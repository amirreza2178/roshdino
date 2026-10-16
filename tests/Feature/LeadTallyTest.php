<?php

namespace Tests\Feature;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTallyTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_sees_registration_counts_and_the_public_site_does_not(): void
    {
        Lead::factory()->count(2)->create([
            'intent' => LeadIntent::Request,
            'service' => LeadServiceInterest::Web,
        ]);
        Lead::factory()->create([
            'name' => 'فقط راه تماس',
            'intent' => LeadIntent::Contact,
            'service' => LeadServiceInterest::Software,
        ]);

        $staff = $this->actingAs(User::factory()->create())
            ->get(route('leads.index', ['service' => 'growth']));

        $staff->assertOk();
        $tally = $this->tallyHtml($staff->getContent());
        $this->assertStringContainsString('درخواست', $tally);
        $this->assertStringContainsString('>2<', $tally);
        $this->assertStringContainsString('راه تماس', $tally);
        $this->assertStringContainsString('رزرو جلسه', $tally);
        $this->assertStringContainsString('>0<', $tally);
        $this->assertStringContainsString('طراحی و توسعه وب', $tally);
        $this->assertStringContainsString('نرم‌افزار اختصاصی', $tally);
        $this->assertStringContainsString(route('leads.index', ['intent' => 'request']), $tally);
        $this->assertStringNotContainsString('فقط راه تماس', $this->listHtml($staff->getContent()));

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('ثبت‌ها')
            ->assertDontSee('تعداد سرنخ‌های ذخیره‌شده');
    }

    public function test_an_empty_inbox_says_there_are_no_registrations(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('هنوز ثبتی نیست.');
    }

    private function tallyHtml(string $html): string
    {
        $start = strpos($html, 'id="lead-tally"');
        $end = strpos($html, 'id="all-leads"');

        return substr($html, $start, $end - $start);
    }

    private function listHtml(string $html): string
    {
        return substr($html, (int) strpos($html, 'id="all-leads"'));
    }
}
