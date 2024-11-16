<?php

use App\Http\Controllers\API\Products\ProductsController;
use App\Http\Controllers\API\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login',[UsersController::class,'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('product-index',[ProductsController::class,'index']);
    
    Route::post('product/store',[ProductsController::class,'store'])->name('products.store');
});

