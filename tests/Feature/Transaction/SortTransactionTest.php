<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SortTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_sort_is_by_date_desc(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10.00,
            'type' => 'expense',
            'description' => 'Oldest',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 20.00,
            'type' => 'expense',
            'description' => 'Middle',
            'transaction_date' => '2026-03-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 30.00,
            'type' => 'expense',
            'description' => 'Newest',
            'transaction_date' => '2026-03-10',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Newest', 'Middle', 'Oldest']);
    }

    public function test_sort_by_date_asc_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10.00,
            'type' => 'expense',
            'description' => 'Oldest',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 30.00,
            'type' => 'expense',
            'description' => 'Newest',
            'transaction_date' => '2026-03-10',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'sort' => 'transaction_date',
                'direction' => 'asc',
            ]));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Oldest', 'Newest']);
    }

    public function test_sort_by_amount_asc_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 200.00,
            'type' => 'expense',
            'description' => 'High',
            'transaction_date' => '2026-03-02',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10.00,
            'type' => 'expense',
            'description' => 'Low',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'Mid',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'sort' => 'amount',
                'direction' => 'asc',
            ]));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Low', 'Mid', 'High']);
    }

    public function test_sort_by_amount_desc_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10.00,
            'type' => 'expense',
            'description' => 'Low',
            'transaction_date' => '2026-03-02',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 200.00,
            'type' => 'expense',
            'description' => 'High',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'Mid',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'sort' => 'amount',
                'direction' => 'desc',
            ]));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['High', 'Mid', 'Low']);
    }

    public function test_invalid_sort_column_returns_validation_error(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.index'))
            ->get(route('transactions.index', [
                'sort' => 'invalid_column',
                'direction' => 'asc',
            ]));

        $response->assertRedirect(route('transactions.index'));
        $response->assertSessionHasErrors(['sort']);
    }

    public function test_sorting_works_with_filters(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 2000.00,
            'type' => 'income',
            'description' => 'Income High',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 500.00,
            'type' => 'income',
            'description' => 'Income Low',
            'transaction_date' => '2026-03-02',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 150.00,
            'type' => 'expense',
            'description' => 'Expense Item',
            'transaction_date' => '2026-03-03',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'type' => 'income',
                'sort' => 'amount',
                'direction' => 'asc',
            ]));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Income Low', 'Income High']);
        $response->assertDontSee('Expense Item');
    }

    public function test_pagination_keeps_sort_parameters(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        $startDate = Carbon::create(2026, 3, 1);

        for ($i = 1; $i <= 20; $i++) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => 100 + $i,
                'type' => 'expense',
                'description' => sprintf('Item %02d', $i),
                'transaction_date' => $startDate->copy()->addDays($i)->toDateString(),
            ]);
        }

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', [
                'sort' => 'amount',
                'direction' => 'asc',
            ]));

        $response->assertStatus(200);
        $response->assertSee('sort=amount');
        $response->assertSee('direction=asc');
    }
}
