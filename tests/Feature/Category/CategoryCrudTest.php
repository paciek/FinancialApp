<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_edit_own_category(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('categories.edit', $category))
            ->put(route('categories.update', $category), [
                'name' => 'Zakupy',
                'type' => 'expense',
            ]);

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategoria została zaktualizowana.');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);
    }

    public function test_user_cannot_edit_other_users_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $category = Category::create([
            'user_id' => $owner->id,
            'name' => 'Czynsz',
            'type' => 'expense',
        ]);

        $this
            ->actingAs($intruder)
            ->get(route('categories.edit', $category))
            ->assertForbidden();

        $this
            ->actingAs($intruder)
            ->put(route('categories.update', $category), [
                'name' => 'Proba',
                'type' => 'income',
            ])
            ->assertForbidden();
    }

    public function test_user_can_delete_own_category(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('categories.destroy', $category));

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategoria została usunięta.');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $category = Category::create([
            'user_id' => $owner->id,
            'name' => 'Subskrypcje',
            'type' => 'expense',
        ]);

        $this
            ->actingAs($intruder)
            ->delete(route('categories.destroy', $category))
            ->assertForbidden();
    }

    public function test_update_validation_works(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Przychod',
            'type' => 'income',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('categories.edit', $category))
            ->put(route('categories.update', $category), [
                'name' => '',
                'type' => 'other',
            ]);

        $response
            ->assertRedirect(route('categories.edit', $category))
            ->assertSessionHasErrors(['name', 'type']);
    }

    public function test_unique_name_per_user_on_update(): void
    {
        $user = User::factory()->create();

        $first = Category::create([
            'user_id' => $user->id,
            'name' => 'Rachunki',
            'type' => 'expense',
        ]);

        $second = Category::create([
            'user_id' => $user->id,
            'name' => 'Rozrywka',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('categories.edit', $second))
            ->put(route('categories.update', $second), [
                'name' => $first->name,
                'type' => 'expense',
            ]);

        $response
            ->assertRedirect(route('categories.edit', $second))
            ->assertSessionHasErrors(['name']);
    }
}