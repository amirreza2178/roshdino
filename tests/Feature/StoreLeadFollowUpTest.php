<?php

namespace Tests\Feature;

use App\Enums\FollowUpChannel;
use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StoreLeadFollowUpTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_a_follow_up(): void
    {
        $lead = Lead::factory()->create();

        $this->post(route('leads.follow-ups.store', $lead), [
            'followed_up_at' => '2026-10-10T15:30',
            'channel' => FollowUpChannel::Call->value,
            'note' => 'تماس گرفتم و مسئله را شنیدم.',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $lead->followUps()->count());
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_responsible_user_records_a_follow_up_without_changing_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'status' => LeadStatus::New,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.follow-ups.store', $lead), [
                'followed_up_at' => '2026-10-10T15:30',
                'channel' => FollowUpChannel::Call->value,
                'note' => 'تماس گرفتم و مسئله را شنیدم.',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('follow_up_saved', $lead->id);

        $followUp = $lead->followUps()->first();

        $this->assertNotNull($followUp);
        $this->assertSame(FollowUpChannel::Call, $followUp->channel);
        $this->assertSame('تماس گرفتم و مسئله را شنیدم.', $followUp->note);
        $this->assertSame('2026-10-10 15:30', $followUp->followed_up_at->timezone('Asia/Tehran')->format('Y-m-d H:i'));
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('تماس')
            ->assertSee('2026-10-10 15:30')
            ->assertSee('تماس گرفتم و مسئله را شنیدم.')
            ->assertSee('پیگیری ثبت شد.')
            ->assertSee('value="new" selected', false);
    }

    public function test_a_follow_up_note_is_required(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.follow-ups.store', $lead), [
                'followed_up_at' => '2026-10-10T15:30',
                'channel' => FollowUpChannel::Message->value,
                'note' => '   ',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('note', null, 'follow-up-'.$lead->id);

        $this->assertSame(0, $lead->followUps()->count());
    }

    public function test_a_channel_outside_call_message_and_email_is_rejected(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.follow-ups.store', $lead), [
                'followed_up_at' => '2026-10-10T15:30',
                'channel' => 'sms',
                'note' => 'این کانال مجاز نیست.',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('channel', null, 'follow-up-'.$lead->id);

        $this->assertSame(0, $lead->followUps()->count());
    }

    public function test_follow_up_time_is_shown_in_tehran(): void
    {
        $lead = Lead::factory()->create();
        $lead->followUps()->create([
            'followed_up_at' => Carbon::parse('2026-10-10 12:00:00', 'UTC'),
            'channel' => FollowUpChannel::Email,
            'note' => 'ایمیل دامنه کار را فرستادم.',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('2026-10-10 15:30')
            ->assertSee('ایمیل')
            ->assertSee('ایمیل دامنه کار را فرستادم.');
    }
}
