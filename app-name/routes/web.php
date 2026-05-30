<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

Route::get('/pages/{slug}', function (string $slug) {
    abort_unless(preg_match('/^[A-Za-z0-9-]+$/', $slug), 404);
    return view('page', compact('slug'));
})->name('pages.show');

Route::get('/quote', QuoteController::class)->name('quote');

/*
|--------------------------------------------------------------------------
| Products (public read)
|--------------------------------------------------------------------------
*/
Route::resource('products', ProductController::class);

/*
|--------------------------------------------------------------------------
| Orders (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::resource('orders', OrderController::class)
        ->only(['index','show','create','store','edit','update','destroy'])
        ->names('orders');

        Route::post('/orders/{order}/restore',
        [OrderController::class, 'restore'])
        ->withTrashed()
        ->name('orders.restore');
    
    Route::delete('/orders/{order}/force',
        [OrderController::class, 'forceDelete'])
        ->withTrashed()
        ->name('orders.forceDelete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';