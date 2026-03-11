<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with('category')
            ->where('user_id', auth()->id())
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

    public function create(): View
    {
        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $request->integer('category_id'),
            'amount' => $request->input('amount'),
            'type' => $request->string('type')->toString(),
            'description' => $request->string('description')->toString() ?: null,
            'transaction_date' => $request->string('transaction_date')->toString(),
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została dodana.');
    }
}