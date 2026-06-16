<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;

// Sākumlapa
Route::get('/', function () {
    return redirect()->route('recipes.index');
});

// ========== AUTENTIFIKĀCIJA ==========
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========== RECEPTES ==========
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');

Route::get('/pievienot', function () {
    $categories = App\Models\Category::all();
    $ingredients = App\Models\Ingredient::all();
    return view('recipes.create', compact('categories', 'ingredients'));
})->name('recipes.create')->middleware('auth');

Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store')->middleware('auth');

Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit')->middleware('auth');
Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update')->middleware('auth');
Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy')->middleware('auth');

// KOMENTĀRI
Route::post('/recipes/{recipe}/comment', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store')->middleware('auth');