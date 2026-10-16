<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StoreLeadMeetingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_a_meeting(): void
    {
        $lead = Lead::factory()->create();

        $this->post(route('leads.meetings.store', $lead), [
            'meets_at' => '2026-10-12T16:00',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $lead->meetings()->count());
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_responsible_user_records_a_meeting_without_changing_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'status' => LeadStatus::Contacted,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.meetings.store', $lead), [
                'meets_at' => '2026-10-12T16:00',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('meeting_saved', $lead->id);

        $meeting = $lead->meetings()->first();

        $this->assertNotNull($meeting);
        $this->assertSame('2026-10-12 16:00', $meeting->meets_at->timezone('Asia/Tehran')->format('Y-m-d H:i'));
        $this->assertSame(LeadStatus::Contacted, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('2026-10-12 16:00')
            ->assertSee('جلسه ثبت شد.')
            ->assertSee('value="contacted" selected', false);
    }

    public function test_a_meeting_time_is_required(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.meetings.store', $lead), [
                'meets_at' => '',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('meets_at', null, 'meeting-'.$lead->id);

        $this->assertSame(0, $lead->meetings()->count());
    }

    public function test_meeting_time_is_shown_in_tehran(): void
    {
        $lead = Lead::factory()->create();
        $lead->meetings()->create([
            'meets_at' => Carbon::parse('2026-10-12 12:30:00', 'UTC'),
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('2026-10-12 16:00');
    }
}
