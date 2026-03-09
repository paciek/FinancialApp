<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_name_email_and_contact_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nowa Nazwa',
            'email' => 'new@example.com',
            'address' => 'Warszawa, ul. Testowa 1',
            'contact_phone' => '+48 123 456 789',
        ]);

        $response
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('success', 'Profil zostal zaktualizowany.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nowa Nazwa',
            'email' => 'new@example.com',
            'address' => 'Warszawa, ul. Testowa 1',
            'contact_phone' => '+48 123 456 789',
        ]);
    }

    public function test_profile_email_must_be_unique(): void
    {
        $user = User::factory()->create(['email' => 'user1@example.com']);
        User::factory()->create(['email' => 'user2@example.com']);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.index'))
            ->put(route('profile.update'), [
                'name' => 'User One',
                'email' => 'user2@example.com',
                'address' => '',
                'contact_phone' => '',
            ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_guest_cannot_update_profile(): void
    {
        $this->put(route('profile.update'), [
            'name' => 'X',
            'email' => 'x@example.com',
        ])->assertRedirect(route('login'));
    }

    public function test_profile_name_format_is_validated(): void
    {
        $user = User::factory()->create(['email' => 'user1@example.com']);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.index'))
            ->put(route('profile.update'), [
                'name' => 'Jan123',
                'email' => 'user1@example.com',
                'address' => 'Warszawa 1',
                'contact_phone' => '+48 111 222 333',
            ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_profile_phone_format_is_validated(): void
    {
        $user = User::factory()->create(['email' => 'user1@example.com']);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.index'))
            ->put(route('profile.update'), [
                'name' => 'Jan Kowalski',
                'email' => 'user1@example.com',
                'address' => 'Warszawa 1',
                'contact_phone' => 'abc-xyz',
            ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHasErrors(['contact_phone']);
    }

    public function test_profile_address_format_is_validated(): void
    {
        $user = User::factory()->create(['email' => 'user1@example.com']);

        $response = $this
            ->actingAs($user)
            ->from(route('profile.index'))
            ->put(route('profile.update'), [
                'name' => 'Jan Kowalski',
                'email' => 'user1@example.com',
                'address' => 'Warszawa @@@',
                'contact_phone' => '+48 111 222 333',
            ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHasErrors(['address']);
    }
}
