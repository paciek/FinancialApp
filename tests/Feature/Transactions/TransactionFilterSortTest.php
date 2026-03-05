<?php

namespace Tests\Feature\Transactions;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionFilterSortTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_transactions(): void
    {
        $this->get(route('transactions.index'))->assertRedirect(route('login'));
    }

    public function test_transactions_can_be_filtered_by_date_range(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Zakupy', 'type' => 'expense']);

        $outside = $this->createTransaction($user->id, $category->id, 10, 'expense', '2026-01-31');
        $inRangeA = $this->createTransaction($user->id, $category->id, 20, 'expense', '2026-02-10');
        $inRangeB = $this->createTransaction($user->id, $category->id, 30, 'expense', '2026-02-20');

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'date_from' => '2026-02-01',
            'date_to' => '2026-02-28',
        ]));

        $response->assertOk();
        $response->assertViewHas('transactions', function ($transactions) use ($outside, $inRangeA, $inRangeB) {
            $ids = $transactions->pluck('id')->all();
            sort($ids);

            return $ids === [$inRangeA->id, $inRangeB->id] && !in_array($outside->id, $ids, true);
        });
    }

    public function test_transactions_can_be_filtered_by_type_and_category(): void
    {
        $user = User::factory()->create();
        $food = Category::create(['user_id' => $user->id, 'name' => 'Jedzenie', 'type' => 'expense']);
        $salary = Category::create(['user_id' => $user->id, 'name' => 'Pensja', 'type' => 'income']);

        $wanted = $this->createTransaction($user->id, $food->id, 100, 'expense', '2026-03-01');
        $this->createTransaction($user->id, $salary->id, 5000, 'income', '2026-03-02');
        $otherCategoryExpense = $this->createTransaction($user->id, $salary->id, 50, 'expense', '2026-03-03');

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'type' => 'expense',
            'category_id' => $food->id,
        ]));

        $response->assertOk();
        $response->assertViewHas('transactions', function ($transactions) use ($wanted, $otherCategoryExpense) {
            $ids = $transactions->pluck('id')->all();

            return $ids === [$wanted->id] && !in_array($otherCategoryExpense->id, $ids, true);
        });
    }

    public function test_transactions_can_be_sorted_by_date_and_amount(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Test', 'type' => 'expense']);

        $first = $this->createTransaction($user->id, $category->id, 300, 'expense', '2026-03-03');
        $second = $this->createTransaction($user->id, $category->id, 100, 'expense', '2026-03-01');
        $third = $this->createTransaction($user->id, $category->id, 200, 'expense', '2026-03-02');

        $dateAscResponse = $this->actingAs($user)->get(route('transactions.index', [
            'sort_by' => 'transaction_date',
            'sort_dir' => 'asc',
        ]));
        $dateAscResponse->assertOk();
        $dateAscResponse->assertViewHas('transactions', fn ($transactions) => $transactions->pluck('id')->all() === [
            $second->id,
            $third->id,
            $first->id,
        ]);

        $amountDescResponse = $this->actingAs($user)->get(route('transactions.index', [
            'sort_by' => 'amount',
            'sort_dir' => 'desc',
        ]));
        $amountDescResponse->assertOk();
        $amountDescResponse->assertViewHas('transactions', fn ($transactions) => $transactions->pluck('id')->all() === [
            $first->id,
            $third->id,
            $second->id,
        ]);
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
