<?php

namespace Tests\Feature\Reports;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseByCategoryReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_see_report(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.expenses.byCategory'));

        $response->assertOk();
        $response->assertSee('Wydatki wg kategorii');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('reports.expenses.byCategory'))->assertRedirect(route('login'));
    }

    public function test_expenses_are_aggregated_by_category(): void
    {
        $user = User::factory()->create();
        $food = Category::create(['user_id' => $user->id, 'name' => 'Jedzenie', 'type' => 'expense']);
        $transport = Category::create(['user_id' => $user->id, 'name' => 'Transport', 'type' => 'expense']);

        $this->createTransaction($user->id, $food->id, 100, 'expense', 'zakupy', '2026-03-01');
        $this->createTransaction($user->id, $food->id, 50, 'expense', 'lunch', '2026-03-02');
        $this->createTransaction($user->id, $transport->id, 40, 'expense', 'bus', '2026-03-03');

        $response = $this->actingAs($user)->get(route('reports.expenses.byCategory'));

        $response->assertOk();
        $response->assertViewHas('total', 190.0);
        $response->assertSee('Jedzenie');
        $response->assertSee('Transport');
    }

    public function test_soft_deleted_transactions_are_ignored(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Dom', 'type' => 'expense']);

        $active = $this->createTransaction($user->id, $category->id, 120, 'expense', 'aktywna', '2026-03-01');
        $deleted = $this->createTransaction($user->id, $category->id, 300, 'expense', 'usunieta', '2026-03-02');
        $deleted->delete();

        $response = $this->actingAs($user)->get(route('reports.expenses.byCategory'));

        $response->assertOk();
        $response->assertViewHas('total', 120.0);
        $this->assertNotNull($active->fresh());
    }

    public function test_date_range_filters_report_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Zakupy', 'type' => 'expense']);

        $this->createTransaction($user->id, $category->id, 100, 'expense', 'styczen', '2026-01-15');
        $this->createTransaction($user->id, $category->id, 200, 'expense', 'luty', '2026-02-15');

        $response = $this->actingAs($user)->get(route('reports.expenses.byCategory', [
            'date_from' => '2026-02-01',
            'date_to' => '2026-02-28',
        ]));

        $response->assertOk();
        $response->assertViewHas('total', 200.0);
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

