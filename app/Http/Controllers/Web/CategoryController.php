<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->string('name')->toString(),
            'type' => $request->string('type')->toString(),
            'user_id' => (int) $request->user()->id,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategoria została utworzona.');
    }
}

