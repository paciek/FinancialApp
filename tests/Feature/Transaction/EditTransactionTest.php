<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_open_edit_form(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100.00,
            'type' => 'expense',
            'description' => 'Test item',
            'transaction_date' => '2026-03-05',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.edit', $transaction));

        $response->assertStatus(200);
    }

    public function test_edit_form_is_prefilled_with_transaction_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 2500.50,
            'type' => 'income',
            'description' => 'Opis testowy',
            'transaction_date' => '2026-03-10',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.edit', $transaction));

        $response->assertStatus(200);
        $response->assertSee('value="2026-03-10"', false);
        $response->assertSee('value="' . $transaction->amount . '"', false);
        $response->assertSee('value="income" checked', false);
        $response->assertSee('value="' . $category->id . '" selected', false);
        $response->assertSee('Opis testowy');
    }

    public function test_user_can_update_transaction(): void
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

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'amount' => 1500.00,
            'type' => 'income',
            'description' => 'Stary opis',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => '2026-03-12',
                'amount' => 120.25,
                'type' => 'expense',
                'category_id' => $expenseCategory->id,
                'description' => 'Nowy opis',
            ]);

        $response->assertRedirect(route('transactions.index'));
        $response->assertSessionHas('success');

        $transaction->refresh();
        $this->assertSame('2026-03-12', $transaction->transaction_date?->format('Y-m-d'));
        $this->assertSame(120.25, (float) $transaction->amount);
        $this->assertSame('expense', $transaction->type);
        $this->assertSame($expenseCategory->id, $transaction->category_id);
        $this->assertSame('Nowy opis', $transaction->description);
    }

    public function test_user_cannot_edit_other_users_transaction(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Cudza kategoria',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 80.00,
            'type' => 'expense',
            'description' => 'Cudza transakcja',
            'transaction_date' => '2026-03-02',
        ]);

        $this
            ->actingAs($user)
            ->get(route('transactions.edit', $transaction))
            ->assertStatus(403);

        $this
            ->actingAs($user)
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => '2026-03-03',
                'amount' => 10,
                'type' => 'expense',
                'category_id' => $otherCategory->id,
                'description' => 'Proba',
            ])
            ->assertStatus(403);
    }

    public function test_validation_errors_are_returned_on_update(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 30.00,
            'type' => 'expense',
            'description' => 'Test',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.edit', $transaction))
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => 'invalid-date',
                'amount' => -10,
                'type' => 'invalid',
                'category_id' => '9999',
                'description' => str_repeat('a', 600),
            ]);

        $response->assertRedirect(route('transactions.edit', $transaction));
        $response->assertSessionHasErrors([
            'transaction_date',
            'amount',
            'type',
            'category_id',
            'description',
        ]);
    }
}
