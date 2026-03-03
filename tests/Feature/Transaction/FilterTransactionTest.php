<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_by_type_works(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create(['user_id' => $user->id, 'name' => 'Pensja', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $user->id, 'name' => 'Zakupy', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => '1000.00',
            'type' => 'income',
            'description' => 'Wplyw',
            'transaction_date' => '2026-01-10',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => '50.00',
            'type' => 'expense',
            'description' => 'Wydatek',
            'transaction_date' => '2026-01-11',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', ['type' => 'expense']));

        $response->assertOk()->assertSee('Wydatek')->assertDontSee('Wplyw');
    }

    public function test_filter_by_date_range_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Inne', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '20.00',
            'type' => 'expense',
            'description' => 'Styczen',
            'transaction_date' => '2026-01-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '30.00',
            'type' => 'expense',
            'description' => 'Luty',
            'transaction_date' => '2026-02-05',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'date_from' => '2026-02-01',
            'date_to' => '2026-02-28',
        ]));

        $response->assertOk()->assertSee('Luty')->assertDontSee('Styczen');
    }

    public function test_filter_by_category_works(): void
    {
        $user = User::factory()->create();
        $categoryA = Category::create(['user_id' => $user->id, 'name' => 'A', 'type' => 'expense']);
        $categoryB = Category::create(['user_id' => $user->id, 'name' => 'B', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $categoryA->id,
            'amount' => '20.00',
            'type' => 'expense',
            'description' => 'Kategoria A',
            'transaction_date' => '2026-02-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $categoryB->id,
            'amount' => '30.00',
            'type' => 'expense',
            'description' => 'Kategoria B',
            'transaction_date' => '2026-02-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'category_id' => $categoryB->id,
        ]));

        $response->assertOk()->assertSee('Kategoria B')->assertDontSee('Kategoria A');
    }

    public function test_combined_filters_work(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Rachunki', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '120.00',
            'type' => 'expense',
            'description' => 'Pasuje',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '120.00',
            'type' => 'income',
            'description' => 'Nie pasuje typ',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'type' => 'expense',
            'date_from' => '2026-03-01',
            'date_to' => '2026-03-31',
            'category_id' => $category->id,
        ]));

        $response->assertOk()->assertSee('Pasuje')->assertDontSee('Nie pasuje typ');
    }

    public function test_user_cannot_filter_with_foreign_category(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $foreignCategory = Category::create(['user_id' => $otherUser->id, 'name' => 'Obca', 'type' => 'expense']);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'category_id' => $foreignCategory->id,
        ]));

        $response->assertSessionHasErrors(['category_id']);
    }

    public function test_date_to_before_date_from_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'date_from' => '2026-03-10',
            'date_to' => '2026-03-01',
        ]));

        $response->assertSessionHasErrors(['date_to']);
    }
}

