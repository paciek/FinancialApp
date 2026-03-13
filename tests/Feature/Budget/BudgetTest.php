<?php

namespace Tests\Feature\Budget;

use App\Models\Category;
use App\Models\MonthlyBudget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_set_monthly_budget(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('budget.store'), [
                'limit_amount' => 500,
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Budzet zapisany.');
    }

    public function test_budget_is_saved_in_database(): void
    {
        $user = User::factory()->create();
        $now = now();

        $this
            ->actingAs($user)
            ->post(route('budget.store'), [
                'limit_amount' => 750.00,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('monthly_budgets', [
            'user_id' => $user->id,
            'month' => $now->month,
            'year' => $now->year,
            'limit_amount' => 750.00,
        ]);
    }

    public function test_expenses_are_summed_for_current_month(): void
    {
        $user = User::factory()->create();
        $now = now();

        MonthlyBudget::create([
            'user_id' => $user->id,
            'month' => $now->month,
            'year' => $now->year,
            'limit_amount' => 300.00,
        ]);

        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 100.00,
            'type' => 'expense',
            'description' => 'Zakupy',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(1)->toDateString(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'Obiad',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(2)->toDateString(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 1000.00,
            'type' => 'income',
            'description' => 'Wyplata',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(3)->toDateString(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('budget.index'));

        $response
            ->assertStatus(200)
            ->assertSee('150.00');
    }

    public function test_progress_bar_shows_correct_percentage(): void
    {
        $user = User::factory()->create();
        $now = now();

        MonthlyBudget::create([
            'user_id' => $user->id,
            'month' => $now->month,
            'year' => $now->year,
            'limit_amount' => 200.00,
        ]);

        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 100.00,
            'type' => 'expense',
            'description' => 'Bilet',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(4)->toDateString(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('budget.index'));

        $response
            ->assertStatus(200)
            ->assertSee('width: 50%')
            ->assertSee('50%');
    }

    public function test_budget_endpoints_require_authentication(): void
    {
        $this->get(route('budget.index'))->assertRedirect(route('login'));
        $this->post(route('budget.store'), [
            'limit_amount' => 100,
        ])->assertRedirect(route('login'));
    }
}
