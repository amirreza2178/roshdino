<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UpdateLeadStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_change_a_lead_status(): void
    {
        $lead = Lead::factory()->create();

        $this->patch(route('leads.status.update', $lead), [
            'status' => LeadStatus::Contacted->value,
        ])->assertRedirect(route('login'));

        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_responsible_user_can_move_a_lead_along_the_sales_path(): void
    {
        $lead = Lead::factory()->create(['name' => 'نسترن']);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('leads.status.update', $lead), [
                'status' => LeadStatus::Meeting->value,
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('status_updated', $lead->id);

        $this->assertSame(LeadStatus::Meeting, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('جلسه')
            ->assertSee('وضعیت ثبت شد.')
            ->assertSee('value="meeting" selected', false);
    }

    public function test_a_status_outside_the_sales_path_is_rejected(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->from(route('leads.index'))
            ->patch(route('leads.status.update', $lead), [
                'status' => 'reviewed',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('status', null, 'lead-'.$lead->id);

        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_legacy_statuses_map_onto_the_sales_path(): void
    {
        $reviewed = Lead::factory()->create();
        $closed = Lead::factory()->create();
        $fresh = Lead::factory()->create();

        DB::table('leads')->where('id', $reviewed->id)->update(['status' => 'reviewed']);
        DB::table('leads')->where('id', $closed->id)->update(['status' => 'closed']);

        $migration = require database_path('migrations/2026_10_10_160000_map_legacy_lead_statuses.php');
        $migration->up();

        $this->assertSame(LeadStatus::Contacted, $reviewed->fresh()->status);
        $this->assertSame(LeadStatus::Lost, $closed->fresh()->status);
        $this->assertSame(LeadStatus::New, $fresh->fresh()->status);
    }
}
