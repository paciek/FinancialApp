<?php

namespace Tests\Feature\Report;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_income_sum(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 1000.00,
            'type' => 'income',
            'description' => 'A',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 500.00,
            'type' => 'income',
            'description' => 'B',
            'transaction_date' => '2026-03-02',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('1500');
    }

    public function test_user_sees_expenses_sum(): void
    {
        $user = User::factory()->create();
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 300.00,
            'type' => 'expense',
            'description' => 'C',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 120.00,
            'type' => 'expense',
            'description' => 'D',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('420');
    }

    public function test_balance_is_calculated_correctly(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Rachunki',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 2000.00,
            'type' => 'income',
            'description' => 'E',
            'transaction_date' => '2026-03-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 750.00,
            'type' => 'expense',
            'description' => 'F',
            'transaction_date' => '2026-03-06',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('1250');
    }

    public function test_summary_includes_only_authenticated_user_data(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);
        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Cudza',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 1000.00,
            'type' => 'income',
            'description' => 'G',
            'transaction_date' => '2026-03-07',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 9000.00,
            'type' => 'income',
            'description' => 'H',
            'transaction_date' => '2026-03-08',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.summary'));

        $response->assertStatus(200);
        $response->assertSee('1000');
        $response->assertDontSee('9000');
    }

    public function test_summary_requires_authentication(): void
    {
        $this
            ->get(route('reports.summary'))
            ->assertRedirect(route('login'));
    }
}
