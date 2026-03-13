<?php

namespace Tests\Feature\Budget;

use App\Models\Category;
use App\Models\MonthlyBudget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_alert_shows_when_budget_is_exceeded(): void
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
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 250.00,
            'type' => 'expense',
            'description' => 'Zakupy',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(1)->toDateString(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response
            ->assertStatus(200)
            ->assertSee('Przekroczyles budzet miesieczny.')
            ->assertSee('200.00')
            ->assertSee('250.00');
    }

    public function test_alert_not_shown_when_budget_is_not_exceeded(): void
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
            'name' => 'Transport',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 100.00,
            'type' => 'expense',
            'description' => 'Bilet',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(2)->toDateString(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response
            ->assertStatus(200)
            ->assertDontSee('Przekroczyles budzet miesieczny.');
    }

    public function test_alert_shows_correct_budget_data(): void
    {
        $user = User::factory()->create();
        $now = now();

        MonthlyBudget::create([
            'user_id' => $user->id,
            'month' => $now->month,
            'year' => $now->year,
            'limit_amount' => 150.00,
        ]);

        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zdrowie',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 170.00,
            'type' => 'expense',
            'description' => 'Apteka',
            'transaction_date' => $now->copy()->startOfMonth()->addDays(3)->toDateString(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response
            ->assertStatus(200)
            ->assertSee('Limit: 150.00')
            ->assertSee('Wydano: 170.00');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard.index'))->assertRedirect(route('login'));
    }
}
