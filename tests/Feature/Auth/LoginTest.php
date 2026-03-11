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
            'password' => Hash::make('Haslo123!'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'Haslo123!',
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHas('success', 'Zalogowano pomyślnie.');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('Haslo123!'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'BledneHaslo123!',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'Nieprawidłowy email lub hasło.',
            ]);

        $this->assertGuest();
    }

    public function test_login_fails_for_non_existing_email(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'missing@example.com',
            'password' => 'Haslo123!',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'Nieprawidłowy email lub hasło.',
            ]);

        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response
            ->assertRedirect('/')
            ->assertSessionHas('success', 'Wylogowano.');

        $this->assertGuest();
    }
}
