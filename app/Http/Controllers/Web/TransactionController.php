<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
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
        Transaction::create([
            'user_id' => (int) $request->user()->id,
            'category_id' => (int) $request->input('category_id'),
            'amount' => $request->input('amount'),
            'type' => $request->string('type')->toString(),
            'description' => $request->filled('description') ? $request->string('description')->toString() : null,
            'transaction_date' => $request->string('transaction_date')->toString(),
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została dodana.');
    }

    public function index(Request $request): View
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'type' => 'nullable|in:income,expense',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('user_id', (int) $request->user()->id),
            ],
            'sort' => 'nullable|in:transaction_date,amount',
            'direction' => 'nullable|in:asc,desc',
        ]);

        $query = Transaction::with('category')
            ->where('user_id', (int) $request->user()->id);

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where('description', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->string('date_from')->toString());
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->string('date_to')->toString());
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->input('category_id'));
        }

        $summaryQuery = clone $query;

        $summary = $summaryQuery
            ->selectRaw("
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as total_expense
            ")
            ->first();

        $totalIncome = (float) ($summary->total_income ?? 0);
        $totalExpense = (float) ($summary->total_expense ?? 0);
        $balance = $totalIncome - $totalExpense;

        $sort = $request->get('sort', 'transaction_date');
        $direction = $request->get('direction', 'desc');

        $transactions = $query
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        $currency = $request->user()->default_currency ?? 'PLN';

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'sort',
            'direction',
            'totalIncome',
            'totalExpense',
            'balance',
            'currency'
        ));
    }

    public function edit(Transaction $transaction, Request $request): View
    {
        abort_if($transaction->user_id !== (int) $request->user()->id, 403);
        abort_if($transaction->trashed(), 404);

        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(StoreTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        abort_if($transaction->user_id !== (int) $request->user()->id, 403);
        abort_if($transaction->trashed(), 404);

        $transaction->update([
            'category_id' => (int) $request->input('category_id'),
            'amount' => $request->input('amount'),
            'type' => $request->string('type')->toString(),
            'description' => $request->filled('description') ? $request->string('description')->toString() : null,
            'transaction_date' => $request->string('transaction_date')->toString(),
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została zaktualizowana.');
    }

    public function destroy(Transaction $transaction, Request $request): RedirectResponse
    {
        abort_if($transaction->user_id !== (int) $request->user()->id, 403);

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została usunięta.');
    }
}
