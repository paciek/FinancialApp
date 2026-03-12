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
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Kategoria',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100.00,
            'type' => 'income',
            'description' => 'Income item',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'Expense item',
            'transaction_date' => '2026-03-02',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['type' => 'income']));

        $response->assertStatus(200);
        $response->assertSee('Income item');
        $response->assertDontSee('Expense item');
    }

    public function test_filter_by_date_range_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 20.00,
            'type' => 'expense',
            'description' => 'Before range',
            'transaction_date' => '2026-02-28',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 40.00,
            'type' => 'expense',
            'description' => 'Inside range',
            'transaction_date' => '2026-03-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 80.00,
            'type' => 'expense',
            'description' => 'After range',
            'transaction_date' => '2026-03-20',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'date_from' => '2026-03-01',
                'date_to' => '2026-03-10',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Inside range');
        $response->assertDontSee('Before range');
        $response->assertDontSee('After range');
    }

    public function test_filter_by_category_works(): void
    {
        $user = User::factory()->create();
        $foodCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);
        $travelCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Podroze',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $foodCategory->id,
            'amount' => 30.00,
            'type' => 'expense',
            'description' => 'Food item',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $travelCategory->id,
            'amount' => 300.00,
            'type' => 'expense',
            'description' => 'Travel item',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['category_id' => $foodCategory->id]));

        $response->assertStatus(200);
        $response->assertSee('Food item');
        $response->assertDontSee('Travel item');
    }

    public function test_combined_filters_work(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Auto',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 4500.00,
            'type' => 'income',
            'description' => 'Salary March',
            'transaction_date' => '2026-03-02',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 300.00,
            'type' => 'expense',
            'description' => 'Car service',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 1200.00,
            'type' => 'income',
            'description' => 'Bonus April',
            'transaction_date' => '2026-04-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'type' => 'income',
                'category_id' => $incomeCategory->id,
                'date_from' => '2026-03-01',
                'date_to' => '2026-03-31',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Salary March');
        $response->assertDontSee('Car service');
        $response->assertDontSee('Bonus April');
    }

    public function test_user_cannot_filter_other_users_categories(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Cudza kategoria',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.index'))
            ->get(route('transactions.index', ['category_id' => $otherCategory->id]));

        $response->assertRedirect(route('transactions.index'));
        $response->assertSessionHasErrors(['category_id']);
    }

    public function test_date_to_before_date_from_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.index'))
            ->get(route('transactions.index', [
                'date_from' => '2026-03-10',
                'date_to' => '2026-03-01',
            ]));

        $response->assertRedirect(route('transactions.index'));
        $response->assertSessionHasErrors(['date_to']);
    }
}
