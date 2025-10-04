<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        try {
            $loans = Loan::with(['user', 'book'])->get();
            return response()->json($loans, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve loans', 'error' => $e->getMessage()], 500);
        }
    }

    public function detail($id){
        try {
            $loan = Loan::with(['book', 'user'])->where('id', $id)->get();
            return response()->json($loan, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve loan', 'error' => $e->getMessage()], 500);

        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'borrow_date' => 'required|date',
            ]);

            $book = Book::find($validatedData['book_id']);

            if (!$book || $book->stock < 1) {
                return response()->json(['message' => 'Book is out of stock or not found'], 400);
            }

            $validatedData['status'] = 'borrowed';
            $loan = Loan::create($validatedData);

            if ($loan) {
                $book->decrement('stock');
                return response()->json(['message' => 'success', 'loan' => $loan], 201);
            } else {
                return response()->json(['message' => 'Failed to create loan record'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process loan', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $loan = Loan::with('book')->find($id);

            if (!$loan) {
                return response()->json(['message' => "loan not found"], 404);
            }

            // Validasi hanya saat mengembalikan buku (RETURNED)
            $validatedData = $request->validate([
                'status' => 'required|in:borrowed,returned',
                'return_date' => 'nullable|date|after_or_equal:borrow_date',
            ]);

            // Hanya proses pengembalian jika status berubah menjadi 'returned' dan belum dikembalikan sebelumnya
            if ($validatedData['status'] === 'returned' && $loan->status === 'borrowed') {

                // Pastikan return_date diisi
                if (empty($validatedData['return_date'])) {
                    $validatedData['return_date'] = now();
                }

                $loan->update($validatedData);

                // Tambahkan stok buku
                $loan->book->increment('stock');

                return response()->json(['message' => "Book successfully returned"], 200);
            }

            // Jika hanya mengupdate data lain (misalnya borrow_date diubah), tapi status tetap borrowed
            $loan->update($validatedData);
            return response()->json(['message' => "Loan record updated"], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update loan', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $loan = Loan::find($id);

            if (!$loan) {
                return response()->json(['message' => 'loan not found'], 404);
            }

            // Jika pinjaman dihapus saat status masih 'borrowed', stok harus dikembalikan
            if ($loan->status === 'borrowed') {
                $loan->book->increment('stock');
            }

            $loan->delete();

            return response()->json(['message' => "success"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete loan', 'error' => $e->getMessage()], 500);
        }
    }
}
