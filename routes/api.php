<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['comment' => 'Access allowed without authorization'], function () {
    Route::post('login', [UserController::class, "login"]);
    Route::post('deposit', [TransactionController::class, "createDeposit"]);
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('', [UserController::class, "create"]);
        Route::get('', [UserController::class, "all"]);
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::post('transfer', [TransactionController::class, "createTransfer"]);
        Route::get('extract/{userId}', [TransactionController::class, "getExtractByUser"]);
    });
});
