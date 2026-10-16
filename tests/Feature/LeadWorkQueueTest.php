<?php

namespace Tests\Feature;

use App\Enums\FollowUpChannel;
use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use App\Enums\ProposalStatus;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadWorkQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_today_queue_shows_only_open_work_and_does_not_change_status(): void
    {
        $this->travelTo(Carbon::parse('2026-10-11 15:00:00', 'Asia/Tehran'));

        $waiting = Lead::factory()->create([
            'name' => 'منتظر تماس',
            'service' => LeadServiceInterest::Web,
            'created_at' => now()->subDays(2),
        ]);
        $newerWaiting = Lead::factory()->create([
            'name' => 'تماس تازه‌تر',
            'created_at' => now()->subDay(),
        ]);
        $followed = Lead::factory()->create(['name' => 'پیگیری‌شده']);
        $followed->followUps()->create([
            'followed_up_at' => now()->subHour(),
            'channel' => FollowUpChannel::Call,
            'note' => 'تماس اول انجام شد.',
        ]);
        Lead::factory()->create([
            'name' => 'فروش تمام',
            'status' => LeadStatus::Won,
        ]);

        $meetingLead = Lead::factory()->create(['name' => 'جلسه فردا', 'status' => LeadStatus::Contacted]);
        $meetingLead->meetings()->create([
            'meets_at' => Carbon::parse('2026-10-12 11:30:00', 'Asia/Tehran')->utc(),
        ]);
        $meetingLead->meetings()->create([
            'meets_at' => Carbon::parse('2026-10-11 10:00:00', 'Asia/Tehran')->utc(),
        ]);
        $meetingLead->meetings()->create([
            'meets_at' => Carbon::parse('2026-10-18 09:00:00', 'Asia/Tehran')->utc(),
        ]);
        $meetingLead->meetings()->create([
            'meets_at' => Carbon::parse('2026-10-10 18:00:00', 'Asia/Tehran')->utc(),
        ]);
        $closedMeeting = Lead::factory()->create(['name' => 'جلسهٔ بسته', 'status' => LeadStatus::Lost]);
        $closedMeeting->meetings()->create([
            'meets_at' => Carbon::parse('2026-10-12 11:30:00', 'Asia/Tehran')->utc(),
        ]);

        $proposalLead = Lead::factory()->create(['name' => 'منتظر جواب', 'status' => LeadStatus::Proposal]);
        $proposalLead->proposals()->create([
            'scope' => 'طراحی سایت فروشگاهی و مسیر خرید.',
            'amount' => 15000000,
            'status' => ProposalStatus::Sent,
            'created_at' => now()->subDay(),
        ]);
        $proposalLead->proposals()->create([
            'scope' => 'پیش‌نویس داخلی.',
            'amount' => 1000000,
            'status' => ProposalStatus::Draft,
        ]);
        $lostProposal = Lead::factory()->create(['name' => 'پیشنهاد بسته', 'status' => LeadStatus::Lost]);
        $lostProposal->proposals()->create([
            'scope' => 'پیشنهاد از دست رفته.',
            'amount' => 2000000,
            'status' => ProposalStatus::Sent,
        ]);

        $older = Lead::factory()->create([
            'name' => 'صفحهٔ دوم صف',
            'created_at' => now()->subDays(3),
        ]);
        Lead::factory()->count(20)->create(['created_at' => now()]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('leads.index'));

        $response->assertOk();
        $queue = $this->queueHtml($response->getContent());

        $this->assertStringContainsString('منتظر تماس', $queue);
        $this->assertStringContainsString('هنوز پیگیری نشده', $queue);
        $this->assertLessThan(strpos($queue, 'تماس تازه‌تر'), strpos($queue, 'منتظر تماس'));
        $this->assertStringNotContainsString('پیگیری‌شده', $queue);
        $this->assertStringNotContainsString('فروش تمام', $queue);
        $this->assertStringContainsString('جلسه فردا', $queue);
        $this->assertStringContainsString('2026-10-11 10:00', $queue);
        $this->assertStringContainsString('2026-10-12 11:30', $queue);
        $this->assertStringNotContainsString('2026-10-18 09:00', $queue);
        $this->assertStringNotContainsString('2026-10-10 18:00', $queue);
        $this->assertStringNotContainsString('جلسهٔ بسته', $queue);
        $this->assertStringContainsString('طراحی سایت فروشگاهی و مسیر خرید.', $queue);
        $this->assertStringNotContainsString('پیش‌نویس داخلی.', $queue);
        $this->assertStringNotContainsString('پیشنهاد از دست رفته.', $queue);
        $this->assertStringContainsString(route('leads.index', ['page' => 2]).'#lead-'.$older->id, $queue);

        $this->assertSame(LeadStatus::New, $waiting->refresh()->status);
        $this->assertSame(LeadStatus::New, $newerWaiting->refresh()->status);
    }

    public function test_empty_queue_says_there_is_nothing_due_today(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'بدون کار امروز',
            'status' => LeadStatus::Contacted,
        ]);
        $lead->followUps()->create([
            'followed_up_at' => now()->subHour(),
            'channel' => FollowUpChannel::Message,
            'note' => 'پیام داده شد.',
        ]);

        $response = $this->actingAs(User::factory()->create())->get(route('leads.index'));

        $response->assertOk()->assertSee('برای امروز کاری در صف نیست.');
        $this->assertStringNotContainsString('اولین تماس', $this->queueHtml($response->getContent()));
    }

    public function test_filters_narrow_the_list_and_leave_the_queue_intact(): void
    {
        Lead::factory()->create([
            'name' => 'وب تازه',
            'service' => LeadServiceInterest::Web,
            'intent' => LeadIntent::Request,
        ]);
        Lead::factory()->create([
            'name' => 'نرم‌افزار تنها',
            'service' => LeadServiceInterest::Software,
            'intent' => LeadIntent::Contact,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('leads.index', ['service' => 'web']));

        $response->assertOk()->assertSee('پاک کردن فیلتر');
        $this->assertStringContainsString('نرم‌افزار تنها', $this->queueHtml($response->getContent()));
        $this->assertStringNotContainsString('نرم‌افزار تنها', $this->listHtml($response->getContent()));
        $this->assertStringContainsString('وب تازه', $this->listHtml($response->getContent()));
    }

    public function test_filter_pagination_keeps_the_selected_service(): void
    {
        Lead::factory()->create([
            'name' => 'وب صفحه دو',
            'service' => LeadServiceInterest::Web,
            'created_at' => now()->subDay(),
        ]);
        Lead::factory()->count(20)->create([
            'service' => LeadServiceInterest::Web,
            'created_at' => now(),
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index', ['service' => 'web']))
            ->assertOk()
            ->assertSee('service=web', false);
    }

    public function test_unknown_filter_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('leads.index'))
            ->get(route('leads.index', ['status' => 'reviewed']))
            ->assertRedirect(route('leads.index'))
            ->assertSessionHasErrors('status');
    }

    public function test_filtered_empty_list_has_its_own_message(): void
    {
        Lead::factory()->create(['service' => LeadServiceInterest::Web]);

        $this->actingAs(User::factory()->create())
            ->get(route('leads.index', ['service' => 'growth']))
            ->assertOk()
            ->assertSee('با این فیلتر سرنخی نیست.')
            ->assertDontSee('هنوز مسئله‌ای ثبت نشده.');
    }

    private function queueHtml(string $html): string
    {
        $start = strpos($html, 'id="work-queue"');
        $end = strpos($html, 'id="all-leads"');

        return substr($html, $start, $end - $start);
    }

    private function listHtml(string $html): string
    {
        return substr($html, (int) strpos($html, 'id="all-leads"'));
    }
}
