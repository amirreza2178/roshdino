<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StoreLeadConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_a_conversation(): void
    {
        $lead = Lead::factory()->create();

        $this->post(route('leads.conversations.store', $lead), [
            'conversed_at' => '2026-10-10T16:10',
            'body' => 'بودجه را تا ماه بعد مشخص می‌کند.',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $lead->conversations()->count());
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_responsible_user_records_a_conversation_without_changing_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'status' => LeadStatus::Contacted,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.conversations.store', $lead), [
                'conversed_at' => '2026-10-10T16:10',
                'body' => 'بودجه را تا ماه بعد مشخص می‌کند.',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('conversation_saved', $lead->id);

        $conversation = $lead->conversations()->first();

        $this->assertNotNull($conversation);
        $this->assertSame('بودجه را تا ماه بعد مشخص می‌کند.', $conversation->body);
        $this->assertSame('2026-10-10 16:10', $conversation->conversed_at->timezone('Asia/Tehran')->format('Y-m-d H:i'));
        $this->assertSame(LeadStatus::Contacted, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('گفتگوها')
            ->assertSee('2026-10-10 16:10')
            ->assertSee('بودجه را تا ماه بعد مشخص می‌کند.')
            ->assertSee('گفتگو ثبت شد.')
            ->assertSee('value="contacted" selected', false);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('بودجه را تا ماه بعد مشخص می‌کند.')
            ->assertDontSee('گفتگو ثبت شد.');
    }

    public function test_conversation_text_is_required(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.conversations.store', $lead), [
                'conversed_at' => '2026-10-10T16:10',
                'body' => '   ',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('body', null, 'conversation-'.$lead->id);

        $this->assertSame(0, $lead->conversations()->count());
    }

    public function test_conversation_time_is_shown_in_tehran(): void
    {
        $lead = Lead::factory()->create();
        $lead->conversations()->create([
            'conversed_at' => Carbon::parse('2026-10-10 12:40:00', 'UTC'),
            'body' => 'ساعت تهران برای گفتگو درست است.',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('2026-10-10 16:10')
            ->assertSee('ساعت تهران برای گفتگو درست است.');
    }
}
