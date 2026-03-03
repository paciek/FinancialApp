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

        $response = $this->actingAs($user)
            ->from(route('categories.edit', $category))
            ->put(route('categories.update', $category), [
                'name' => 'Zakupy spożywcze',
                'type' => 'expense',
            ]);

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategoria została zaktualizowana.');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Zakupy spożywcze',
            'type' => 'expense',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_edit_foreign_category(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();

        $category = Category::create([
            'user_id' => $owner->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        $this->actingAs($attacker)
            ->get(route('categories.edit', $category))
            ->assertForbidden();

        $this->actingAs($attacker)
            ->put(route('categories.update', $category), [
                'name' => 'Zmiana',
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

        $response = $this->actingAs($user)->delete(route('categories.destroy', $category));

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategoria została usunięta.');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_foreign_category(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();

        $category = Category::create([
            'user_id' => $owner->id,
            'name' => 'Czynsz',
            'type' => 'expense',
        ]);

        $this->actingAs($attacker)
            ->delete(route('categories.destroy', $category))
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_update_validation_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Stara',
            'type' => 'income',
        ]);

        $response = $this->actingAs($user)
            ->from(route('categories.edit', $category))
            ->put(route('categories.update', $category), [
                'name' => '',
                'type' => 'wrong',
            ]);

        $response->assertRedirect(route('categories.edit', $category));
        $response->assertSessionHasErrors(['name', 'type']);
    }

    public function test_unique_name_per_user_works_on_update(): void
    {
        $user = User::factory()->create();

        $first = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $second = Category::create([
            'user_id' => $user->id,
            'name' => 'Rachunki',
            'type' => 'expense',
        ]);

        $response = $this->actingAs($user)
            ->from(route('categories.edit', $second))
            ->put(route('categories.update', $second), [
                'name' => $first->name,
                'type' => 'expense',
            ]);

        $response->assertRedirect(route('categories.edit', $second));
        $response->assertSessionHasErrors(['name']);
    }
}
