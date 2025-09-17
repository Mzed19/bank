<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Public APIs (no auth)
Route::post('login', [AccountController::class, 'login']);
Route::post('deposits', [TransactionController::class, 'deposit']);
Route::post('accounts', [AccountController::class, 'create']);

// Authenticated user APIs
Route::middleware('auth:api')->group(function () {
    Route::prefix('accounts/transactions')->group(function () {
        Route::post('transfers', [TransactionController::class, 'transfer']);
        Route::get('', [TransactionController::class, 'loggedAccountTransactions']);
    });
});

// Admin APIs - But now are no auth
Route::middleware([])->group(function () {
    Route::get('transactions', [TransactionController::class, 'getAllTransactions']);
    Route::get('accounts', [AccountController::class, 'getAccounts']);
});
