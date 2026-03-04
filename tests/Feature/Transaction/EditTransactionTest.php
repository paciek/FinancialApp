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

    public function test_user_can_edit_own_transaction(): void
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
            'amount' => '500.00',
            'type' => 'income',
            'description' => 'Start',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)
            ->get(route('transactions.edit', $transaction))
            ->assertOk()
            ->assertSee('Edycja transakcji');
    }

    public function test_data_is_updated_in_database(): void
    {
        $user = User::factory()->create();
        $oldCategory = Category::create(['user_id' => $user->id, 'name' => 'Stara', 'type' => 'expense']);
        $newCategory = Category::create(['user_id' => $user->id, 'name' => 'Nowa', 'type' => 'expense']);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $oldCategory->id,
            'amount' => '50.00',
            'type' => 'expense',
            'description' => 'Przed',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)->put(route('transactions.update', $transaction), [
            'transaction_date' => '2026-03-05',
            'amount' => '70.00',
            'type' => 'expense',
            'category_id' => $newCategory->id,
            'description' => 'Po',
        ])->assertRedirect(route('transactions.index'));

        $transaction->refresh();
        $this->assertSame('2026-03-05', $transaction->transaction_date->format('Y-m-d'));
        $this->assertSame('70.00', $transaction->amount);
        $this->assertSame($newCategory->id, $transaction->category_id);
        $this->assertSame('Po', $transaction->description);
    }

    public function test_cannot_change_to_foreign_category(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $ownCategory = Category::create(['user_id' => $user->id, 'name' => 'Moja', 'type' => 'income']);
        $foreignCategory = Category::create(['user_id' => $otherUser->id, 'name' => 'Obca', 'type' => 'income']);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $ownCategory->id,
            'amount' => '50.00',
            'type' => 'income',
            'description' => 'Tx',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)
            ->from(route('transactions.edit', $transaction))
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => '2026-03-01',
                'amount' => '55.00',
                'type' => 'income',
                'category_id' => $foreignCategory->id,
                'description' => 'Test',
            ])
            ->assertRedirect(route('transactions.edit', $transaction))
            ->assertSessionHasErrors(['category_id']);
    }

    public function test_cannot_edit_foreign_transaction(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::create(['user_id' => $otherUser->id, 'name' => 'Obca', 'type' => 'expense']);

        $transaction = Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'amount' => '10.00',
            'type' => 'expense',
            'description' => 'Obca transakcja',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)
            ->get(route('transactions.edit', $transaction))
            ->assertForbidden();
    }

    public function test_cannot_edit_soft_deleted_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Moja', 'type' => 'expense']);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '10.00',
            'type' => 'expense',
            'description' => 'Do usuniecia',
            'transaction_date' => '2026-03-01',
        ]);

        $transaction->delete();

        $this->actingAs($user)
            ->get(route('transactions.edit', $transaction->id))
            ->assertNotFound();
    }

    public function test_validation_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Moja', 'type' => 'income']);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '10.00',
            'type' => 'income',
            'description' => 'Tx',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)
            ->from(route('transactions.edit', $transaction))
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => '',
                'amount' => '',
                'type' => 'income',
                'category_id' => $category->id,
                'description' => str_repeat('a', 1001),
            ])
            ->assertRedirect(route('transactions.edit', $transaction))
            ->assertSessionHasErrors(['transaction_date', 'amount', 'description']);
    }

    public function test_amount_cannot_be_negative(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['user_id' => $user->id, 'name' => 'Moja', 'type' => 'expense']);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '15.00',
            'type' => 'expense',
            'description' => 'Tx',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)
            ->from(route('transactions.edit', $transaction))
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => '2026-03-01',
                'amount' => '-1',
                'type' => 'expense',
                'category_id' => $category->id,
                'description' => 'Test',
            ])
            ->assertRedirect(route('transactions.edit', $transaction))
            ->assertSessionHasErrors(['amount']);
    }
}

