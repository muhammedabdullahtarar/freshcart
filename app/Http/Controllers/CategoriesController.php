<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class CategoriesController extends Controller
{
    public function getCategories()
    {
        $isAuthorized = auth()->user()->can('view', Category::class);

        $query = Category::where('is_archived', false);

        if ($isAuthorized) {
            $categories = $query->get();
        } else {
            $categories = $query->select('id', 'name')->get();
        }

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }

    public function getCategory($id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $this->authorize('view', Category::class);

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Category::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Category name is required',
            'name.string' => 'Category name must be a string',
            'name.max' => 'Category name cannot exceed 255 characters',
            'name.unique' => 'This category name already exists',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = new Category;
        $category->name = $request->name;
        $category->is_archived = false;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $this->authorize('edit', Category::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Category name is required',
            'name.string' => 'Category name must be a string',
            'name.max' => 'Category name cannot exceed 255 characters',
            'name.unique' => 'This category name already exists',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->name = $request->name;
        $category->is_archived = false;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
        ]);
    }

    public function delete(Request $request, $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }



        $this->authorize('delete', Category::class);

        if ($request->query('force') === 'true') {

            foreach ($category->products as $product) {

                $product->categories()->detach($category->id);

                if ($product->categories()->count() === 0) {
                    foreach ($product->images as $image) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }

                    $product->delete();
                }
            }


            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category and all associated products deleted successfully',
            ]);
        }

        if ($category->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category because it has associated products.',
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    public function archive($id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->is_archived = true;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category archived successfully',
        ]);
    }
}
