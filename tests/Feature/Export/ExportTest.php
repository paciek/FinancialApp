<?php

namespace Tests\Feature\Export;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_export_downloads_file(): void
    {
        $user = User::factory()->create();
        $category = Category::query()->create([
            'user_id' => $user->id,
            'name' => 'Food',
            'type' => 'expense',
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100,
            'type' => 'expense',
            'description' => 'Lunch',
            'transaction_date' => '2026-01-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.export.csv'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $response->assertHeader('content-disposition', 'attachment; filename=transactions_export.csv');

        $content = $response->streamedContent();

        $this->assertStringContainsString('ID,Data,Kwota,Typ,Kategoria,Opis', $content);
        $this->assertStringContainsString('Food', $content);
        $this->assertStringContainsString('Lunch', $content);
    }

    public function test_json_export_downloads_file(): void
    {
        $user = User::factory()->create();
        $category = Category::query()->create([
            'user_id' => $user->id,
            'name' => 'Food',
            'type' => 'expense',
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100,
            'type' => 'expense',
            'description' => 'Lunch',
            'transaction_date' => '2026-01-01',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.export.json'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json; charset=UTF-8');
        $response->assertHeader('content-disposition', 'attachment; filename=transactions_export.json');

        $rows = json_decode($response->streamedContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsArray($rows);
        $this->assertCount(1, $rows);
        $this->assertSame('Food', $rows[0]['category']);
    }

    public function test_export_contains_only_current_user_data(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::query()->create([
            'user_id' => $user->id,
            'name' => 'Food',
            'type' => 'expense',
        ]);

        $otherCategory = Category::query()->create([
            'user_id' => $otherUser->id,
            'name' => 'Other',
            'type' => 'expense',
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100,
            'type' => 'expense',
            'description' => 'Lunch',
            'transaction_date' => '2026-01-01',
        ]);

        Transaction::query()->create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'amount' => 555,
            'type' => 'expense',
            'description' => 'Secret',
            'transaction_date' => '2026-01-02',
        ]);

        $jsonResponse = $this->actingAs($user)->get(route('transactions.export.json'));
        $rows = json_decode($jsonResponse->streamedContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(1, $rows);
        $this->assertSame('Lunch', $rows[0]['description']);
        $this->assertSame('Food', $rows[0]['category']);

        $csvResponse = $this->actingAs($user)->get(route('transactions.export.csv'));
        $csv = $csvResponse->streamedContent();

        $this->assertStringContainsString('Lunch', $csv);
        $this->assertStringNotContainsString('Secret', $csv);
    }

    public function test_export_buttons_are_visible_on_transactions_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertOk();
        $response->assertSee('href="' . route('transactions.export.csv') . '"', false);
        $response->assertSee('href="' . route('transactions.export.json') . '"', false);
        $response->assertSee('fa-file-csv', false);
        $response->assertSee('fa-file-code', false);
    }
}
