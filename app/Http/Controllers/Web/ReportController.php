<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function financialSummary(): View
    {
        $userId = auth()->id();

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $income - $expenses;

        $expensesByCategory = Transaction::query()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $chartLabels = $expensesByCategory->pluck('category.name')->values();
        $chartData = $expensesByCategory->pluck('total')->values();
        $chartColors = $expensesByCategory->pluck('category.color')->values();

        return view('reports.summary', [
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $balance,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartColors' => $chartColors,
        ]);
    }
}
