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

    public function index(Request $request): View
    {
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'type' => ['nullable', 'in:income,expense'],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query->where('user_id', (int) $request->user()->id)
                ),
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
}
