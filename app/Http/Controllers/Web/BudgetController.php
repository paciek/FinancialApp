<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MonthlyBudget;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $budget = MonthlyBudget::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $spentAmount = (float) Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $limitAmount = (float) ($budget?->limit_amount ?? 0);
        $remainingAmount = $limitAmount - $spentAmount;
        $percentageUsed = $limitAmount > 0
            ? (int) round(($spentAmount / $limitAmount) * 100)
            : 0;

        return view('budget.index', [
            'budget' => $budget,
            'spent_amount' => $spentAmount,
            'remaining_amount' => $remainingAmount,
            'percentage_used' => $percentageUsed,
            'current_month' => $currentMonth,
            'current_year' => $currentYear,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'limit_amount' => 'required|numeric|min:0',
        ]);

        $currentMonth = now()->month;
        $currentYear = now()->year;

        MonthlyBudget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'month' => $currentMonth,
                'year' => $currentYear,
            ],
            [
                'limit_amount' => $validated['limit_amount'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Budzet zapisany.');
    }
}
