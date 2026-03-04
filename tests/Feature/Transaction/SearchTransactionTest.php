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

    public function test_search_returns_matching_results_by_description(): void
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
            'amount' => 100,
            'type' => 'expense',
            'description' => 'duze zakupy spozywcze',
            'transaction_date' => '2026-01-10',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 50,
            'type' => 'expense',
            'description' => 'rachunek za internet',
            'transaction_date' => '2026-01-11',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => 'zakupy']));

        $response->assertOk();
        $response->assertSee('duze zakupy spozywcze');
        $response->assertDontSee('rachunek za internet');
    }

    public function test_search_is_case_insensitive(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 70,
            'type' => 'expense',
            'description' => 'Zakupy Biedronka',
            'transaction_date' => '2026-01-12',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => 'zakupy']));

        $response->assertOk();
        $response->assertSee('Zakupy Biedronka');
    }

    public function test_search_does_not_return_other_users_transactions(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Moja',
            'type' => 'expense',
        ]);

        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'Cudza',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $userCategory->id,
            'amount' => 20,
            'type' => 'expense',
            'description' => 'zakupy domowe',
            'transaction_date' => '2026-01-13',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 30,
            'type' => 'expense',
            'description' => 'zakupy tajne',
            'transaction_date' => '2026-01-13',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => 'zakupy']));

        $response->assertOk();
        $response->assertSee('zakupy domowe');
        $response->assertDontSee('zakupy tajne');
    }

    public function test_search_does_not_return_soft_deleted_transactions(): void
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
            'amount' => 120,
            'type' => 'expense',
            'description' => 'zakupy meble',
            'transaction_date' => '2026-01-14',
        ]);

        $transaction->delete();

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => 'zakupy']));

        $response->assertOk();
        $response->assertDontSee('zakupy meble');
    }

    public function test_search_works_with_filters(): void
    {
        $user = User::factory()->create();

        $foodCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Jedzenie',
            'type' => 'expense',
        ]);

        $salaryCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pensja',
            'type' => 'income',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $foodCategory->id,
            'amount' => 80,
            'type' => 'expense',
            'description' => 'zakupy styczen',
            'transaction_date' => '2026-01-05',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $salaryCategory->id,
            'amount' => 5000,
            'type' => 'income',
            'description' => 'zakupy bonus',
            'transaction_date' => '2026-01-06',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'q' => 'zakupy',
            'type' => 'expense',
            'category_id' => $foodCategory->id,
        ]));

        $response->assertOk();
        $response->assertSee('zakupy styczen');
        $response->assertDontSee('zakupy bonus');
    }

    public function test_search_works_with_sorting(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 999,
            'type' => 'expense',
            'description' => 'zakupy duze',
            'transaction_date' => '2026-01-03',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 10,
            'type' => 'expense',
            'description' => 'zakupy male',
            'transaction_date' => '2026-01-02',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', [
            'q' => 'zakupy',
            'sort' => 'amount',
            'direction' => 'asc',
        ]));

        $response->assertOk();

        $content = $response->getContent();
        $this->assertIsString($content);
        $this->assertLessThan(
            strpos($content, 'zakupy duze'),
            strpos($content, 'zakupy male')
        );
    }

    public function test_pagination_preserves_query_param_q(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Paginated',
            'type' => 'expense',
        ]);

        foreach (range(1, 20) as $index) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => $index,
                'type' => 'expense',
                'description' => "zakupy {$index}",
                'transaction_date' => '2026-01-01',
            ]);
        }

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => 'zakupy']));

        $response->assertOk();
        $response->assertSee('q=zakupy', false);
        $response->assertSee('page=2', false);
    }

    public function test_empty_q_does_not_break_the_list(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Rachunki',
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 199,
            'type' => 'expense',
            'description' => 'oplata za prad',
            'transaction_date' => '2026-01-08',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.index', ['q' => '']));

        $response->assertOk();
        $response->assertSee('oplata za prad');
    }

    public function test_user_cannot_filter_with_other_users_category(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $otherCategory = Category::create([
            'user_id' => $other->id,
            'name' => 'Cudza',
            'type' => 'expense',
        ]);

        $response = $this->actingAs($user)
            ->from(route('transactions.index'))
            ->get(route('transactions.index', ['category_id' => $otherCategory->id]));

        $response->assertRedirect(route('transactions.index'));
        $response->assertSessionHasErrors(['category_id']);
    }
}
