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

        $monthly = Transaction::query()
            ->where('user_id', $userId)
            ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m-%d') as day, type, SUM(amount) as total")
            ->groupBy('day', 'type')
            ->orderBy('day')
            ->get();

        $dailyMap = [];
        $dayOrder = [];

        foreach ($monthly as $row) {
            $day = $row->day;
            if (!isset($dailyMap[$day])) {
                $dailyMap[$day] = ['income' => 0.0, 'expense' => 0.0];
                $dayOrder[] = $day;
            }

            if ($row->type === 'income') {
                $dailyMap[$day]['income'] += (float) $row->total;
            }

            if ($row->type === 'expense') {
                $dailyMap[$day]['expense'] += (float) $row->total;
            }
        }

        $balanceLabels = collect($dayOrder);
        $runningBalance = 0.0;
        $balanceData = collect($dayOrder)->map(function (string $day) use ($dailyMap, &$runningBalance) {
            $runningBalance += $dailyMap[$day]['income'] - $dailyMap[$day]['expense'];
            return $runningBalance;
        });

        return view('reports.summary', [
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $balance,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartColors' => $chartColors,
            'balanceLabels' => $balanceLabels,
            'balanceData' => $balanceData,
        ]);
    }
}
