<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Exception;

class AuthorController extends Controller
{
    public function index()
    {
        try {
            $authors = Author::all();
            return response()->json($authors);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch authors'], 500);
        }
    }

      public function detail($id){
        try {
            $author = Author::with(['books'])->where('id', $id)->get();
            return response()->json($author, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve author', 'error' => $e->getMessage()], 500);

        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100|unique:authors,name',
            'bio' => 'required|string',
        ]);

        try {
            $author = Author::create($validatedData);
            return response()->json(['message' => 'success', 'author' => $author], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create author'], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $find = Author::find($id);

        if (!$find) {
            return response()->json(['message' => "author not found"], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:100|unique:authors,name,' . $find->id,
            'bio' => 'required|string',
        ]);

        try {
            $find->update($validatedData);
            return response()->json(['message' => "success"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update author'], 500);
        }

    }

    public function destroy($id)
    {
        $data = Author::find($id);

        if (!$data) {
            return response()->json(['message' => 'author not found'], 404);
        }

        try {
            $data->delete();
            return response()->json(['message' => "success"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete author'], 500);
        }
    }
}
