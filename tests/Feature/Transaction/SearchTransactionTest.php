<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_transaction_by_description(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 120.00,
            'type' => 'expense',
            'description' => 'Zakupy spozywcze',
            'transaction_date' => '2026-03-10',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 80.00,
            'type' => 'expense',
            'description' => 'Rachunki',
            'transaction_date' => '2026-03-11',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['search' => 'Zakupy']));

        $response->assertStatus(200);
        $response->assertSee('Zakupy spozywcze');
        $response->assertDontSee('Rachunki');
    }

    public function test_search_returns_correct_results(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Rozne',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 500.00,
            'type' => 'income',
            'description' => 'Premia kwartalna',
            'transaction_date' => '2026-03-12',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 300.00,
            'type' => 'income',
            'description' => 'Premia roczna',
            'transaction_date' => '2026-03-13',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['search' => 'roczna']));

        $response->assertStatus(200);
        $response->assertSee('Premia roczna');
        $response->assertDontSee('Premia kwartalna');
    }

    public function test_search_is_limited_to_authenticated_user_data(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Zakupy',
            'type' => 'expense',
        ]);
        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Cudza',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'Zakupy domowe',
            'transaction_date' => '2026-03-09',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 999.00,
            'type' => 'expense',
            'description' => 'Zakupy cudze',
            'transaction_date' => '2026-03-09',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['search' => 'Zakupy']));

        $response->assertStatus(200);
        $response->assertSee('Zakupy domowe');
        $response->assertDontSee('Zakupy cudze');
    }

    public function test_no_results_shows_empty_message(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Inne',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10.00,
            'type' => 'expense',
            'description' => 'Bilet',
            'transaction_date' => '2026-03-08',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('transactions.index', ['search' => 'Nieistniejace']));

        $response->assertStatus(200);
        $response->assertSee('Brak transakcji spelniajacych kryteria.');
    }

    public function test_endpoint_requires_authentication(): void
    {
        $this
            ->get(route('transactions.index', ['search' => 'Zakupy']))
            ->assertRedirect(route('login'));
    }
}
