<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('deposit', [TransactionController::class, "createDeposit"]);

Route::group(['prefix' => 'user'], function() {
    Route::post('', [UserController::class, "create"]);
    Route::get('', [UserController::class, "all"]);
});

Route::group(['prefix' => 'transaction'], function() {
    Route::post('transfer', [TransactionController::class, "createTransfer"]);
    Route::get('extract/{userId}', [TransactionController::class, "getExtractByUser"]);
});
