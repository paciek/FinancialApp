<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TransactionIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_own_transactions_only(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 120.50,
            'type' => 'expense',
            'description' => 'Wlasna transakcja',
            'transaction_date' => '2026-03-01',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 5000.00,
            'type' => 'income',
            'description' => 'Cudza transakcja',
            'transaction_date' => '2026-03-02',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertSee('Wlasna transakcja');
        $response->assertDontSee('Cudza transakcja');
    }

    public function test_guest_cannot_access_transactions(): void
    {
        $this->get(route('transactions.index'))->assertRedirect(route('login'));
    }

    public function test_filter_by_type_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Kategoria',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100.00,
            'type' => 'income',
            'description' => 'Income item',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 200.00,
            'type' => 'expense',
            'description' => 'Expense item',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['type' => 'income']));

        $response->assertStatus(200);
        $response->assertSee('Income item');
        $response->assertDontSee('Expense item');
    }

    public function test_pagination_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        $startDate = Carbon::create(2026, 3, 1);

        for ($i = 1; $i <= 16; $i++) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => 10.00 + $i,
                'type' => 'expense',
                'description' => sprintf('Transakcja %02d', $i),
                'transaction_date' => $startDate->copy()->addDays($i)->toDateString(),
            ]);
        }

        $firstPage = $this
            ->actingAs($user)
            ->get(route('transactions.index'));

        $firstPage->assertStatus(200);
        $firstPage->assertSee('Transakcja 16');
        $firstPage->assertDontSee('Transakcja 01');

        $secondPage = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['page' => 2]));

        $secondPage->assertStatus(200);
        $secondPage->assertSee('Transakcja 01');
    }
}
