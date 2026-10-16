<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadProposal;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConvertAcceptedProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_convert_an_accepted_proposal(): void
    {
        $lead = Lead::factory()->create();
        $proposal = $this->proposal($lead, ProposalStatus::Accepted);

        $this->post(route('leads.proposals.convert', [$lead, $proposal]))
            ->assertRedirect(route('login'));

        $this->assertSame(0, Client::query()->count());
        $this->assertSame(0, Project::query()->count());
        $this->assertSame(LeadStatus::New, $lead->fresh()->status);
    }

    public function test_responsible_user_turns_an_accepted_proposal_into_a_client_and_project(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'contact' => '09121234567',
            'status' => LeadStatus::New,
        ]);
        $proposal = $this->proposal($lead, ProposalStatus::Accepted);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('شروع پروژه')
            ->assertDontSee('پروژه شروع شد.');

        $this->actingAs($user)
            ->post(route('leads.proposals.convert', [$lead, $proposal]))
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('project_started', $proposal->id);

        $client = Client::query()->where('lead_id', $lead->id)->first();
        $project = Project::query()->where('lead_proposal_id', $proposal->id)->first();

        $this->assertNotNull($client);
        $this->assertSame('نسترن', $client->name);
        $this->assertSame('09121234567', $client->contact);
        $this->assertNotNull($project);
        $this->assertSame($client->id, $project->client_id);
        $this->assertSame($lead->id, $project->lead_id);
        $this->assertSame($proposal->id, $project->lead_proposal_id);
        $this->assertSame('طراحی سایت فروشگاهی و مسیر خرید.', $project->scope);
        $this->assertSame(15000000, $project->amount);
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('مشتری')
            ->assertSee('نسترن')
            ->assertSee('09121234567')
            ->assertSee('طراحی سایت فروشگاهی و مسیر خرید.')
            ->assertSee('15,000,000')
            ->assertSee('پروژه شروع شد.')
            ->assertSee('value="won" selected', false)
            ->assertDontSee('شروع پروژه');
    }

    public function test_a_proposal_that_is_not_accepted_cannot_start_a_project(): void
    {
        $user = User::factory()->create();

        foreach ([ProposalStatus::Draft, ProposalStatus::Sent, ProposalStatus::Rejected] as $status) {
            $lead = Lead::factory()->create();
            $proposal = $this->proposal($lead, $status);

            $this->actingAs($user)
                ->post(route('leads.proposals.convert', [$lead, $proposal]))
                ->assertRedirect(route('leads.index'))
                ->assertSessionHasErrors('proposal', null, 'convert-'.$proposal->id);

            $this->assertSame(LeadStatus::New, $lead->fresh()->status);
        }

        $this->assertSame(0, Client::query()->count());
        $this->assertSame(0, Project::query()->count());

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertDontSee('شروع پروژه');
    }

    public function test_an_accepted_proposal_cannot_be_converted_twice(): void
    {
        $lead = Lead::factory()->create(['status' => LeadStatus::Proposal]);
        $proposal = $this->proposal($lead, ProposalStatus::Accepted);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.proposals.convert', [$lead, $proposal]))
            ->assertRedirect(route('leads.index'));

        $this->actingAs($user)
            ->post(route('leads.proposals.convert', [$lead, $proposal]))
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('proposal', null, 'convert-'.$proposal->id);

        $this->assertSame(1, Client::query()->count());
        $this->assertSame(1, Project::query()->count());
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);
    }

    public function test_a_second_accepted_proposal_on_the_same_lead_reuses_the_client(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'نسترن',
            'contact' => '09121234567',
        ]);
        $first = $this->proposal($lead, ProposalStatus::Accepted, 'نسخه اول فروشگاه.');
        $second = $this->proposal($lead, ProposalStatus::Accepted, 'نسخه دوم فروشگاه.', 8000000);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('leads.proposals.convert', [$lead, $first]))->assertRedirect();
        $this->actingAs($user)->post(route('leads.proposals.convert', [$lead, $second]))->assertRedirect();

        $client = Client::query()->where('lead_id', $lead->id)->first();
        $secondProject = Project::query()->where('lead_proposal_id', $second->id)->first();

        $this->assertSame(1, Client::query()->count());
        $this->assertSame(2, $lead->projects()->count());
        $this->assertNotNull($client);
        $this->assertNotNull($secondProject);
        $this->assertSame($client->id, $secondProject->client_id);
        $this->assertSame(8000000, $secondProject->amount);
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);
    }

    public function test_a_proposal_from_another_lead_cannot_be_converted(): void
    {
        $lead = Lead::factory()->create();
        $other = Lead::factory()->create();
        $proposal = $this->proposal($other, ProposalStatus::Accepted);

        $this->actingAs(User::factory()->create())
            ->post(route('leads.proposals.convert', [$lead, $proposal]))
            ->assertNotFound();

        $this->assertSame(0, Client::query()->count());
        $this->assertSame(0, Project::query()->count());
        $this->assertSame(LeadStatus::New, $other->fresh()->status);
    }

    private function proposal(Lead $lead, ProposalStatus $status, string $scope = 'طراحی سایت فروشگاهی و مسیر خرید.', int $amount = 15000000): LeadProposal
    {
        return $lead->proposals()->create([
            'scope' => $scope,
            'amount' => $amount,
            'status' => $status,
        ]);
    }
}
