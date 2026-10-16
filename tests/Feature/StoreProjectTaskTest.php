<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\ProjectTaskStatus;
use App\Enums\ProposalStatus;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_record_a_project_task(): void
    {
        [$lead, $project] = $this->leadWithProject();

        $this->post(route('leads.projects.tasks.store', [$lead, $project]), [
            'title' => 'آماده‌سازی محتوای صفحهٔ اصلی',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $project->tasks()->count());
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);
    }

    public function test_responsible_user_records_a_project_task_without_changing_lead_status(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('leads.projects.tasks.store', [$lead, $project]), [
                'title' => 'آماده‌سازی محتوای صفحهٔ اصلی',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('project_task_saved', $lead->id);

        $task = $project->tasks()->first();

        $this->assertNotNull($task);
        $this->assertSame('آماده‌سازی محتوای صفحهٔ اصلی', $task->title);
        $this->assertSame(ProjectTaskStatus::Open, $task->status);
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('وظایف پروژه')
            ->assertSee('آماده‌سازی محتوای صفحهٔ اصلی')
            ->assertSee('باز')
            ->assertSee('وظیفه ثبت شد.')
            ->assertSee('value="won" selected', false);
    }

    public function test_a_project_task_title_is_required(): void
    {
        [$lead, $project] = $this->leadWithProject();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.projects.tasks.store', [$lead, $project]), [
                'title' => '   ',
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('title', null, 'project-task-'.$lead->id.'-'.$project->id);

        $this->assertSame(0, $project->tasks()->count());
    }

    public function test_responsible_user_marks_a_project_task_done_without_changing_lead_status(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $task = $project->tasks()->create([
            'title' => 'تست نهایی فرم تماس',
            'status' => ProjectTaskStatus::Open,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('leads.projects.tasks.status.update', [$lead, $project, $task]), [
                'status' => ProjectTaskStatus::Done->value,
            ])
            ->assertRedirect(route('leads.index'))
            ->assertSessionHas('project_task_status_saved', $lead->id);

        $this->assertSame(ProjectTaskStatus::Done, $task->fresh()->status);
        $this->assertSame(LeadStatus::Won, $lead->fresh()->status);

        $this->actingAs($user)
            ->get(route('leads.index'))
            ->assertOk()
            ->assertSee('تست نهایی فرم تماس')
            ->assertSee('انجام شد')
            ->assertSee('وضعیت وظیفه ثبت شد.')
            ->assertDontSee('انجام شد</button>', false);
    }

    public function test_a_project_from_another_lead_cannot_receive_tasks(): void
    {
        [$lead, $project] = $this->leadWithProject();
        $other = Lead::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('leads.projects.tasks.store', [$other, $project]), [
                'title' => 'وظیفهٔ نامعتبر',
            ])
            ->assertNotFound();

        $this->assertSame(0, $project->tasks()->count());
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
