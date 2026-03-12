<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index(): View
    {
        return view('export.index');
    }

    public function exportCsv(): StreamedResponse
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
        ];

        $callback = static function () use ($transactions): void {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['Date', 'Amount', 'Type', 'Category', 'Description']);

            foreach ($transactions as $transaction) {
                fputcsv($output, [
                    optional($transaction->transaction_date)->format('Y-m-d'),
                    $transaction->amount,
                    $transaction->type,
                    $transaction->category?->name ?? '-',
                    $transaction->description ?? '',
                ]);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, 'transactions.csv', $headers);
    }

    public function exportJson(): JsonResponse
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->get();

        return response()
            ->json($transactions)
            ->withHeaders([
                'Content-Disposition' => 'attachment; filename="transactions.json"',
            ]);
    }
}
