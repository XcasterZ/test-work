<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;


Route::post('/register', [RegisterController::class, 'register']); //ทำแล้ว
Route::post('/login', [LoginController::class, 'login']); //ทำแล้ว
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum'); //ทำแล้ว

Route::get('/products/{id}', [ProductController::class, 'show']);  //ทำแล้ว

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [ProductController::class, 'index']); //ทำแล้ว

    Route::post('/products', [ProductController::class, 'store']); //ทำแล้ว
    Route::delete('/products/{product}', [ProductController::class, 'destroy']); //ทำแล้ว

    Route::post('/products/{id}/reviews', [ReviewController::class, 'store']); //ทำแล้ว
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);  //ทำแล้ว
});
