<?php

use App\Http\Controllers\UserController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/users/register', [UserController::class, 'register'])->name('api.register');
Route::post('/users/login', [UserController::class, 'login'])->name('api.login');

Route::get('/profile', function () {
    $user = auth()->user();
    return view('profile', compact('user'));
})->middleware(['auth', 'web']);



Route::middleware('auth:sanctum')->group(function(){
    Route::get('/users',[UserController::class, 'index']);    
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('users/me', [UserController::class, 'me']);
    Route::post('/users/logout', [UserController::class, 'logout'])->name('api.logout');
});
