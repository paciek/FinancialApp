<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'type' => ['nullable', 'in:income,expense'],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query->where('user_id', (int) $request->user()->id)),
            ],
            'sort' => ['nullable', 'in:transaction_date,amount'],
            'direction' => ['nullable', 'in:asc,desc'],
        ]);

        $sort = (string) $request->get('sort', 'transaction_date');
        $direction = (string) $request->get('direction', 'desc');

        $query = Transaction::query()
            ->with('category')
            ->where('user_id', (int) $request->user()->id);

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', (string) $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', (string) $request->input('date_to'));
        }
        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->input('category_id'));
        }

        $transactions = $query
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact('transactions', 'categories', 'sort', 'direction'));
    }

    public function create(Request $request): View
    {
        $categories = $request->user()->categories()->orderBy('name')->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Transaction::create([
            'user_id' => (int) $request->user()->id,
            'transaction_date' => $validated['transaction_date'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'category_id' => (int) $validated['category_id'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transakcja została dodana.');
    }

    public function edit(Transaction $transaction): View
    {
        abort_if($transaction->user_id !== (int) auth()->id(), 403);
        abort_if($transaction->trashed(), 404);

        $categories = Category::query()
            ->where('user_id', (int) auth()->id())
            ->where('type', $transaction->type)
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_if($transaction->user_id !== (int) auth()->id(), 403);
        abort_if($transaction->trashed(), 404);

        $validated = $request->validate([
            'transaction_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where('user_id', (int) auth()->id())
                    ->where('type', (string) $request->input('type')),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $transaction->update($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została zaktualizowana.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        abort_if($transaction->user_id !== (int) auth()->id(), 403);

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transakcja została usunięta.');
    }

    public function categoriesApi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['nullable', 'in:income,expense'],
        ]);

        $query = Category::query()
            ->where('user_id', (int) $request->user()->id)
            ->orderBy('name');

        if (!empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        $categories = $query->get(['id', 'name']);

        return response()->json($categories);
    }
}

