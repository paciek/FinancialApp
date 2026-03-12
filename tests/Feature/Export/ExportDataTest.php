<?php

namespace Tests\Feature\Export;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_download_csv(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
            'color' => '#6c757d',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 50.00,
            'type' => 'expense',
            'description' => 'A',
            'transaction_date' => '2026-03-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('export.csv'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="transactions.csv"');
    }

    public function test_user_can_download_json(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'type' => 'expense',
            'color' => '#6c757d',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 60.00,
            'type' => 'expense',
            'description' => 'B',
            'transaction_date' => '2026-03-02',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('export.json'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="transactions.json"');
    }

    public function test_export_contains_only_user_data(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'UserCat',
            'type' => 'expense',
            'color' => '#6c757d',
        ]);
        $otherCategory = Category::create([
            'user_id' => $otherUser->id,
            'name' => 'OtherCat',
            'type' => 'expense',
            'color' => '#6c757d',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 70.00,
            'type' => 'expense',
            'description' => 'User item',
            'transaction_date' => '2026-03-03',
        ]);

        Transaction::create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 99.00,
            'type' => 'expense',
            'description' => 'Other item',
            'transaction_date' => '2026-03-04',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('export.json'));

        $response->assertStatus(200);
        $response->assertSee('User item');
        $response->assertDontSee('Other item');
    }

    public function test_export_requires_authentication(): void
    {
        $this
            ->get(route('export.csv'))
            ->assertRedirect(route('login'));
    }

    public function test_csv_has_correct_columns(): void
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get(route('export.csv'));

        $response->assertStatus(200);
        $response->assertSee('Date,Amount,Type,Category,Description');
    }
}
