<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function expensesByCategory(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $baseQuery = Transaction::query()
            ->where('user_id', (int) $request->user()->id);

        if ($request->filled('date_from')) {
            $baseQuery->whereDate('transaction_date', '>=', $request->string('date_from')->toString());
        }

        if ($request->filled('date_to')) {
            $baseQuery->whereDate('transaction_date', '<=', $request->string('date_to')->toString());
        }

        $summary = (clone $baseQuery)
            ->selectRaw("
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as total_expense
            ")
            ->first();

        $totalIncome = (float) ($summary->total_income ?? 0);
        $totalExpense = (float) ($summary->total_expense ?? 0);
        $balance = $totalIncome - $totalExpense;

        $expenses = (clone $baseQuery)
            ->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $chartLabels = $expenses->map(static fn ($item) => $item->category?->name ?? 'Bez kategorii')->values();
        $chartData = $expenses->map(static fn ($item) => (float) $item->total)->values();
        $total = (float) $expenses->sum('total');

        return view('reports.expenses-by-category', compact(
            'chartLabels',
            'chartData',
            'total',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }

    public function balanceOverTime(Request $request): View
    {
        $transactions = Transaction::query()
            ->where('user_id', (int) $request->user()->id)
            ->orderBy('transaction_date')
            ->get(['transaction_date', 'type', 'amount']);

        $monthlyBalances = $transactions
            ->groupBy(static fn (Transaction $transaction) => $transaction->transaction_date->format('Y-m'))
            ->map(static function ($items): float {
                return (float) $items->sum(static fn (Transaction $transaction): float => $transaction->type === 'income'
                    ? (float) $transaction->amount
                    : -(float) $transaction->amount
                );
            });

        $labels = $monthlyBalances->keys()->values();
        $values = $monthlyBalances->values();

        return view('reports.balance-over-time', compact('labels', 'values'));
    }
}
