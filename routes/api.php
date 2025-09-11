<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function() {
    Route::post('', [UserController::class, "create"]);
    Route::get('', [UserController::class, "all"]);
});
