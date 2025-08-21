<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('freshcart');
});

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

Route::get('/signin', function () {
    return view('auth.signin');
})->name('signin');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::get('/verify-email/{id}', [AuthController::class, 'verifyEmail'])->name('verify.email');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('products');

    Route::get('/categories', function () {
        return view('admin.categories');
    })->name('categories');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('/admins', function () {
        return view('admin.admins');
    })->name('admins');

    Route::get('/roles', function () {
        return view('admin.roles');
    })->name('roles');
});
