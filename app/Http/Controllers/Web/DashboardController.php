<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MonthlyBudget;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $income - $expenses;

        $recentTransactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        $expensesByCategory = Transaction::query()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $totalTransactions = Transaction::where('user_id', $userId)->count();
        $incomeCount = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->count();
        $expenseCount = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->count();
        $averageIncome = (float) Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->avg('amount');
        $averageExpense = (float) Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->avg('amount');
        $largestIncome = (float) (Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->orderByDesc('amount')
            ->value('amount') ?? 0);
        $largestExpense = (float) (Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->orderByDesc('amount')
            ->value('amount') ?? 0);

        $topExpenseCategory = $expensesByCategory
            ->sortByDesc('total')
            ->first();

        $budget = MonthlyBudget::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $spent = (float) Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $budgetExceeded = $budget && $spent > (float) $budget->limit_amount;
        $percentage = $budget && (float) $budget->limit_amount > 0
            ? (int) round(($spent / (float) $budget->limit_amount) * 100)
            : 0;

        return view('dashboard.index', [
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $balance,
            'recentTransactions' => $recentTransactions,
            'totalTransactions' => $totalTransactions,
            'incomeCount' => $incomeCount,
            'expenseCount' => $expenseCount,
            'averageIncome' => $averageIncome,
            'averageExpense' => $averageExpense,
            'largestIncome' => $largestIncome,
            'largestExpense' => $largestExpense,
            'topExpenseCategory' => $topExpenseCategory,
            'budget' => $budget,
            'spent' => $spent,
            'budgetExceeded' => $budgetExceeded,
            'percentage' => $percentage,
        ]);
    }
}
