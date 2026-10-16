<?php

namespace Tests\Feature;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use App\Mail\NewLeadArrived;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class NotifyNewLeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_request_notifies_the_responsible_person_by_email_and_telegram(): void
    {
        Mail::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);
        $this->noticeChannels();

        $this->post(route('leads.store'), [
            'name' => 'امیررضا',
            'contact' => 'Amir@Example.com',
            'service' => LeadServiceInterest::Software->value,
            'problem' => 'فرآیند سفارش‌ها پراکنده است و به یک سیستم نیاز داریم.',
        ])->assertRedirect(route('home', ['intent' => 'request']).'#contact')
            ->assertSessionHas('lead_received', true);

        $lead = Lead::query()->first();

        $this->assertNotNull($lead);
        $this->assertSame(LeadStatus::New, $lead->status);

        Mail::assertSent(NewLeadArrived::class, function (NewLeadArrived $mail) use ($lead): bool {
            $body = $mail->body();

            return $mail->hasTo('notice@roshdino.test')
                && $mail->envelope()->subject === 'سرنخ تازه: امیررضا'
                && $mail->lead->is($lead)
                && str_contains($body, 'اقدام: درخواست')
                && str_contains($body, 'خدمت: نرم‌افزار اختصاصی')
                && str_contains($body, 'راه تماس: amir@example.com')
                && str_contains($body, 'مسئله: فرآیند سفارش‌ها پراکنده است و به یک سیستم نیاز داریم.')
                && str_contains($body, 'وضعیت: جدید')
                && str_contains($body, route('leads.index'));
        });

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.telegram.org/bot123:test/sendMessage'
                && $request['chat_id'] === '@roshdino_notice'
                && str_contains($request['text'], 'نام: امیررضا')
                && str_contains($request['text'], 'اقدام: درخواست');
        });
    }

    public function test_a_booked_meeting_includes_the_tehran_time_and_a_contact_lead_omits_the_problem(): void
    {
        Mail::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);
        $this->noticeChannels(chatId: '424242');

        $this->post(route('leads.store'), [
            'name' => 'نسترن',
            'contact' => '09121234567',
            'service' => LeadServiceInterest::Web->value,
            'intent' => LeadIntent::Meeting->value,
            'meets_at' => '2026-10-12T16:00',
        ])->assertSessionHas('lead_received', true);

        Mail::assertSent(NewLeadArrived::class, function (NewLeadArrived $mail): bool {
            $body = $mail->body();

            return str_contains($body, 'اقدام: رزرو جلسه')
                && str_contains($body, 'زمان جلسه: 2026-10-12 16:00')
                && ! str_contains($body, 'مسئله:');
        });

        Http::assertSent(fn ($request): bool => $request['chat_id'] === '424242'
            && str_contains($request['text'], 'زمان جلسه: 2026-10-12 16:00'));

        $this->assertSame(LeadStatus::New, Lead::query()->first()?->status);

        Mail::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true]),
        ]);

        $this->post(route('leads.store'), [
            'name' => 'سارا',
            'contact' => '09120000000',
            'intent' => LeadIntent::Contact->value,
        ])->assertSessionHas('lead_received', true);

        Mail::assertSent(NewLeadArrived::class, function (NewLeadArrived $mail): bool {
            return str_contains($mail->body(), 'اقدام: راه تماس')
                && str_contains($mail->body(), 'راه تماس: 09120000000')
                && ! str_contains($mail->body(), 'مسئله:')
                && ! str_contains($mail->body(), 'زمان جلسه:');
        });
    }

    public function test_spam_and_invalid_submissions_are_not_announced(): void
    {
        Mail::fake();
        Http::fake();
        $this->noticeChannels();

        $this->post(route('leads.store'), [
            'name' => 'ربات',
            'contact' => 'bot@example.com',
            'problem' => 'این یک پیام خودکار و بی‌ربط است.',
            'company_website' => 'https://spam.example',
        ])->assertRedirect();

        $this->post(route('leads.store'), [
            'name' => 'سارا',
            'contact' => 'not-a-contact',
            'problem' => 'یک توضیح کافی برای مسئله کسب‌وکار.',
        ])->assertSessionHasErrors('contact');

        Mail::assertNothingSent();
        Http::assertNothingSent();
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_a_delivery_failure_still_keeps_the_new_lead(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('smtp down'));
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => false], 500),
        ]);
        $this->noticeChannels();

        $this->post(route('leads.store'), [
            'name' => 'امیررضا',
            'contact' => '09120000000',
            'problem' => 'می‌خواهیم مسیر ثبت سفارش ساده شود.',
        ])->assertRedirect(route('home', ['intent' => 'request']).'#contact')
            ->assertSessionHas('lead_received', true);

        $this->assertDatabaseHas('leads', [
            'name' => 'امیررضا',
            'status' => LeadStatus::New->value,
        ]);

        Http::assertSent(fn ($request): bool => str_contains($request->url(), '/sendMessage'));
    }

    public function test_telegram_is_skipped_without_a_bot_token_and_email_still_goes_out(): void
    {
        Mail::fake();
        Http::fake();
        $this->noticeChannels(token: null);

        $this->post(route('leads.store'), [
            'name' => 'امیررضا',
            'contact' => '09120000000',
            'problem' => 'می‌خواهیم مسیر ثبت سفارش ساده شود.',
        ])->assertSessionHas('lead_received', true);

        Mail::assertSent(NewLeadArrived::class);
        Http::assertNothingSent();
    }

    private function noticeChannels(?string $token = '123:test', ?string $chatId = null): void
    {
        config([
            'roshdino.contact.email' => 'notice@roshdino.test',
            'roshdino.contact.telegram' => 'roshdino_notice',
            'roshdino.contact.telegram_bot_token' => $token,
            'roshdino.contact.telegram_chat_id' => $chatId,
        ]);
    }
}
