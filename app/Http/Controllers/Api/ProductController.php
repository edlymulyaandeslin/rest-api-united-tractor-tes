<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => "Data is Empty"
            ]);
        }

        return new ProductResource(200, 'List Data Products', $products);
    }

    public function store(Request $request)
    {
        $validateDate = $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'image' => 'nullable',
        ]);

        if ($request->file('image')) {
            $filename = time() . '-' . $request->image->getClientOriginalName();

            $path = $request->file('image')->storeAs('products', $filename, 'public');

            $validateDate['image'] = $path;
        }

        $product = Product::create($validateDate);

        return new ProductResource(201, 'Product Created', $product);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return new ProductResource(200, 'Detail Product', $product);
    }

    public function update(Product $product, Request $request)
    {
        $rules = [
            'category_id' => 'nullable',
            'name' => 'nullable',
            'price' => 'nullable',
            'image' => 'nullable',
        ];

        $validateData = $request->validate($rules);

        if ($request->file('image')) {
            if ($product->image) {
                Storage::delete($product->image);
            }

            $filename = time() . '-' . $request->image->getClientOriginalName();

            $path = $request->file('image')->storeAs('products', $filename, 'public');

            $validateData['image'] = $path;
        }

        $product->update($validateData);

        return new ProductResource(200, 'Product Updated', $product);
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }

        $product->delete();

        return new ProductResource(200, 'Product Deleted', null);
    }
}
