<?php

use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductGalleryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\TeamsController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/teams', [TeamsController::class, 'index']);

Route::apiResource('products', ProductController::class)->only(['index', 'show']);

Route::get('/tags', [TagController::class, 'index']);

Route::middleware('auth')->group(function () {

    Route::get('user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::put('user', [UserController::class, 'update']);

    Route::apiResource('products', ProductController::class)->only(['store', 'update']);

    Route::get('/cart', [CartController::class, 'index'])->middleware('auth:sanctum');
    Route::patch('/cart', [CartController::class, 'update'])->middleware('auth:sanctum');

    Route::post('/checkout', [CheckoutController::class, 'submit']);
    Route::get('/checkout/{order}', [CheckoutController::class, 'retry']);

    Route::post('/products/{product}/gallery', [ProductGalleryController::class, 'store']);
    Route::put('/gallery/{productGallery}', [ProductGalleryController::class, 'update']);
    Route::delete('/gallery/{productGallery}', [ProductGalleryController::class, 'delete']);

    Route::apiResource('orders', OrderController::class)->except(['store']);

    Route::post('/tags', [TagController::class, 'store']);
});
