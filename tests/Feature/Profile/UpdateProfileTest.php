<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Jan',
            'email' => 'jan@example.com',
            'default_currency' => 'PLN',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@example.com',
            'default_currency' => 'EUR',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Profil został zaktualizowany.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@example.com',
            'default_currency' => 'EUR',
        ]);
    }

    public function test_guest_has_no_access_to_profile_edit(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));

        $this->put(route('profile.update'), [
            'name' => 'Jan',
            'email' => 'jan@example.com',
            'default_currency' => 'PLN',
        ])->assertRedirect(route('login'));
    }

    public function test_email_must_be_unique_except_current_user(): void
    {
        $user = User::factory()->create(['email' => 'current@example.com']);
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Current User',
                'email' => 'taken@example.com',
                'default_currency' => 'PLN',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_currency_must_be_from_allowed_list(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Jan',
                'email' => 'jan@example.com',
                'default_currency' => 'JPY',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors(['default_currency']);
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => '',
                'email' => 'jan@example.com',
                'default_currency' => 'PLN',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors(['name']);
    }
}
