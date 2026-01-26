<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController; // Controlador de autenticación
use App\Http\Controllers\RoleController; // Controlador de roles
use App\Http\Controllers\CategoryController; // Controlador de categorías
use App\Http\Controllers\ProductController; // Controlador de productos

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// http://localhost:8000/api/categories
Route::prefix('categories')->group(function () {
    Route::get('index', [ CategoryController::class, 'index'])->name('categories.index');
    Route::post('register', [ CategoryController::class, 'store'])->name('categories.store');
    Route::get('find/{id}', [ CategoryController::class, 'show'])->name('categories.show');
    Route::put('update/{id}', [ CategoryController::class, 'update'])->name('categories.update');
    Route::delete('delete/{id}', [ CategoryController::class, 'destroy'])->name('categories.destroy');

    // Rutas para papelera
    Route::get('trash', [ CategoryController::class, 'trash'])->name('categories.trash');
});

Route::prefix('products')->group(function () {
    Route::get("index", [ ProductController::class, 'index'])->name('products.index');
    Route::post("register", [ ProductController::class, 'store'])->name('products.store');
    Route::get("find/{id}", [ ProductController::class, 'show'])->name('products.show');
    Route::put("update/{id}", [ ProductController::class, 'update'])->name('products.update');
    Route::delete("delete/{id}", [ ProductController::class, 'destroy'])->name('products.destroy');
});

Route::prefix('roles')->group(function () {
    Route::get("index", [ RoleController::class, 'index'])->name('roles.index');
    Route::post("register", [ RoleController::class, 'store'])->name('roles.store');
});

// Autenticación base
Route::prefix('auth')->group(function () {
    Route::post('register', [ AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [ AuthController::class, 'login'])->name('auth.login');

    // Rutas protegidas (Rutas que necesitan si o si el token de autenticación)
    Route::middleware(['auth:api'])->group(function () {
        Route::get('profile', [ AuthController::class, 'profile'])->name('auth.profile');
        Route::get('logout', [ AuthController::class, 'logout'])->name('auth.logout');
    });
});


