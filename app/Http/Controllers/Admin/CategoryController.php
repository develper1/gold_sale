<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortBy  = $request->input('sort_by', 'sort_order');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['sort_order', 'name', 'slug'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'sort_order';
        }

        $categories = Category::orderByRaw($sortBy === 'sort_order' ? 'sort_order IS NULL' : '0')
            ->orderBy($sortBy, $sortDir)
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        $category = null;
        return view('admin.categories.add_edit', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.add_edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
} 