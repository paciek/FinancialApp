<?php

namespace Tests\Feature\Reports;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceOverTimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_report(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.balance.overTime'));

        $response->assertOk();
        $response->assertSee('Saldo w czasie');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('reports.balance.overTime'))->assertRedirect(route('login'));
    }

    public function test_report_aggregates_data_by_month(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create(['user_id' => $user->id, 'name' => 'Pensja', 'type' => 'income']);
        $expenseCategory = Category::create(['user_id' => $user->id, 'name' => 'Rachunki', 'type' => 'expense']);

        $this->createTransaction($user->id, $incomeCategory->id, 1000, 'income', '2026-01-10');
        $this->createTransaction($user->id, $expenseCategory->id, 200, 'expense', '2026-01-20');
        $this->createTransaction($user->id, $incomeCategory->id, 500, 'income', '2026-02-05');
        $this->createTransaction($user->id, $expenseCategory->id, 100, 'expense', '2026-02-06');

        $response = $this->actingAs($user)->get(route('reports.balance.overTime'));

        $response->assertOk();
        $response->assertViewHas('labels', fn ($labels) => $labels->all() === ['2026-01', '2026-02']);
        $response->assertViewHas('values', fn ($values) => $values->all() === [800.0, 400.0]);
    }

    public function test_other_users_data_is_not_visible(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userIncomeCategory = Category::create(['user_id' => $user->id, 'name' => 'Premia', 'type' => 'income']);
        $otherIncomeCategory = Category::create(['user_id' => $otherUser->id, 'name' => 'Pensja inna', 'type' => 'income']);

        $this->createTransaction($user->id, $userIncomeCategory->id, 300, 'income', '2026-03-01');
        $this->createTransaction($otherUser->id, $otherIncomeCategory->id, 999, 'income', '2026-03-02');

        $response = $this->actingAs($user)->get(route('reports.balance.overTime'));

        $response->assertOk();
        $response->assertViewHas('labels', fn ($labels) => $labels->all() === ['2026-03']);
        $response->assertViewHas('values', fn ($values) => $values->all() === [300.0]);
    }

    private function createTransaction(
        int $userId,
        int $categoryId,
        float $amount,
        string $type,
        string $date
    ): Transaction {
        return Transaction::create([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'amount' => $amount,
            'type' => $type,
            'transaction_date' => $date,
        ]);
    }
}
