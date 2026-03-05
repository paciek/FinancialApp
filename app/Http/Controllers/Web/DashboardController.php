<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = (int) auth()->id();

        $summary = Transaction::query()
            ->where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as total_expense,
                COUNT(*) as total_transactions
            ")
            ->first();

        $totalIncome = (float) ($summary->total_income ?? 0);
        $totalExpense = (float) ($summary->total_expense ?? 0);
        $balance = $totalIncome - $totalExpense;
        $transactionCount = (int) ($summary->total_transactions ?? 0);

        $latestTransactions = Transaction::query()
            ->where('user_id', $userId)
            ->with('category')
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'transactionCount',
            'latestTransactions'
        ));
    }
}
