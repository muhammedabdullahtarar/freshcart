<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function getProducts()
    {

        $query = Product::query();

        if (auth()->user()->can('view', Product::class)) {

            $query->select('id', 'name', 'price', 'stock', 'created_at')
                ->with('categories');
        } else {
            $query->select('id', 'name');
        }


        $products = $query->get();

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function getProduct($id)
    {
        $product = Product::with(['categories', 'images'])->find($id);

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $this->authorize('view', $product);

        return response()->json(['success' => true, 'product' => $product]);
    }

    public function createUpdateProduct(Request $request, $id = null)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'categories' => 'required|array',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
            'existing_images' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $product = $id ? Product::find($id) : new Product;
        $this->authorize($id ? 'edit' : 'create', $product);



        $product->fill([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ])->save();

        $product->categories()->sync($request->categories);

        if ($id) {
            $product->images()
                ->whereNotIn('id', $request->existing_images ?? [])
                ->get()
                ->each(function ($image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                });
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return response()->json([
            'success' => true,
            'product' => $product->load(['categories', 'images']),
        ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $this->authorize('delete', $product);

        $product->images->each(function ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        });

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }
}
