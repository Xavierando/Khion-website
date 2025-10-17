<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::get('/{vue_capture?}', function () {
    return view('app');
})->where('vue_capture', '[\/\w\.-]*');
