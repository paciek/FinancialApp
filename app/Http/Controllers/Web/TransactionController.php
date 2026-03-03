<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::query()
            ->with('category')
            ->where('user_id', (int) $request->user()->id)
            ->orderByDesc('transaction_date');

        $type = $request->string('type')->toString();
        if (in_array($type, ['income', 'expense'], true)) {
            $query->where('type', $type);
        }

        $dateFrom = $request->string('date_from')->toString();
        if ($dateFrom !== '') {
            $query->whereDate('transaction_date', '>=', $dateFrom);
        }

        $dateTo = $request->string('date_to')->toString();
        if ($dateTo !== '') {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('transactions.index', compact('transactions'));
    }
}

