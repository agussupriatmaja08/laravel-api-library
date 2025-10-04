<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Exception;

class PublisherController extends Controller
{
    public function index()
    {
        try {
            $publishers = Publisher::all();
            return response()->json($publishers);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch publishers'], 500);
        }
    }

     public function detail($id){
        try {
            $publisher = Publisher::with(['books'])->where('id', $id)->get();
            return response()->json($publisher, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve publisher', 'error' => $e->getMessage()], 500);

        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100|unique:publishers,name',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        try {
            $publisher = Publisher::create($validatedData);
            return response()->json(['message' => 'success', 'publisher' => $publisher], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create publisher'], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $find = Publisher::find($id);

        if (!$find) {
            return response()->json(['message' => "publisher not found"], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:100|unique:publishers,name,' . $find->id,
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        try {
            $find->update($validatedData);
            return response()->json(['message' => "success"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update publisher'], 500);
        }

    }

    public function destroy($id)
    {
        $data = Publisher::find($id);

        if (!$data) {
            return response()->json(['message' => 'publisher not found'], 404);
        }

        try {
            $data->delete();
            return response()->json(['message' => "success"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete publisher'], 500);
        }
    }
}
