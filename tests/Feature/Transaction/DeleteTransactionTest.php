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
            'name' => 'Dom',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '120.00',
            'type' => 'expense',
            'description' => 'Czynsz',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this->actingAs($user)->delete(route('transactions.destroy', $transaction));

        $response
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success', 'Transakcja została usunięta.');
    }

    public function test_transaction_is_soft_deleted(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Dom',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '120.00',
            'type' => 'expense',
            'description' => 'Czynsz',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)->delete(route('transactions.destroy', $transaction));

        $this->assertSoftDeleted('transactions', ['id' => $transaction->id]);
    }

    public function test_soft_deleted_transaction_is_not_visible_on_index(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Dom',
            'type' => 'expense',
        ]);

        $hidden = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '120.00',
            'type' => 'expense',
            'description' => 'Ukryta transakcja',
            'transaction_date' => '2026-03-01',
        ]);

        $visible = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '50.00',
            'type' => 'expense',
            'description' => 'Widoczna transakcja',
            'transaction_date' => '2026-03-02',
        ]);

        $hidden->delete();

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk()
            ->assertSee($visible->description)
            ->assertDontSee($hidden->description);
    }

    public function test_user_cannot_delete_foreign_transaction(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::create([
            'user_id' => $other->id,
            'name' => 'Obca',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $other->id,
            'category_id' => $category->id,
            'amount' => '33.00',
            'type' => 'expense',
            'description' => 'Obca transakcja',
            'transaction_date' => '2026-03-01',
        ]);

        $this->actingAs($user)
            ->delete(route('transactions.destroy', $transaction))
            ->assertForbidden();
    }

    public function test_guest_cannot_delete_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Dom',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => '120.00',
            'type' => 'expense',
            'description' => 'Czynsz',
            'transaction_date' => '2026-03-01',
        ]);

        $this->delete(route('transactions.destroy', $transaction))
            ->assertRedirect(route('login'));
    }
}

