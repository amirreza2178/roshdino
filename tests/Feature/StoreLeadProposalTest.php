<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreLeadProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_a_proposal(): void
    {
        $lead = Lead::factory()->create();

        $this->post(route('leads.proposals.store', $lead), [
            'scope' => 'طراحی سایت فروشگاهی و مسیر خرید.',
            'amount' => '15000000',
            'status' => ProposalStatus::Sent->value,
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $lead->proposals()->count());
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_responsible_user_records_a_proposal_without_changing_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'status' => LeadStatus::Meeting,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.proposals.store', $lead), [
                'scope' => 'طراحی سایت فروشگاهی و مسیر خرید.',
                'amount' => '15,000,000',
                'status' => ProposalStatus::Sent->value,
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('proposal_saved', $lead->id);

        $proposal = $lead->proposals()->first();

        $this->assertNotNull($proposal);
        $this->assertSame('طراحی سایت فروشگاهی و مسیر خرید.', $proposal->scope);
        $this->assertSame(15000000, $proposal->amount);
        $this->assertSame(ProposalStatus::Sent, $proposal->status);
        $this->assertSame(LeadStatus::Meeting, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('طراحی سایت فروشگاهی و مسیر خرید.')
            ->assertSee('15,000,000')
            ->assertSee('تومان')
            ->assertSee('فرستاده شد')
            ->assertSee('پیشنهاد ثبت شد.')
            ->assertSee('value="meeting" selected', false);
    }

    public function test_scope_and_amount_are_required(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.proposals.store', $lead), [
                'scope' => '   ',
                'amount' => '',
                'status' => ProposalStatus::Draft->value,
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors(['scope', 'amount'], null, 'proposal-'.$lead->id);

        $this->assertSame(0, $lead->proposals()->count());
    }

    public function test_a_status_outside_the_proposal_path_is_rejected(): void
    {
        $lead = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.proposals.store', $lead), [
                'scope' => 'طراحی سایت فروشگاهی و مسیر خرید.',
                'amount' => '15000000',
                'status' => 'won',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('status', null, 'proposal-'.$lead->id);

        $this->assertSame(0, $lead->proposals()->count());
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }
}
