<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_total_income_correctly(): void
    {
        $user = User::factory()->create(['default_currency' => 'PLN']);
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        $this->createTransaction($user->id, $incomeCategory->id, 1000, 'income', 'pensja', '2026-03-01');
        $this->createTransaction($user->id, $incomeCategory->id, 500, 'income', 'premia', '2026-03-02');

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk();
        $this->assertSame(1500.0, (float) $response->viewData('totalIncome'));
    }

    public function test_it_calculates_total_expense_correctly(): void
    {
        $user = User::factory()->create(['default_currency' => 'PLN']);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $this->createTransaction($user->id, $expenseCategory->id, 300, 'expense', 'zakupy', '2026-03-01');
        $this->createTransaction($user->id, $expenseCategory->id, 200, 'expense', 'restauracja', '2026-03-02');

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk();
        $this->assertSame(500.0, (float) $response->viewData('totalExpense'));
    }

    public function test_it_calculates_balance_correctly(): void
    {
        $user = User::factory()->create(['default_currency' => 'PLN']);

        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Praca',
            'type' => 'income',
        ]);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Dom',
            'type' => 'expense',
        ]);

        $this->createTransaction($user->id, $incomeCategory->id, 1000, 'income', 'wynagrodzenie', '2026-03-01');
        $this->createTransaction($user->id, $expenseCategory->id, 250, 'expense', 'rachunki', '2026-03-02');

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk();
        $this->assertSame(750.0, (float) $response->viewData('balance'));
    }

    public function test_it_ignores_soft_deleted_transactions(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Praca',
            'type' => 'income',
        ]);

        $visible = $this->createTransaction($user->id, $incomeCategory->id, 500, 'income', 'widoczna', '2026-03-01');
        $deleted = $this->createTransaction($user->id, $incomeCategory->id, 700, 'income', 'ukryta', '2026-03-02');
        $deleted->delete();

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk();
        $response->assertSee('widoczna');
        $response->assertDontSee('ukryta');
        $this->assertSame(500.0, (float) $response->viewData('totalIncome'));
    }

    public function test_it_does_not_include_other_users_data(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userIncomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Moje',
            'type' => 'income',
        ]);
        $otherIncomeCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Cudze',
            'type' => 'income',
        ]);

        $this->createTransaction($user->id, $userIncomeCategory->id, 400, 'income', 'moj opis', '2026-03-01');
        $this->createTransaction($otherUser->id, $otherIncomeCategory->id, 1000, 'income', 'cudzy opis', '2026-03-01');

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk();
        $response->assertSee('moj opis');
        $response->assertDontSee('cudzy opis');
        $this->assertSame(400.0, (float) $response->viewData('totalIncome'));
    }

    public function test_summary_reacts_to_date_filter(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Praca',
            'type' => 'income',
        ]);

        $this->createTransaction($user->id, $incomeCategory->id, 300, 'income', 'styczen', '2026-01-01');
        $this->createTransaction($user->id, $incomeCategory->id, 700, 'income', 'luty', '2026-02-01');

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'date_from' => '2026-02-01',
            'date_to' => '2026-02-28',
        ]));

        $response->assertOk();
        $this->assertSame(700.0, (float) $response->viewData('totalIncome'));
    }

    public function test_summary_reacts_to_search(): void
    {
        $user = User::factory()->create();
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        $this->createTransaction($user->id, $expenseCategory->id, 120, 'expense', 'zakupy spozywcze', '2026-03-01');
        $this->createTransaction($user->id, $expenseCategory->id, 180, 'expense', 'rachunek telefon', '2026-03-01');

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => 'zakupy']));

        $response->assertOk();
        $this->assertSame(120.0, (float) $response->viewData('totalExpense'));
    }

    private function createTransaction(
        int $userId,
        int $categoryId,
        float $amount,
        string $type,
        string $description,
        string $date
    ): Transaction {
        return Transaction::create([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'transaction_date' => $date,
        ]);
    }
}

