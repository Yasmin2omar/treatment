<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ComplaintController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\OrderController;
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

Route::get('Doctor',[DoctorController::class,'index']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/admin/orders', [AdminOrderController::class, 'index']);
        Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show']);
        Route::get('/complaints', [ComplaintController::class, 'index']);
        Route::get('/contacts', [ContactController::class, 'index']);
    });

    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        // Route::get('/user-orders/{userId}', [OrderController::class, 'userOrders']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });

    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/complaints', [ComplaintController::class, 'store']);

require __DIR__.'/auth.php';
