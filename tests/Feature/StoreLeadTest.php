<?php

namespace Tests\Feature;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreLeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_submit_a_problem(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => 'امیررضا',
            'contact' => 'Amir@Example.com',
            'service' => LeadServiceInterest::Software->value,
            'problem' => 'فرآیند سفارش‌ها پراکنده است و به یک سیستم نیاز داریم.',
        ]);

        $response
            ->assertRedirect(route('home', ['intent' => 'request']).'#contact')
            ->assertSessionHas('lead_received', true);

        $this->assertDatabaseHas('leads', [
            'name' => 'امیررضا',
            'contact' => 'amir@example.com',
            'service' => LeadServiceInterest::Software->value,
            'status' => LeadStatus::New->value,
            'source' => 'website',
            'intent' => LeadIntent::Request->value,
        ]);
    }

    public function test_a_visitor_can_leave_contact_details_without_a_problem(): void
    {
        $this->post(route('leads.store'), [
            'name' => 'نسترن',
            'contact' => '09121234567',
            'intent' => LeadIntent::Contact->value,
        ])->assertRedirect(route('home', ['intent' => 'contact']).'#contact')
            ->assertSessionHas('lead_received', true);

        $lead = Lead::query()->first();

        $this->assertNotNull($lead);
        $this->assertSame(LeadIntent::Contact, $lead->intent);
        $this->assertSame('', $lead->problem);
        $this->assertSame(LeadStatus::New, $lead->status);
        $this->assertSame(0, $lead->meetings()->count());
    }

    public function test_a_visitor_can_book_a_meeting_without_changing_lead_status(): void
    {
        $this->post(route('leads.store'), [
            'name' => 'نسترن',
            'contact' => '09121234567',
            'service' => LeadServiceInterest::Web->value,
            'intent' => LeadIntent::Meeting->value,
            'meets_at' => '2026-10-12T16:00',
        ])->assertRedirect(route('home', ['intent' => 'meeting']).'#contact');

        $lead = Lead::query()->first();
        $meeting = $lead?->meetings()->first();

        $this->assertNotNull($lead);
        $this->assertSame(LeadIntent::Meeting, $lead->intent);
        $this->assertSame(LeadStatus::New, $lead->status);
        $this->assertNotNull($meeting);
        $this->assertSame('2026-10-12 16:00', $meeting->meets_at?->timezone('Asia/Tehran')->format('Y-m-d H:i'));
    }

    public function test_a_request_still_needs_a_problem_and_a_meeting_still_needs_a_time(): void
    {
        $this->post(route('leads.store'), [
            'name' => 'نسترن',
            'contact' => '09121234567',
            'intent' => LeadIntent::Request->value,
            'problem' => 'کوتاه',
        ])->assertRedirect(route('home', ['intent' => 'request']).'#contact')
            ->assertSessionHasErrors('problem');

        $this->post(route('leads.store'), [
            'name' => 'نسترن',
            'contact' => '09121234567',
            'intent' => LeadIntent::Meeting->value,
        ])->assertRedirect(route('home', ['intent' => 'meeting']).'#contact')
            ->assertSessionHasErrors('meets_at');

        $this->post(route('leads.store'), [
            'name' => 'نسترن',
            'contact' => '09121234567',
            'intent' => 'project',
            'problem' => 'یک توضیح کافی برای مسئله کسب‌وکار.',
        ])->assertRedirect(route('home', ['intent' => 'project']).'#contact')
            ->assertSessionHasErrors('intent');

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_a_mobile_number_is_stored_in_local_format(): void
    {
        $this->post(route('leads.store'), [
            'name' => 'سارا',
            'contact' => '+98 912 000 0000',
            'problem' => 'سایت فعلی مشتری را به تماس نمی‌رساند.',
        ])->assertRedirect(route('home', ['intent' => 'request']).'#contact');

        $lead = Lead::query()->first();

        $this->assertNotNull($lead);
        $this->assertSame('09120000000', $lead->contact);
        $this->assertSame(LeadServiceInterest::Unsure, $lead->service);
    }

    public function test_invalid_contact_is_rejected(): void
    {
        $this->from(route('home'))
            ->post(route('leads.store'), [
                'name' => 'سارا',
                'contact' => 'not-a-contact',
                'problem' => 'یک توضیح کافی برای مسئله کسب‌وکار.',
            ])
            ->assertRedirect(route('home', ['intent' => 'request']).'#contact')
            ->assertSessionHasErrors('contact');

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_honeypot_submissions_are_not_stored(): void
    {
        $this->post(route('leads.store'), [
            'name' => 'ربات',
            'contact' => 'bot@example.com',
            'problem' => 'این یک پیام خودکار و بی‌ربط است.',
            'company_website' => 'https://spam.example',
        ])->assertRedirect(route('home', ['intent' => 'request']).'#contact');

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_repeated_submissions_are_throttled(): void
    {
        $payload = [
            'name' => 'امیررضا',
            'contact' => '09120000000',
            'problem' => 'می‌خواهیم مسیر ثبت سفارش ساده شود.',
        ];

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('leads.store'), $payload)->assertRedirect();
        }

        $this->post(route('leads.store'), $payload)->assertTooManyRequests();
        $this->assertDatabaseCount('leads', 5);
    }
}
