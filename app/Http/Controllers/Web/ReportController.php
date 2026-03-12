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

        return view('reports.summary', compact('income', 'expenses', 'balance'));
    }
}
