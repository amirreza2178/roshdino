<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreProjectDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_a_project_delivery(): void
    {
        [$lead, $project] = $this->leadWithProject();

        $this->post(route('leads.projects.deliveries.store', [$lead, $project]), [
            'delivered_at' => '2026-10-15T14:00',
            'note' => 'نسخهٔ اول سایت روی دامنهٔ اصلی تحویل شد.',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $project->deliveries()->count());
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);
    }

    public function test_responsible_user_records_a_project_delivery_without_changing_lead_status(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.projects.deliveries.store', [$lead, $project]), [
                'delivered_at' => '2026-10-15T14:00',
                'note' => 'نسخهٔ اول سایت روی دامنهٔ اصلی تحویل شد.',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('project_delivery_saved', $lead->id);

        $delivery = $project->deliveries()->first();

        $this->assertNotNull($delivery);
        $this->assertSame('نسخهٔ اول سایت روی دامنهٔ اصلی تحویل شد.', $delivery->note);
        $this->assertSame('2026-10-15 14:00', $delivery->delivered_at->timezone('Asia/Tehran')->format('Y-m-d H:i'));
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('تحویل‌ها')
            ->assertSee('2026-10-15 14:00')
            ->assertSee('نسخهٔ اول سایت روی دامنهٔ اصلی تحویل شد.')
            ->assertSee('تحویل ثبت شد.')
            ->assertSee('value="won" selected', false);
    }

    public function test_a_project_delivery_note_is_required(): void
    {
        [$lead, $project] = $this->leadWithProject();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.projects.deliveries.store', [$lead, $project]), [
                'delivered_at' => '2026-10-15T14:00',
                'note' => '   ',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('note', null, 'project-delivery-'.$lead->id.'-'.$project->id);

        $this->assertSame(0, $project->deliveries()->count());
    }

    public function test_a_project_from_another_lead_cannot_receive_deliveries(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $other = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.projects.deliveries.store', [$other, $project]), [
                'delivered_at' => '2026-10-15T14:00',
                'note' => 'تحویل نامعتبر',
            ])
            ->assertNotFound();

        $this->assertSame(0, $project->deliveries()->count());
    }

    /**
     * @return array{0: Lead, 1: Project}
     */
    private function leadWithProject(): array
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'contact' => '09121234567',
            'status' => LeadStatus::Won,
        ]);

        $proposal = $lead->proposals()->create([
            'scope' => 'طراحی سایت فروشگاهی و مسیر خرید.',
            'amount' => 15000000,
            'status' => ProposalStatus::Accepted,
        ]);

        $client = $lead->client()->create([
            'name' => $lead->name,
            'contact' => $lead->contact,
        ]);

        $project = Project::query()->create([
            'client_id' => $client->id,
            'lead_id' => $lead->id,
            'lead_proposal_id' => $proposal->id,
            'scope' => $proposal->scope,
            'amount' => $proposal->amount,
        ]);

        return [$lead, $project];
    }
}
