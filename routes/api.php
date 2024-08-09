<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('categories', CategoryController::class)->middleware(['auth:sanctum']);
Route::apiResource('products', ProductController::class)->middleware(['auth:sanctum']);

Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);

Route::get('Unauthenticated', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('Unauthenticated');
