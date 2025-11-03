<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::post('/login', function () {
    // Just redirect for now
    return redirect('/');
})->name('login');

Route::post('/dashboard', function () {
    // Just redirect for now
    return redirect('/');
})->name('dashboard');

Route::post('/books.index', function () {
    // Just redirect for now
    return redirect('/');
})->name('books.index');

Route::post('/', function () {
    // Just redirect for now
    return redirect('/');
})->name('');
