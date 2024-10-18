<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BorrowingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UsersController::class);
Route::resource('books', BooksController::class);
Route::post('/borrowings', [BorrowingsController::class, 'store']);
Route::get('/borrowings/return/{bookId}', [BorrowingsController::class, 'returnBook']);