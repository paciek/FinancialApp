<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request): View
    {
        $userId = (int) $request->user()->id;
        $month = Carbon::now()->format('Y-m');
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $budget = Budget::query()
            ->where('user_id', $userId)
            ->where('month', $month)
            ->first();

        $spentAmount = (float) Transaction::query()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $limitAmount = (float) ($budget?->limit_amount ?? 0);
        $progressPercent = $limitAmount > 0
            ? min(100.0, ($spentAmount / $limitAmount) * 100)
            : 0.0;

        return view('budget.index', [
            'budget' => $budget,
            'month' => $month,
            'spentAmount' => $spentAmount,
            'limitAmount' => $limitAmount,
            'progressPercent' => $progressPercent,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'limit_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $month = Carbon::now()->format('Y-m');

        Budget::query()->updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'month' => $month,
            ],
            [
                'limit_amount' => $validated['limit_amount'],
            ]
        );

        return redirect()
            ->route('budget.index')
            ->with('success', 'Budzet zostal zapisany.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'limit_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $month = Carbon::now()->format('Y-m');

        Budget::query()->updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'month' => $month,
            ],
            [
                'limit_amount' => $validated['limit_amount'],
            ]
        );

        return redirect()
            ->route('budget.index')
            ->with('success', 'Budzet zostal zaktualizowany.');
    }
}
