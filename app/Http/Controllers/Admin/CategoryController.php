<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('name')) {
            $name = $request->input('name');
            $query->where('name', 'like', "%{$name}%");
        }

        $categories = $query->latest()->paginate(10);

        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json(['success' => 'Category created successfully', 'category' => new CategoryResource($category)], 201);
    }
    
    public function show(string $id)
    {
        try {
            $category = Cache::remember("category_{$id}", 60, function () use ($id) {
                return Category::with('tasks')->findOrFail($id);
            });

            return new CategoryResource($category);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function update(CategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $data = $request->validated();
            $category->update($data);
            Cache::forget("category_{$id}");

            return response()->json(['success' => 'Category updated successfully', 'category' => new CategoryResource($category)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            Cache::forget("category_{$id}");

            return response()->json(['success' => 'Category deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }
}
