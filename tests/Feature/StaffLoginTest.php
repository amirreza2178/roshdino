<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Database\Seeders\ResponsibleUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('ورود مسئول')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('noindex, nofollow', false)
            ->assertDontSee('شروع یک پروژه');
    }

    public function test_responsible_user_can_sign_in_and_reach_the_sales_inbox(): void
    {
        $user = User::factory()->create([
            'email' => 'masool@roshdino.test',
            'password' => 'correct-password',
        ]);

        Lead::factory()->create([
            'name' => 'نسترن',
            'problem' => 'مسیر ثبت سفارش فروشگاه نامشخص است.',
        ]);

        $this->get(route('leads.index'))->assertRedirect(route('login'));

        $this->post(route('login.store'), [
            'email' => 'Masool@Roshdino.test',
            'password' => 'correct-password',
        ])->assertRedirect(route('leads.index'));

        $this->assertAuthenticatedAs($user);

        $this->get(route('leads.index'))
            ->assertOk()
            ->assertSee('نسترن')
            ->assertSee('مسیر ثبت سفارش فروشگاه نامشخص است.');
    }

    public function test_wrong_password_does_not_open_a_session(): void
    {
        User::factory()->create([
            'email' => 'masool@roshdino.test',
            'password' => 'correct-password',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'masool@roshdino.test',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_closes_the_sales_inbox(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->get(route('leads.index'))->assertRedirect(route('login'));
    }

    public function test_an_authenticated_visit_to_login_goes_to_the_inbox(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('leads.index'));
    }

    public function test_seeder_creates_the_responsible_user_from_config(): void
    {
        config([
            'roshdino.admin.name' => 'مسئول رشدینو',
            'roshdino.admin.email' => 'Masool@Roshdino.test',
            'roshdino.admin.password' => 'a-long-secret-1',
        ]);

        $this->seed(ResponsibleUserSeeder::class);

        $user = User::query()->where('email', 'masool@roshdino.test')->first();

        $this->assertNotNull($user);
        $this->assertSame('مسئول رشدینو', $user->name);
        $this->assertTrue(Hash::check('a-long-secret-1', $user->password));
    }

    public function test_seeder_does_not_replace_an_existing_password(): void
    {
        $user = User::factory()->create([
            'name' => 'نام قبلی',
            'email' => 'masool@roshdino.test',
            'password' => 'changed-secret-1',
        ]);

        config([
            'roshdino.admin.name' => 'مسئول رشدینو',
            'roshdino.admin.email' => 'masool@roshdino.test',
            'roshdino.admin.password' => 'a-long-secret-1',
        ]);

        $this->seed(ResponsibleUserSeeder::class);

        $user->refresh();

        $this->assertSame('مسئول رشدینو', $user->name);
        $this->assertTrue(Hash::check('changed-secret-1', $user->password));
    }

    public function test_seeder_skips_a_missing_or_short_password(): void
    {
        config([
            'roshdino.admin.email' => 'masool@roshdino.test',
            'roshdino.admin.password' => 'short',
        ]);

        $this->seed(ResponsibleUserSeeder::class);

        $this->assertSame(0, User::query()->count());
    }
}
