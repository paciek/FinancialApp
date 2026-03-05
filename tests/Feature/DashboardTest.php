<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_dashboard_counts_income_and_expense_correctly(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create(['user_id' => $user->id, 'name' => 'Pensja', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $user->id, 'name' => 'Rachunki', 'type' => 'expense']);

        $this->createTransaction($user->id, $incomeCategory->id, 1200, 'income', '2026-03-01');
        $this->createTransaction($user->id, $incomeCategory->id, 300, 'income', '2026-03-02');
        $this->createTransaction($user->id, $expenseCategory->id, 400, 'expense', '2026-03-03');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalIncome', 1500.0);
        $response->assertViewHas('totalExpense', 400.0);
    }

    public function test_balance_is_calculated_correctly(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create(['user_id' => $user->id, 'name' => 'Pensja', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $user->id, 'name' => 'Zakupy', 'type' => 'expense']);

        $this->createTransaction($user->id, $incomeCategory->id, 1000, 'income', '2026-03-01');
        $this->createTransaction($user->id, $expenseCategory->id, 250, 'expense', '2026-03-04');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('balance', 750.0);
    }

    public function test_latest_five_transactions_are_displayed(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Dom', 'type' => 'expense']);
        $otherCategory = Category::create(['user_id' => $other->id, 'name' => 'Inne', 'type' => 'expense']);

        for ($i = 1; $i <= 6; $i++) {
            $this->createTransaction($user->id, $category->id, 10 * $i, 'expense', "2026-03-0{$i}", "uzytkownik {$i}");
        }

        $this->createTransaction($other->id, $otherCategory->id, 999, 'expense', '2026-03-10', 'obcy');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('latestTransactions', function ($transactions) {
            return $transactions->count() === 5
                && $transactions->first()->description === 'uzytkownik 6'
                && $transactions->last()->description === 'uzytkownik 2';
        });
    }

    private function createTransaction(
        int $userId,
        ?int $categoryId,
        float $amount,
        string $type,
        string $date,
        ?string $description = null
    ): Transaction {
        return Transaction::create([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'amount' => $amount,
            'type' => $type,
            'transaction_date' => $date,
            'description' => $description,
        ]);
    }
}
