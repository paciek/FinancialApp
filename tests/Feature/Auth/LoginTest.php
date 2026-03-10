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
            'email' => 'jan@example.com',
            'password' => Hash::make('Haslo123!'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'jan@example.com',
            'password' => 'Haslo123!',
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHas('success', 'Zalogowano pomyślnie.');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'jan@example.com',
            'password' => Hash::make('Haslo123!'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'jan@example.com',
            'password' => 'ZleHaslo123!',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'Nieprawidłowy email lub hasło.',
            ]);

        $this->assertGuest();
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'brak@example.com',
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
        $response = $this->from(route('login'))->post(route('login'), []);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response
            ->assertRedirect('/')
            ->assertSessionHas('success', 'Wylogowano.');

        $this->assertGuest();
    }
}