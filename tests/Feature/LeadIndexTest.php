<?php

namespace Tests\Feature;

use App\Enums\FollowUpChannel;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_login_before_the_inbox(): void
    {
        $this->get(route('leads.index'))->assertRedirect(route('login'));
    }

    public function test_responsible_user_sees_submitted_leads(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'problem' => 'مسیر ثبت سفارش فروشگاه نامشخص است.',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('نسترن')
            ->assertSee($lead->problem)
            ->assertSee('جدید')
            ->assertSee('ثبت وضعیت')
            ->assertSee('خروج')
            ->assertDontSee('فقط روی محیط محلی')
            ->assertDontSee('reviewed');
    }

    public function test_older_leads_move_to_the_next_page(): void
    {
        $older = Lead::factory()->create([
            'name' => 'سرنخ صفحه دو',
            'created_at' => now()->subDay(),
        ]);
        $older->followUps()->create([
            'followed_up_at' => now()->subHour(),
            'channel' => FollowUpChannel::Call,
            'note' => 'تماس اول انجام شد.',
        ]);
        Lead::factory()->count(20)->create([
            'created_at' => now(),
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertDontSee('سرنخ صفحه دو')
            ->assertSee('1 از 2')
            ->assertSee('بعدی');

        $this->actingAs($user)
            ->get(route('leads.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('سرنخ صفحه دو')
            ->assertSee('قبلی');
    }

    public function test_sales_inbox_stays_closed_for_guests_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->get(route('leads.index'))->assertRedirect(route('login'));
    }

    public function test_signed_in_responsible_user_can_open_the_inbox_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        Lead::factory()->create(['name' => 'نسترن']);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('نسترن');
    }
}
