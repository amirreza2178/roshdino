<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_the_password_page(): void
    {
        $this->get(route('password.edit'))->assertRedirect(route('login'));
    }

    public function test_responsible_user_replaces_the_password_and_stays_signed_in(): void
    {
        $user = User::factory()->create([
            'password' => 'current-secret-1',
        ]);

        $this->actingAs($user)
            ->put(route('password.update'), [
                'current_password' => 'current-secret-1',
                'password' => 'next-secret-123',
                'password_confirmation' => 'next-secret-123',
            ])
            ->assertRedirect(route('password.edit'))
            ->assertSessionHas('password_saved', true);

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('next-secret-123', $user->fresh()->password));

        $this->post(route('logout'));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'current-secret-1',
        ])->assertSessionHasErrors('email');

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'next-secret-123',
        ])->assertRedirect(route('leads.index'));
    }

    public function test_a_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create([
            'password' => 'current-secret-1',
        ]);

        $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => 'wrong-secret-1',
                'password' => 'next-secret-123',
                'password_confirmation' => 'next-secret-123',
            ])
            ->assertRedirect(route('password.edit'))
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('current-secret-1', $user->fresh()->password));
    }

    public function test_a_short_password_is_rejected(): void
    {
        $user = User::factory()->create([
            'password' => 'current-secret-1',
        ]);

        $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => 'current-secret-1',
                'password' => 'short-pass',
                'password_confirmation' => 'short-pass',
            ])
            ->assertRedirect(route('password.edit'))
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('current-secret-1', $user->fresh()->password));
    }
}
