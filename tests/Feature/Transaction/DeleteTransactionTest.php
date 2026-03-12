<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 120.00,
            'type' => 'expense',
            'description' => 'Do usuniecia',
            'transaction_date' => '2026-03-10',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('transactions.destroy', $transaction));

        $response->assertRedirect(route('transactions.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);
    }

    public function test_deleted_transaction_is_not_visible_on_index(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 80.00,
            'type' => 'expense',
            'description' => 'Ukryta',
            'transaction_date' => '2026-03-11',
        ]);

        $transaction->delete();

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Ukryta');
    }

    public function test_user_cannot_delete_other_users_transaction(): void
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
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'Cudza',
            'transaction_date' => '2026-03-05',
        ]);

        $this
            ->actingAs($user)
            ->delete(route('transactions.destroy', $transaction))
            ->assertStatus(403);
    }

    public function test_delete_requires_authentication(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10.00,
            'type' => 'expense',
            'description' => 'Public',
            'transaction_date' => '2026-03-01',
        ]);

        $this
            ->delete(route('transactions.destroy', $transaction))
            ->assertRedirect(route('login'));
    }
}
