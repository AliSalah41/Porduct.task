<?php

use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Products\ProductsController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/dashboard', function () {
//     return view('tables');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard',[ProductsController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('products',[ProductsController::class,'index']);

    Route::get('products.create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductsController::class, 'store'])->name('products.store');
});

Route::get('products/{id}', [ProductsController::class, 'show'])->middleware('log.request');
Route::get('GreaterPrice', [ProductsController::class, 'getGraterPrice'])->middleware('log.request');
Route::get('products/above-price/{amount}', [ProductsController::class, 'getProductsAbovePrice']);

Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);

Route::get('create-payment', [PaymentController::class, 'createPayment'])->name('payment.create')->middleware('log.request');
Route::post('process-payment', [PaymentController::class, 'handlePayment'])->name('payment.process');
Route::post('/confirm-payment', [PaymentController::class, 'confirmPayment']);



require __DIR__.'/auth.php';
