<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


use App\Models\User;

Route::get('/', function () {
    $users = User::all();
    return view('welcome', compact('users'));
});

Route::get('/login', [UserController::class,'showLogin'])->name('login');
Route::get('/register', [UserController::class,'showRegister'])->name('register');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');



