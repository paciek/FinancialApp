<?php

namespace Tests\Feature\Report;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_endpoint_returns_balance_chart_data(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
            'color' => '#0d6efd',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 1000.00,
            'type' => 'income',
            'description' => 'A',
            'transaction_date' => '2026-01-15',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('balanceChart');
        $response->assertSee('2026-01-15');
    }

    public function test_monthly_balance_is_calculated_correctly(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
            'color' => '#0d6efd',
        ]);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
            'color' => '#dc3545',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 2000.00,
            'type' => 'income',
            'description' => 'B',
            'transaction_date' => '2026-02-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 500.00,
            'type' => 'expense',
            'description' => 'C',
            'transaction_date' => '2026-02-10',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee(json_encode(['2026-02-05', '2026-02-10']), false);
        $response->assertSee(json_encode([2000, 1500]), false);
    }

    public function test_data_is_grouped_by_months(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
            'color' => '#0d6efd',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 800.00,
            'type' => 'income',
            'description' => 'D',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 900.00,
            'type' => 'income',
            'description' => 'E',
            'transaction_date' => '2026-02-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('2026-01-01');
        $response->assertSee('2026-02-01');
    }

    public function test_data_includes_only_authenticated_user_transactions(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'User',
            'type' => 'income',
            'color' => '#198754',
        ]);
        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Other',
            'type' => 'income',
            'color' => '#dc3545',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $userCategory->id,
            'amount' => 700.00,
            'type' => 'income',
            'description' => 'F',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 900.00,
            'type' => 'income',
            'description' => 'G',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('700');
        $response->assertDontSee('900');
    }

    public function test_summary_endpoint_requires_authentication(): void
    {
        $this
            ->get(route('reports.summary'))
            ->assertRedirect(route('login'));
    }
}
