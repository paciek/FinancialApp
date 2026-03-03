<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Zalogowano pomyślnie.');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'WrongPass123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'Nieprawidłowy email lub hasło.']);
        $this->assertGuest();
    }

    public function test_login_fails_for_non_existing_email(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'missing@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'Nieprawidłowy email lub hasło.']);
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_logout_works_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Wylogowano.');
        $this->assertGuest();
    }
}
