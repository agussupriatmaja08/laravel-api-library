<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Route::get('/categories', function () {
//     $categories = Category::all();
//     return $categories;
// });


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// require __DIR__ . '/auth.php';
