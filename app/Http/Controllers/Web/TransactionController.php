<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $userId = (int) $request->user()->id;
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'type' => ['nullable', Rule::in(['income', 'expense'])],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('user_id', $userId)),
            ],
            'sort' => ['nullable', Rule::in(['date', 'amount'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $categories = Category::query()
            ->where('user_id', $userId)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $transactionsQuery = Transaction::query()
            ->where('user_id', $userId)
            ->with('category');

        if (!empty($filters['date_from'])) {
            $transactionsQuery->whereDate('transaction_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $transactionsQuery->whereDate('transaction_date', '<=', $filters['date_to']);
        }
        if (!empty($filters['type'])) {
            $transactionsQuery->where('type', $filters['type']);
        }
        if (!empty($filters['category_id'])) {
            $transactionsQuery->where('category_id', (int) $filters['category_id']);
        }

        $sort = $filters['sort'] ?? 'date';
        $direction = $filters['direction'] ?? 'desc';
        $sortColumn = $sort === 'amount' ? 'amount' : 'transaction_date';

        $transactions = $transactionsQuery
            ->orderBy($sortColumn, $direction)
            ->orderBy('id', $direction)
            ->get();

        return view('transactions.index', compact('categories', 'transactions', 'filters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = (int) $request->user()->id;
        $validated = $this->validateTransaction($request, $userId);

        Transaction::create([
            'user_id' => $userId,
            'category_id' => (int) $validated['category_id'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transakcja zostala dodana.');
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $userId = (int) $request->user()->id;
        abort_unless((int) $transaction->user_id === $userId, 404);

        $validated = $this->validateTransaction($request, $userId);

        $transaction->update([
            'category_id' => (int) $validated['category_id'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transakcja zostala zaktualizowana.');
    }

    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_unless((int) $transaction->user_id === (int) $request->user()->id, 404);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transakcja zostala usunieta.');
    }

    private function validateTransaction(Request $request, int $userId): array
    {
        return $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('user_id', $userId)),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', Rule::in(['income', 'expense'])],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);
    }
}
