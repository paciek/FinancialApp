<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register.store'), [
            'login' => 'jan_kowalski',
            'email' => 'jan@example.com',
            'email_confirmation' => 'jan@example.com',
            'password' => 'Haslo123!',
            'password_confirmation' => 'Haslo123!',
            'terms' => '1',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', 'Konto zostało utworzone. Zaloguj się.');

        $this->assertDatabaseHas('users', [
            'login' => 'jan_kowalski',
            'email' => 'jan@example.com',
        ]);

        $this->assertGuest();
    }

    public function test_login_must_be_unique(): void
    {
        User::factory()->create([
            'login' => 'istniejacy_login',
            'name' => 'Istniejacy',
            'email' => 'old@example.com',
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'login' => 'istniejacy_login',
            'email' => 'new@example.com',
            'email_confirmation' => 'new@example.com',
            'password' => 'Haslo123!',
            'password_confirmation' => 'Haslo123!',
            'terms' => '1',
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['login']);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create([
            'login' => 'old_login',
            'name' => 'Old User',
            'email' => 'exists@example.com',
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'login' => 'new_login',
            'email' => 'exists@example.com',
            'email_confirmation' => 'exists@example.com',
            'password' => 'Haslo123!',
            'password_confirmation' => 'Haslo123!',
            'terms' => '1',
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['email']);
    }

    public function test_terms_are_required(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'login' => 'new_login',
            'email' => 'new@example.com',
            'email_confirmation' => 'new@example.com',
            'password' => 'Haslo123!',
            'password_confirmation' => 'Haslo123!',
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['terms']);
    }

    public function test_password_must_be_confirmed(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'login' => 'new_login',
            'email' => 'new@example.com',
            'email_confirmation' => 'new@example.com',
            'password' => 'Haslo123!',
            'password_confirmation' => 'inneHaslo123!',
            'terms' => '1',
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['password']);
    }

    public function test_email_confirmation_must_match_email(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'login' => 'new_login',
            'email' => 'new@example.com',
            'email_confirmation' => 'diff@example.com',
            'password' => 'Haslo123!',
            'password_confirmation' => 'Haslo123!',
            'terms' => '1',
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['email_confirmation']);
    }
}

