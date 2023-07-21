<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// auth route
Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

// global route (available for both buyer and seller)
Route::get('/product', [ProductsController::class, "index"]);
Route::get('/product/{product}', [ProductsController::class, "show"]);

// buyer route
Route::group(['middleware' => ['auth:sanctum', 'ability:buyer']], function () {
    // Endpoint CRUD produk untuk seller
    Route::resource("/cart", CartController::class)->except(["update", "edit"]);
    Route::resource("/checkout", CheckoutController::class)->except(["update", "edit"]);
});

// seller route
Route::group(['middleware' => ['auth:sanctum', 'ability:seller']], function () {
    // Endpoint CRUD produk untuk seller
    Route::resource("/product", ProductsController::class)->except(["index", "show"]);
});

// authed route
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Endpoint logout
    Route::post("/logout", [AuthController::class, "logout"]);
});
