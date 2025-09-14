<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::group(['comment' => 'APIs without authorization'], function () {
    Route::post('login', [AccountController::class, "login"]);
    Route::post('deposits', [TransactionController::class, "createDeposit"]);
    Route::post('accounts', [AccountController::class, "create"]);
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'transactions'], function () {
        Route::post('transfers', [TransactionController::class, "createTransfer"]);
        Route::get('extracts', [TransactionController::class, "getExtract"]);
    });
});

Route::group(['comment' => 'APIs for admin accounts'], function () {
    Route::get('extracts', [TransactionController::class, "getExtracts"]);
    Route::get('accounts', [AccountController::class, "all"]);
});
