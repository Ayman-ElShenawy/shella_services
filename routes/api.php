<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceInformationController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'Register');
    Route::post('login', 'login');
    Route::get('profile', 'profile')->middleware('auth:sanctum');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
    Route::post('changePassword', 'changePassword')->middleware('auth:sanctum');
});
route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('service', ServiceController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('order', OrderController::class);
    Route::apiResource('serviceinformation', ServiceInformationController::class);
});

