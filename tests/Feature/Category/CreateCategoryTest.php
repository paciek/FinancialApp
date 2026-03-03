<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'Wynagrodzenie',
            'type' => 'income',
        ]);

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategoria została utworzona.');

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Wynagrodzenie',
            'type' => 'income',
        ]);
    }

    public function test_guest_has_no_access(): void
    {
        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->get(route('categories.create'))->assertRedirect(route('login'));

        $this->post(route('categories.store'), [
            'name' => 'Transport',
            'type' => 'expense',
        ])->assertRedirect(route('login'));
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => '',
                'type' => 'income',
            ]);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_type_must_be_income_or_expense(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => 'Test',
                'type' => 'other',
            ]);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['type']);
    }

    public function test_name_must_be_unique_for_the_same_user(): void
    {
        $user = User::factory()->create();

        Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $response = $this->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => 'Jedzenie',
                'type' => 'expense',
            ]);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_two_different_users_can_have_the_same_category_name(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        Category::create([
            'user_id' => $firstUser->id,
            'name' => 'Paliwo',
            'type' => 'expense',
        ]);

        $response = $this->actingAs($secondUser)->post(route('categories.store'), [
            'name' => 'Paliwo',
            'type' => 'expense',
        ]);

        $response->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'user_id' => $secondUser->id,
            'name' => 'Paliwo',
            'type' => 'expense',
        ]);
    }
}
