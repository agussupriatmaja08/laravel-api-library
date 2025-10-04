<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json($categories);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch categories'], 500);
        }
    }

    public function detail($id){
        try {
            $category = Category::with(['books'])->where('id', $id)->get();
            return response()->json($category, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve category', 'error' => $e->getMessage()], 500);

        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:20|unique:categories,name',
            'description' => 'required'
        ]);

        try {
            $category = Category::create($validatedData);
            return response()->json(['message' => 'success', 'category' => $category], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create category'], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $find = Category::find($id);

        if (!$find) {
            return response()->json(['message' => "category not found"], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:20|unique:categories,name,' . $find->id,
            'description' => 'required'
        ]);

        try {
            $find->update($validatedData);
            return response()->json(['message' => "success"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update category'], 500);
        }

    }

    public function destroy($id)
    {
        $data = Category::find($id);

        if (!$data) {
            return response()->json(['message' => 'category not found'], 404);
        }

        try {
            $data->delete();
            return response()->json(['message' => "success"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete category'], 500);
        }
    }
}
