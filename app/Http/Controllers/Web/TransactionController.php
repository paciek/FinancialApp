<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function create(Request $request): View
    {
        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Transaction::create([
            'user_id' => (int) $request->user()->id,
            'category_id' => (int) $validated['category_id'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została dodana.');
    }
}

