<?php

namespace Tests\Feature;

use App\Models\HomePage;
use App\Models\User;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeCopyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_edit_the_homepage_copy(): void
    {
        $this->get(route('site.home-copy.edit'))->assertRedirect(route('login'));
    }

    public function test_staff_can_change_the_hero_and_the_public_home_follows(): void
    {
        $story = HomePage::defaults();
        $story['hero']['title'] = 'یک عنوان تازه برای قهرمان صفحه.';
        $story['hero']['accent'] = 'قهرمان صفحه';

        $this->actingAs(User::factory()->create())
            ->put(route('site.home-copy.update'), $story)
            ->assertRedirect(route('site.home-copy.edit'))
            ->assertSessionHas('saved');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('یک عنوان تازه برای', false)
            ->assertSee('<em>قهرمان صفحه</em>', false)
            ->assertDontSee('دیجیتال‌تر، ساده‌تر و قابل‌رشدتر');
    }

    public function test_a_blank_row_is_dropped_and_an_empty_section_is_rejected(): void
    {
        $story = HomePage::defaults();
        $story['faq']['items'][] = ['question' => '', 'answer' => ''];
        $story['problem']['items'] = [['title' => '', 'body' => '']];

        $this->actingAs(User::factory()->create())
            ->from(route('site.home-copy.edit'))
            ->put(route('site.home-copy.update'), $story)
            ->assertRedirect(route('site.home-copy.edit'))
            ->assertSessionHasErrors('problem.items');

        $this->assertNull(HomePage::query()->first());
    }

    public function test_seeder_does_not_overwrite_homepage_copy(): void
    {
        $story = HomePage::defaults();
        $story['hero']['title'] = 'عنوانی که در پنل مانده است.';
        HomePage::query()->create(['story' => $story]);

        $this->seed(SiteContentSeeder::class);

        $this->assertSame(
            'عنوانی که در پنل مانده است.',
            HomePage::query()->firstOrFail()->story['hero']['title'],
        );
    }
}
