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

    public function test_user_can_create_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('transactions.store'), [
                'transaction_date' => '2026-03-05',
                'amount' => 25.50,
                'type' => 'expense',
                'category_id' => $category->id,
                'description' => 'Kolacja',
            ]);

        $response
            ->assertRedirect(route('transactions.index'))
            ->assertSessionHas('success', 'Transakcja została dodana.');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 25.50,
            'type' => 'expense',
            'description' => 'Kolacja',
            'transaction_date' => '2026-03-05',
        ]);
    }

    public function test_guest_cannot_create_transaction(): void
    {
        $this->get(route('transactions.create'))->assertRedirect(route('login'));
        $this->post(route('transactions.store'), [
            'transaction_date' => '2026-03-05',
            'amount' => 25.50,
            'type' => 'expense',
            'category_id' => 1,
        ])->assertRedirect(route('login'));
    }

    public function test_category_must_belong_to_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.create'))
            ->post(route('transactions.store'), [
                'transaction_date' => '2026-03-05',
                'amount' => 100.00,
                'type' => 'income',
                'category_id' => $otherCategory->id,
            ]);

        $response
            ->assertRedirect(route('transactions.create'))
            ->assertSessionHasErrors(['category_id']);
    }

    public function test_amount_must_be_positive(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'income',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.create'))
            ->post(route('transactions.store'), [
                'transaction_date' => '2026-03-05',
                'amount' => 0,
                'type' => 'income',
                'category_id' => $category->id,
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
            'name' => 'Test',
            'type' => 'income',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('transactions.create'))
            ->post(route('transactions.store'), [
                'transaction_date' => '2026-03-05',
                'amount' => 100.00,
                'type' => 'other',
                'category_id' => $category->id,
            ]);

        $response
            ->assertRedirect(route('transactions.create'))
            ->assertSessionHasErrors(['type']);
    }

    public function test_description_is_optional(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('transactions.store'), [
                'transaction_date' => '2026-03-06',
                'amount' => 12.00,
                'type' => 'expense',
                'category_id' => $category->id,
            ]);

        $response->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 12.00,
            'type' => 'expense',
            'transaction_date' => '2026-03-06',
        ]);
    }
}