<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreProjectSupportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_project_support(): void
    {
        [$lead, $project] = $this->leadWithProject();

        $this->post(route('leads.projects.supports.store', [$lead, $project]), [
            'supported_at' => '2026-11-01T10:00',
            'note' => 'فرم تماس روی موبایل اصلاح شد.',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $project->supports()->count());
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);
    }

    public function test_responsible_user_records_project_support_without_changing_lead_status(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.projects.supports.store', [$lead, $project]), [
                'supported_at' => '2026-11-01T10:00',
                'note' => 'فرم تماس روی موبایل اصلاح شد.',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('project_support_saved', $lead->id);

        $support = $project->supports()->first();

        $this->assertNotNull($support);
        $this->assertSame('فرم تماس روی موبایل اصلاح شد.', $support->note);
        $this->assertSame('2026-11-01 10:00', $support->supported_at->timezone('Asia/Tehran')->format('Y-m-d H:i'));
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('پشتیبانی')
            ->assertSee('2026-11-01 10:00')
            ->assertSee('فرم تماس روی موبایل اصلاح شد.')
            ->assertSee('پشتیبانی ثبت شد.')
            ->assertSee('value="won" selected', false);
    }

    public function test_a_project_support_note_is_required(): void
    {
        [$lead, $project] = $this->leadWithProject();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.projects.supports.store', [$lead, $project]), [
                'supported_at' => '2026-11-01T10:00',
                'note' => '   ',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('note', null, 'project-support-'.$lead->id.'-'.$project->id);

        $this->assertSame(0, $project->supports()->count());
    }

    public function test_a_project_from_another_lead_cannot_receive_support_notes(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $other = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.projects.supports.store', [$other, $project]), [
                'supported_at' => '2026-11-01T10:00',
                'note' => 'یادداشت نامعتبر',
            ])
            ->assertNotFound();

        $this->assertSame(0, $project->supports()->count());
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
