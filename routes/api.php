<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use PharIo\Manifest\AuthorCollectionIterator;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use Laravel\Passport\Passport;



// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('/categories', function () {
//     $categories = Category::all();
//     return $categories;
// });


// Passport::routes();


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



// tanpa token
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'detail']);
    Route::post('/login', [AuthController::class, 'login']);
});

// butuh token
Route::middleware('auth:api')->group(function () {
    //    Categories
    Route::get('/categories/{id}', [CategoryController::class, 'detail']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // author
    Route::get('/authors', [AuthorController::class, 'index']);
    Route::get('/authors/{id}', [AuthorController::class, 'detail']);
    Route::post('/authors', [AuthorController::class, 'store']);
    Route::put('/authors/{id}', [AuthorController::class, 'update']);
    Route::delete('/authors/{id}', [AuthorController::class, 'destroy']);


    // publisher 
    Route::get('publishers', [PublisherController::class, 'index']);
    Route::get('/publishers/{id}', [PublisherController::class, 'detail']);
    Route::post('publishers', [PublisherController::class, 'store']);
    Route::put('publishers/{id}', [PublisherController::class, 'update']);
    Route::delete('publishers/{id}', [PublisherController::class, 'destroy']);

    // Book
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'detail']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    // Loan
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/loan/{id}', [LoanController::class, 'detail']);

    Route::post('/loans', [LoanController::class, 'store']);
    Route::put('/loans/{id}', [LoanController::class, 'update']);
    Route::delete('/loans/{id}', [LoanController::class, 'destroy']);
});



// cara buat secret id dan secret client :
// php artisan passport:client --password
// http://127.0.0.1:8001/docs/api mengakses dokumen api
