<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateLeadValueTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_an_estimated_value(): void
    {
        $lead = Lead::factory()->create([
            'status' => LeadStatus::Contacted,
        ]);

        $this->patch(route('leads.value.update', $lead), [
            'estimated_value' => '15000000',
        ])->assertRedirect(route('login'));

        $lead->refresh();

        $this->assertNull($lead->estimated_value);
        $this->assertSame(LeadStatus::Contacted, $lead->status);
    }

    public function test_responsible_user_records_an_estimated_value_without_changing_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'status' => LeadStatus::Meeting,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('leads.value.update', $lead), [
                'estimated_value' => '15,000,000',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('value_saved', $lead->id);

        $lead->refresh();

        $this->assertSame(15000000, $lead->estimated_value);
        $this->assertSame(LeadStatus::Meeting, $lead->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('ارزش تخمینی')
            ->assertSee('15,000,000')
            ->assertSee('تومان')
            ->assertSee('ارزش ثبت شد.')
            ->assertSee('value="meeting" selected', false);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('15,000,000')
            ->assertDontSee('ارزش تخمینی');
    }

    public function test_a_later_amount_replaces_the_same_estimate(): void
    {
        $lead = Lead::factory()->create([
            'estimated_value' => 1000000,
            'status' => LeadStatus::New,
        ]);

        $this->actingAs(User::factory()->create())
            ->patch(route('leads.value.update', $lead), [
                'estimated_value' => '2500000',
            ])
            ->assertRedirect(route('leads.index'));

        $lead->refresh();

        $this->assertSame(2500000, $lead->estimated_value);
        $this->assertSame(LeadStatus::New, $lead->status);
    }

    public function test_amount_is_required(): void
    {
        $lead = Lead::factory()->create([
            'estimated_value' => 4000000,
        ]);

        $this->actingAs(User::factory()->create())
            ->patch(route('leads.value.update', $lead), [
                'estimated_value' => '   ',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('estimated_value', null, 'value-'.$lead->id);

        $this->assertSame(4000000, $lead->fresh()->estimated_value);
    }
}
