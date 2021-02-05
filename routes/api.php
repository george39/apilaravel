<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register", [UserController::class, "register"]);
Route::post('/login', [UserController::class, 'login']);
Route::post('/cars', [CarController::class, 'store']);
Route::get('/get-cars', [CarController::class, 'index']);
Route::get('/get-car/{id}', [CarController::class, 'show']);
Route::put('/update-car/{id}', [CarController::class, 'update']);
Route::delete('/delete-car/{id}', [CarController::class, 'destroy']);
