<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->get();
        return view('admin.sub-categories.index')->with('subCategories', $subCategories);
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
        $subCategories = SubCategory::where('category_id', $categoryId)->get();
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