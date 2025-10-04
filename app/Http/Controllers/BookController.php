<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = Book::with(['author', 'publisher'])->get();
            return response()->json($books, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve books', 'error' => $e->getMessage()], 500);
        }
    }

    public function detail($id){
        try {
            $book = Book::with(['author', 'publisher'])->where('id', $id)->get();
            return response()->json($book, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve books', 'error' => $e->getMessage()], 500);

        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'author_id' => 'required|exists:authors,id',
                'publisher_id' => 'required|exists:publishers,id',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'isbn' => 'required|string|unique:books,isbn|max:20',
                'stock' => 'nullable|integer|min:0',
            ]);

            $book = Book::create($validatedData);

            if ($book) {
                return response()->json(['message' => 'success', 'book' => $book], 201);
            } else {
                return response()->json(['message' => 'Failed to create book'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create book', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return response()->json(['message' => "book not found"], 404);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'author_id' => 'required|exists:authors,id',
                'publisher_id' => 'required|exists:publishers,id',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                // ISBN must be unique, except for the current book's ID
                'isbn' => 'required|string|unique:books,isbn,' . $book->id . '|max:20',
                'stock' => 'nullable|integer|min:0',
            ]);

            $book->update($validatedData);

            return response()->json(['message' => "success"], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update book', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return response()->json(['message' => 'book not found'], 404);
            }

            $book->delete();

            return response()->json(['message' => "success"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete book', 'error' => $e->getMessage()], 500);
        }
    }
}
