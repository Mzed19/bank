<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['comment' => 'APIs without authorization'], function () {
    Route::post('login', [UserController::class, "login"]);
    Route::post('deposit', [TransactionController::class, "createDeposit"]);
    Route::post('user', [UserController::class, "create"]);
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'transaction'], function () {
        Route::post('transfer', [TransactionController::class, "createTransfer"]);
        Route::get('extract', [TransactionController::class, "getExtract"]);
    });
});

Route::group(['comment' => 'APIs for admin users'], function () {
    Route::get('extracts', [TransactionController::class, "getExtracts"]);
    Route::get('user', [UserController::class, "all"]);
});
