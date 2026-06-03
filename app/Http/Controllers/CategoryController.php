<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\AuditLogger;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $categories = Category::orderBy('name')->paginate(20);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.form', ['category' => new Category()]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        AuditLogger::log('category.created', $category, null, ['name' => $category->name]);

        return redirect()->route('categories.index')
                         ->with('success', "Category \"{$category->name}\" created.");
    }

    public function edit(Category $category)
    {
        return view('categories.form', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $old = $category->name;
        $category->update($request->validated());

        AuditLogger::log('category.updated', $category, ['name' => $old], ['name' => $category->name]);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function deactivate(Category $category)
    {
        $category->update(['is_active' => false]);
        AuditLogger::log('category.deactivated', $category, ['is_active' => true], ['is_active' => false]);

        return redirect()->route('categories.index')
                         ->with('success', "Category \"{$category->name}\" deactivated.");
    }

    public function activate(Category $category)
    {
        $category->update(['is_active' => true]);

        return redirect()->route('categories.index')
                         ->with('success', "Category \"{$category->name}\" reactivated.");
    }
}
