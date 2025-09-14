<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Public APIs (no auth)
Route::post('login', [AccountController::class, 'login']);
Route::post('deposits', [TransactionController::class, 'createDeposit']);
Route::post('accounts', [AccountController::class, 'create']);

// Authenticated user APIs
Route::middleware('auth:api')->group(function () {
    Route::prefix('accounts/transactions')->group(function () {
        Route::post('transfers', [TransactionController::class, 'createTransfer']);
        Route::get('', [TransactionController::class, 'loggedAccountTransactions']);
    });
});

// Admin APIs
Route::middleware([])->group(function () {
    Route::get('transactions', [TransactionController::class, 'getAllAccountsTransactions']);
    Route::get('accounts', [AccountController::class, 'getAccounts']);
});
