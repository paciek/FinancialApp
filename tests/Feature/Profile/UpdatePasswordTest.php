<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPass123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.password.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'OldPass123!',
                'password' => 'NewPass123!',
                'password_confirmation' => 'NewPass123!',
            ]);

        $response
            ->assertRedirect(route('profile.password.edit'))
            ->assertSessionHas('success', 'Hasło zostało zmienione.');

        $this->assertTrue(Hash::check('NewPass123!', $user->fresh()->password));
    }

    public function test_invalid_current_password_blocks_password_change(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPass123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.password.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'WrongPass123!',
                'password' => 'NewPass123!',
                'password_confirmation' => 'NewPass123!',
            ]);

        $response->assertRedirect(route('profile.password.edit'));
        $response->assertSessionHasErrors(['current_password']);
        $this->assertTrue(Hash::check('OldPass123!', $user->fresh()->password));
    }

    public function test_password_must_be_confirmed(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPass123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.password.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'OldPass123!',
                'password' => 'NewPass123!',
                'password_confirmation' => 'Mismatch123!',
            ]);

        $response->assertRedirect(route('profile.password.edit'));
        $response->assertSessionHasErrors(['password']);
    }

    public function test_new_password_must_be_different_than_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPass123!'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.password.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'OldPass123!',
                'password' => 'OldPass123!',
                'password_confirmation' => 'OldPass123!',
            ]);

        $response->assertRedirect(route('profile.password.edit'));
        $response->assertSessionHasErrors(['password']);
    }

    public function test_guest_has_no_access_to_password_change_screen(): void
    {
        $this->get(route('profile.password.edit'))->assertRedirect(route('login'));

        $this->put(route('profile.password.update'), [
            'current_password' => 'OldPass123!',
            'password' => 'NewPass123!',
            'password_confirmation' => 'NewPass123!',
        ])->assertRedirect(route('login'));
    }
}
