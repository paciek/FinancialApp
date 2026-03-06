<?php

namespace Tests\Feature\Budget;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_store_budget_for_current_month(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('budget.store'), [
                'limit_amount' => '1200.00',
            ]);

        $response->assertRedirect(route('budget.index'));

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'month' => Carbon::now()->format('Y-m'),
            'limit_amount' => '1200.00',
        ]);
    }

    public function test_authenticated_user_can_update_budget_for_current_month(): void
    {
        $user = User::factory()->create();

        Budget::query()->create([
            'user_id' => $user->id,
            'month' => Carbon::now()->format('Y-m'),
            'limit_amount' => 1000,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('budget.update'), [
                'limit_amount' => '1500.50',
            ]);

        $response->assertRedirect(route('budget.index'));

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'month' => Carbon::now()->format('Y-m'),
            'limit_amount' => '1500.50',
        ]);
    }

    public function test_budget_page_displays_spending_progress_for_current_month(): void
    {
        $user = User::factory()->create();
        $today = Carbon::today()->toDateString();

        Budget::query()->create([
            'user_id' => $user->id,
            'month' => Carbon::now()->format('Y-m'),
            'limit_amount' => 1200,
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 700,
            'transaction_date' => $today,
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 150,
            'transaction_date' => $today,
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 9999,
            'transaction_date' => $today,
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 300,
            'transaction_date' => Carbon::now()->subMonth()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('budget.index'));

        $response
            ->assertOk()
            ->assertSee('850.00 / 1 200.00 PLN', false)
            ->assertSee('width: 70.83%;', false);
    }

    public function test_budget_link_is_visible_in_authenticated_navbar_and_route_is_protected(): void
    {
        $this->get(route('budget.index'))->assertRedirect(route('login'));

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.password.edit'))
            ->assertOk()
            ->assertSee('href="' . route('budget.index') . '"', false);
    }
}
