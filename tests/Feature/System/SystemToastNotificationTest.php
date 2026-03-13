<?php

namespace Tests\Feature\System;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemToastNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_toast_shows_after_creating_transaction(): void
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
            ])
            ->followRedirects();

        $response
            ->assertStatus(200)
            ->assertSee('toast-body', false)
            ->assertSee('fa-circle-check', false);
    }

    public function test_toast_shows_after_editing_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Transport',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 40.00,
            'type' => 'expense',
            'description' => 'Bilet',
            'transaction_date' => '2026-03-06',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('transactions.update', $transaction), [
                'transaction_date' => '2026-03-06',
                'amount' => 55.00,
                'type' => 'expense',
                'category_id' => $category->id,
                'description' => 'Bilet miejski',
            ])
            ->followRedirects();

        $response
            ->assertStatus(200)
            ->assertSee('toast-body', false)
            ->assertSee('fa-circle-check', false);
    }

    public function test_toast_shows_after_deleting_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Rozrywka',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 18.00,
            'type' => 'expense',
            'description' => 'Kino',
            'transaction_date' => '2026-03-07',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('transactions.destroy', $transaction))
            ->followRedirects();

        $response
            ->assertStatus(200)
            ->assertSee('toast-body', false)
            ->assertSee('fa-circle-check', false);
    }

    public function test_toast_disappears_after_refresh(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zdrowie',
            'type' => 'expense',
        ]);

        $this
            ->actingAs($user)
            ->post(route('transactions.store'), [
                'transaction_date' => '2026-03-08',
                'amount' => 12.00,
                'type' => 'expense',
                'category_id' => $category->id,
                'description' => 'Apteka',
            ])
            ->followRedirects()
            ->assertSee('toast-body', false);

        $this
            ->actingAs($user)
            ->get(route('transactions.index'))
            ->assertStatus(200)
            ->assertDontSee('toast align-items-center text-bg-success', false);
    }

    public function test_transaction_endpoints_require_authentication(): void
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

        $this->get(route('transactions.create'))->assertRedirect(route('login'));
        $this->post(route('transactions.store'), [
            'transaction_date' => '2026-03-05',
            'amount' => 25.50,
            'type' => 'expense',
            'category_id' => $category->id,
        ])->assertRedirect(route('login'));
        $this->get(route('transactions.edit', $transaction))->assertRedirect(route('login'));
        $this->put(route('transactions.update', $transaction), [
            'transaction_date' => '2026-03-05',
            'amount' => 30.00,
            'type' => 'expense',
            'category_id' => $category->id,
        ])->assertRedirect(route('login'));
        $this->delete(route('transactions.destroy', $transaction))->assertRedirect(route('login'));
    }
}
