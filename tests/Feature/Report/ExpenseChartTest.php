<?php

namespace Tests\Feature\Report;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_endpoint_returns_chart_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
            'color' => '#dc3545',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 120.00,
            'type' => 'expense',
            'description' => 'A',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('expensesChart');
        $response->assertSee($category->name);
        $response->assertSee($category->color);
    }

    public function test_only_expense_transactions_are_counted(): void
    {
        $user = User::factory()->create();
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
            'color' => '#198754',
        ]);
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
            'color' => '#0d6efd',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 200.00,
            'type' => 'expense',
            'description' => 'B',
            'transaction_date' => '2026-03-02',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 900.00,
            'type' => 'income',
            'description' => 'C',
            'transaction_date' => '2026-03-03',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee($expenseCategory->name);
        $response->assertDontSee($incomeCategory->name);
    }

    public function test_data_is_grouped_by_category(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
            'color' => '#ffc107',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 80.00,
            'type' => 'expense',
            'description' => 'D',
            'transaction_date' => '2026-03-04',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 70.00,
            'type' => 'expense',
            'description' => 'E',
            'transaction_date' => '2026-03-05',
        ]);

        $sum = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->sum('amount');

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee(json_encode([$category->name]), false);
        $response->assertSee(json_encode([$sum]), false);
    }

    public function test_each_category_has_color_in_chart_data(): void
    {
        $user = User::factory()->create();
        $categoryA = Category::create([
            'user_id' => $user->id,
            'name' => 'Dom',
            'type' => 'expense',
            'color' => '#6f42c1',
        ]);
        $categoryB = Category::create([
            'user_id' => $user->id,
            'name' => 'Zdrowie',
            'type' => 'expense',
            'color' => '#0d6efd',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $categoryA->id,
            'amount' => 40.00,
            'type' => 'expense',
            'description' => 'F',
            'transaction_date' => '2026-03-06',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $categoryB->id,
            'amount' => 60.00,
            'type' => 'expense',
            'description' => 'G',
            'transaction_date' => '2026-03-07',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee($categoryA->color);
        $response->assertSee($categoryB->color);
    }

    public function test_chart_data_includes_only_authenticated_user_transactions(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'UserCat',
            'type' => 'expense',
            'color' => '#198754',
        ]);
        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'OtherCat',
            'type' => 'expense',
            'color' => '#dc3545',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $userCategory->id,
            'amount' => 55.00,
            'type' => 'expense',
            'description' => 'H',
            'transaction_date' => '2026-03-08',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 999.00,
            'type' => 'expense',
            'description' => 'I',
            'transaction_date' => '2026-03-09',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee($userCategory->name);
        $response->assertDontSee($otherCategory->name);
    }

    public function test_summary_endpoint_requires_authentication(): void
    {
        $this
            ->get(route('reports.summary'))
            ->assertRedirect(route('login'));
    }
}
