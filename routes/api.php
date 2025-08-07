<?php

// Correct controller imports for the accounting project
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\AuthController; // Assuming you moved it
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);



Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/accounts', [AccountController::class, 'index']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::get('accounts/{account}/balance', [AccountController::class, 'balance']);

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});