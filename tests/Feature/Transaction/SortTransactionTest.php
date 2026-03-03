<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_sorting_is_transaction_date_desc(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Main', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '10.00',
            'type' => 'expense',
            'description' => 'Older',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '20.00',
            'type' => 'expense',
            'description' => 'Newer',
            'transaction_date' => '2026-02-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index'));
        $response->assertOk();

        $transactions = $response->viewData('transactions');
        $this->assertSame('Newer', $transactions->first()->description);
    }

    public function test_sort_by_date_asc_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Main', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '10.00',
            'type' => 'expense',
            'description' => 'Older',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '20.00',
            'type' => 'expense',
            'description' => 'Newer',
            'transaction_date' => '2026-02-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'sort' => 'transaction_date',
            'direction' => 'asc',
        ]));

        $transactions = $response->viewData('transactions');
        $this->assertSame('Older', $transactions->first()->description);
    }

    public function test_sort_by_amount_asc_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Main', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '100.00',
            'type' => 'expense',
            'description' => 'High',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '5.00',
            'type' => 'expense',
            'description' => 'Low',
            'transaction_date' => '2026-02-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'sort' => 'amount',
            'direction' => 'asc',
        ]));

        $transactions = $response->viewData('transactions');
        $this->assertSame('Low', $transactions->first()->description);
    }

    public function test_sort_by_amount_desc_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Main', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '100.00',
            'type' => 'expense',
            'description' => 'High',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '5.00',
            'type' => 'expense',
            'description' => 'Low',
            'transaction_date' => '2026-02-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'sort' => 'amount',
            'direction' => 'desc',
        ]));

        $transactions = $response->viewData('transactions');
        $this->assertSame('High', $transactions->first()->description);
    }

    public function test_invalid_sort_column_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('transactions.index'))
            ->get(route('transactions.index', ['sort' => 'hack']));

        $response->assertSessionHasErrors(['sort']);
    }

    public function test_sorting_works_with_filters(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create(['user_id' => $user->id, 'name' => 'Income', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $user->id, 'name' => 'Expense', 'type' => 'expense']);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => '50.00',
            'type' => 'income',
            'description' => 'Income one',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => '10.00',
            'type' => 'expense',
            'description' => 'Expense one',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => '100.00',
            'type' => 'expense',
            'description' => 'Expense two',
            'transaction_date' => '2026-01-02',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'type' => 'expense',
            'sort' => 'amount',
            'direction' => 'asc',
        ]));

        $response->assertOk()->assertDontSee('Income one');
        $transactions = $response->viewData('transactions');
        $this->assertSame('Expense one', $transactions->first()->description);
    }

    public function test_pagination_keeps_sort_query(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Main', 'type' => 'expense']);

        for ($i = 1; $i <= 16; $i++) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => (string) ($i + 0.5),
                'type' => 'expense',
                'description' => "Tx {$i}",
                'transaction_date' => '2026-01-01',
            ]);
        }

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'sort' => 'amount',
            'direction' => 'asc',
            'page' => 2,
        ]));

        $response
            ->assertOk()
            ->assertSee('sort=amount')
            ->assertSee('direction=asc');
    }
}

