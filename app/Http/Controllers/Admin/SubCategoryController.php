<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortBy  = $request->input('sort_by', 'sort_order');
        $sortDir = $request->input('sort_dir', 'asc') === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['sort_order', 'name', 'slug', 'category'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'sort_order';
        }

        $query = SubCategory::with('category')
            ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('sub_categories.*');

        if ($sortBy === 'category') {
            $query->orderBy('categories.name', $sortDir);
        } elseif ($sortBy === 'sort_order') {
            $query->orderByRaw('sub_categories.sort_order IS NULL')->orderBy('sub_categories.sort_order', $sortDir);
        } else {
            $query->orderBy('sub_categories.' . $sortBy, $sortDir);
        }
        $query->orderBy('sub_categories.name');

        $subCategories = $query->get();

        return view('admin.sub-categories.index', compact('subCategories', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategory = null;
        return view('admin.sub-categories.add_edit', compact('categories','subCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:sub_categories',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        SubCategory::create($request->all());

        return redirect()->route('admin.sub-categories.index')->with('success', 'SubCategory created successfully');
    }

    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.sub-categories.add_edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:sub_categories,slug,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $subCategory = SubCategory::findOrFail($id);
        $subCategory->update($request->all());

        return redirect()->route('admin.sub-categories.index')->with('success', 'SubCategory updated successfully');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();
        return back()->with('success', 'SubCategory deleted successfully!');
    }

    public function getSubCategoriesByCategory($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->orderBy('sort_order')->orderBy('name')->get();
        return response()->json($subCategories);
    }

    public function show($id)
    {
        try {
            $subCategory = SubCategory::findOrFail($id);
            return response()->json($subCategory);
        } catch (\Exception $e) {
            return response()->json(['error' => 'SubCategory not found'], 404);
        }
    }
} 