<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\TeamsController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\ProductGalleryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');

Route::put('/user', [UserController::class,'update'])->middleware('auth:sanctum');;
Route::get('/teams', [TeamsController::class,'index']);
Route::get('/products', [ProductController::class,'index']);
Route::post('/products', [ProductController::class,'store'])->middleware('auth:sanctum');
Route::get('/products/{product}', [ProductController::class,'show']);
Route::put('/products/{product}', [ProductController::class,'update'])->middleware('auth:sanctum');
Route::get('/cart', [CartController::class,'index'])->middleware('auth:sanctum');
Route::patch('/cart', [CartController::class,'update'])->middleware('auth:sanctum');
Route::post('/checkout', [CheckoutController::class,'submit'])->middleware('auth:sanctum');


Route::post('/products/{product}/gallery', [ProductGalleryController::class,'store']);
Route::put('/gallery/{productGallery}', [ProductGalleryController::class,'update']);
Route::delete('/gallery/{productGallery}', [ProductGalleryController::class,'delete']);


Route::get('/tags', [TagController::class,'index']);
Route::post('/tags', [TagController::class,'store'])->middleware('auth:sanctum');