<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_sees_own_transactions(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '1500.50',
            'type' => 'income',
            'description' => 'Wyplata',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response
            ->assertOk()
            ->assertSee('Lista transakcji')
            ->assertSee('Pensja')
            ->assertSee('Wyplata');
    }

    public function test_user_does_not_see_transactions_of_other_users(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Obca kategoria',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => '50.00',
            'type' => 'expense',
            'description' => 'Obca transakcja',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response
            ->assertOk()
            ->assertDontSee('Obca transakcja')
            ->assertDontSee('Obca kategoria');
    }

    public function test_guest_has_no_access(): void
    {
        $response = $this->get(route('transactions.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_filter_by_type_works(): void
    {
        $user = User::factory()->create();
        $incomeCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Premia',
            'type' => 'income',
        ]);
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => '250.00',
            'type' => 'income',
            'description' => 'Premia kwartalna',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'amount' => '80.00',
            'type' => 'expense',
            'description' => 'Market',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', ['type' => 'income']));

        $response
            ->assertOk()
            ->assertSee('Premia kwartalna')
            ->assertDontSee('Market');
    }

    public function test_pagination_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Koszty stale',
            'type' => 'expense',
        ]);

        for ($i = 1; $i <= 16; $i++) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => '10.00',
                'type' => 'expense',
                'description' => "Transakcja {$i}",
                'transaction_date' => '2026-03-01',
            ]);
        }

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions) => $transactions->count() === 15 && $transactions->total() === 16);
    }
}

