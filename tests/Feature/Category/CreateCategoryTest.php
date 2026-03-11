<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('categories.store'), [
                'name' => 'Transport',
                'type' => 'expense',
            ]);

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategoria została utworzona.');

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
        ]);
    }

    public function test_guest_cannot_access_category_pages(): void
    {
        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->get(route('categories.create'))->assertRedirect(route('login'));
        $this->post(route('categories.store'), [
            'name' => 'Jedzenie',
            'type' => 'expense',
        ])->assertRedirect(route('login'));
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => '',
                'type' => 'income',
            ]);

        $response
            ->assertRedirect(route('categories.create'))
            ->assertSessionHasErrors(['name']);
    }

    public function test_type_must_be_income_or_expense(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => 'Pensja',
                'type' => 'other',
            ]);

        $response
            ->assertRedirect(route('categories.create'))
            ->assertSessionHasErrors(['type']);
    }

    public function test_name_must_be_unique_per_user(): void
    {
        $user = User::factory()->create();

        Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => 'Zakupy',
                'type' => 'expense',
            ]);

        $response
            ->assertRedirect(route('categories.create'))
            ->assertSessionHasErrors(['name']);
    }

    public function test_different_users_can_share_same_category_name(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        Category::create([
            'user_id' => $firstUser->id,
            'name' => 'Oszczednosci',
            'type' => 'income',
        ]);

        $response = $this
            ->actingAs($secondUser)
            ->post(route('categories.store'), [
                'name' => 'Oszczednosci',
                'type' => 'income',
            ]);

        $response->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'user_id' => $secondUser->id,
            'name' => 'Oszczednosci',
            'type' => 'income',
        ]);
    }
}