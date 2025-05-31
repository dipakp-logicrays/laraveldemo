<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Only Logged in users can access these routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::resource('contacts', ContactController::class);
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
});


// Public routes
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/search', [PostController::class, 'search'])->name('posts.search');

// Blog routes
Route::resource('posts', PostController::class);

// Additional post routes
Route::middleware('auth')->group(function () {
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my');
});

// Category routes (optional)
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])
      ->name('categories.show');

// Tag routes (optional)
Route::get('/tags/{tag:slug}', [TagController::class, 'show'])
      ->name('tags.show');


require __DIR__.'/auth.php';
