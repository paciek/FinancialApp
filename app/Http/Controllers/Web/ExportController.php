<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportCsv(Request $request): StreamedResponse
    {
        $rows = $this->exportRows($request);

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['ID', 'Data', 'Kwota', 'Typ', 'Kategoria', 'Opis']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['id'],
                    $row['date'],
                    $row['amount'],
                    $row['type'],
                    $row['category'],
                    $row['description'],
                ]);
            }

            fclose($handle);
        }, 'transactions_export.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportJson(Request $request): StreamedResponse
    {
        $rows = $this->exportRows($request);

        return response()->streamDownload(function () use ($rows): void {
            echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }, 'transactions_export.json', [
            'Content-Type' => 'application/json; charset=UTF-8',
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function exportRows(Request $request): array
    {
        return Transaction::query()
            ->with('category')
            ->where('user_id', (int) $request->user()->id)
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get()
            ->map(static fn (Transaction $transaction): array => [
                'id' => (int) $transaction->id,
                'date' => $transaction->transaction_date?->format('Y-m-d'),
                'amount' => (float) $transaction->amount,
                'type' => $transaction->type,
                'category' => $transaction->category?->name,
                'description' => $transaction->description,
            ])
            ->all();
    }
}
