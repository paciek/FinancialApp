<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'type' => 'nullable|in:income,expense',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('user_id', auth()->id()),
            ],
            'sort' => 'nullable|in:transaction_date,amount',
            'direction' => 'nullable|in:asc,desc',
        ]);

        $query = Transaction::with('category')
            ->where('user_id', auth()->id());

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $sort = $request->get('sort', 'transaction_date');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $transactions = $query->paginate(15)->withQueryString();
        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create(): View
    {
        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('categories'));
    }

    public function edit(Transaction $transaction): View
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact('transaction', 'categories'));
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
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->update([
            'transaction_date' => $request->input('transaction_date'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'category_id' => $request->input('category_id'),
            'description' => $request->input('description'),
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja zostala zaktualizowana.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja zostala usunieta.');
    }
}
