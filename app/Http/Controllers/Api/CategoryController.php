<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(5);

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => "Data is Empty"
            ]);
        }

        return new CategoryResource(200, 'List Data Categories', $categories);
    }

    public function store(Request $request)
    {
        $validateDate = $request->validate([
            'name' => 'required|unique:categories',
        ]);

        $categories = Category::create($validateDate);

        return new CategoryResource(201, 'Category Created', $categories);
    }

    public function show(Category $category)
    {
        return new CategoryResource(200, 'Detail Category', $category);
    }

    public function update(Category $category, Request $request)
    {
        $rules = [];
        if ($request->name) {
            $rules['name'] = 'required';
        }

        $validateDate = $request->validate($rules);

        $category->update($validateDate);

        return new CategoryResource(200, 'Category Updated', $category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return new CategoryResource(200, 'Category Deleted', null);
    }
}
