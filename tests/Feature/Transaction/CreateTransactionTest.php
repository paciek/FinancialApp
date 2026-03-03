<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        $response = $this->actingAs($user)->post(route('transactions.store'), [
            'transaction_date' => '2026-03-03',
            'amount' => '1450.00',
            'type' => 'income',
            'category_id' => $category->id,
            'description' => 'Wyplata miesieczna',
        ]);

        $response
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success', 'Transakcja została dodana.');

        $transaction = Transaction::query()->first();
        $this->assertNotNull($transaction);
        $this->assertSame($user->id, $transaction->user_id);
        $this->assertSame($category->id, $transaction->category_id);
        $this->assertSame('income', $transaction->type);
        $this->assertSame('Wyplata miesieczna', $transaction->description);
        $this->assertSame('2026-03-03', $transaction->transaction_date->format('Y-m-d'));
    }

    public function test_guest_has_no_access(): void
    {
        $this->get(route('transactions.create'))->assertRedirect(route('login'));
        $this->post(route('transactions.store'), [])->assertRedirect(route('login'));
    }

    public function test_category_id_must_belong_to_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $foreignCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Obca',
            'type' => 'expense',
        ]);

        $response = $this->actingAs($user)->from(route('transactions.create'))->post(route('transactions.store'), [
            'transaction_date' => '2026-03-03',
            'amount' => '10.00',
            'type' => 'expense',
            'category_id' => $foreignCategory->id,
            'description' => 'Test',
        ]);

        $response
            ->assertRedirect(route('transactions.create'))
            ->assertSessionHasErrors(['category_id']);
    }

    public function test_amount_must_be_greater_than_zero(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        $response = $this->actingAs($user)->from(route('transactions.create'))->post(route('transactions.store'), [
            'transaction_date' => '2026-03-03',
            'amount' => '0',
            'type' => 'expense',
            'category_id' => $category->id,
            'description' => 'Test',
        ]);

        $response
            ->assertRedirect(route('transactions.create'))
            ->assertSessionHasErrors(['amount']);
    }

    public function test_type_must_be_income_or_expense(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Dodatkowe',
            'type' => 'income',
        ]);

        $response = $this->actingAs($user)->from(route('transactions.create'))->post(route('transactions.store'), [
            'transaction_date' => '2026-03-03',
            'amount' => '250.00',
            'type' => 'other',
            'category_id' => $category->id,
            'description' => 'Test',
        ]);

        $response
            ->assertRedirect(route('transactions.create'))
            ->assertSessionHasErrors(['type']);
    }

    public function test_description_optional_works(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Inne',
            'type' => 'income',
        ]);

        $response = $this->actingAs($user)->post(route('transactions.store'), [
            'transaction_date' => '2026-03-03',
            'amount' => '100.00',
            'type' => 'income',
            'category_id' => $category->id,
            'description' => '',
        ]);

        $response->assertRedirect(route('transactions.index'));

        $transaction = Transaction::query()->first();
        $this->assertNotNull($transaction);
        $this->assertNull($transaction->description);
    }
}

