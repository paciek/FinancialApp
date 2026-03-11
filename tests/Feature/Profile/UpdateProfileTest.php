<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Jan Kowalski',
            'email' => 'jan@example.com',
            'default_currency' => 'PLN',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Adam Nowak',
                'email' => 'adam@example.com',
                'default_currency' => 'EUR',
            ]);

        $response
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success', 'Profil został zaktualizowany.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Adam Nowak',
            'email' => 'adam@example.com',
            'default_currency' => 'EUR',
        ]);
    }

    public function test_guest_cannot_access_profile_edit(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));

        $this->put(route('profile.update'), [
            'name' => 'User',
            'email' => 'user@example.com',
            'default_currency' => 'PLN',
        ])->assertRedirect(route('login'));
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $user = User::factory()->create([
            'email' => 'current@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Nowe Imie',
                'email' => 'existing@example.com',
                'default_currency' => 'USD',
            ]);

        $response
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['email']);
    }

    public function test_default_currency_must_be_from_list(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'default_currency' => 'JPY',
            ]);

        $response
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['default_currency']);
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => '',
                'email' => 'valid@example.com',
                'default_currency' => 'GBP',
            ]);

        $response
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['name']);
    }
}