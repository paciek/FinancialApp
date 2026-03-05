<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->where('user_id', (int) $request->user()->id)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['income', 'expense'])],
        ]);

        Category::create([
            'user_id' => (int) $request->user()->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategoria zostala dodana.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_unless((int) $category->user_id === (int) $request->user()->id, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['income', 'expense'])],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategoria zostala zaktualizowana.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        abort_unless((int) $category->user_id === (int) $request->user()->id, 404);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategoria zostala usunieta.');
    }
}
