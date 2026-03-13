<?php

namespace Tests\Feature\Dashboard;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee('Ostatnie transakcje');
    }

    public function test_financial_summary_is_correct(): void
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
            'amount' => 2500.00,
            'type' => 'income',
            'description' => 'A',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => 600.00,
            'type' => 'expense',
            'description' => 'B',
            'transaction_date' => '2026-03-02',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee('2500');
        $response->assertSee('600');
        $response->assertSee('1900');
    }

    public function test_recent_transactions_are_visible(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        $amounts = [10, 20, 30, 40, 50, 60];

        $days = count($amounts) - 1;

        foreach ($amounts as $index => $amount) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => $amount,
                'type' => 'expense',
                'description' => "T{$amount}",
                'transaction_date' => now()->subDays($days - $index)->format('Y-m-d'),
            ]);
        }

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee('60.00');
        $response->assertSee('50.00');
        $response->assertSee('40.00');
        $response->assertSee('30.00');
        $response->assertSee('20.00');
        $response->assertDontSee('10.00');
    }

    public function test_dashboard_includes_only_authenticated_user_data(): void
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
            'amount' => 1200.00,
            'type' => 'income',
            'description' => 'C',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 9000.00,
            'type' => 'income',
            'description' => 'D',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee('1200');
        $response->assertDontSee('9000');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this
            ->get(route('dashboard.index'))
            ->assertRedirect(route('login'));
    }
}
