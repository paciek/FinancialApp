<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $userId = (int) $request->user()->id;

        $summary = Transaction::query()
            ->where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as total_expense,
                COUNT(*) as total_count
            ")
            ->first();

        $totalIncome = (float) ($summary->total_income ?? 0);
        $totalExpense = (float) ($summary->total_expense ?? 0);
        $balance = $totalIncome - $totalExpense;
        $transactionCount = (int) ($summary->total_count ?? 0);

        $latestTransactions = Transaction::query()
            ->where('user_id', $userId)
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->limit(10)
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
