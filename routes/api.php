<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\AuthenticationController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);

    Route::post('register', [RegisterController::class, 'register']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('user', [AuthenticationController::class, 'authenticated']);

        Route::post('logout', [LoginController::class, 'logout']);
    });
});

Route::group(['prefix' => 'statuses'], function () {
    Route::get('/', [StatusController::class, 'index']);

    Route::get('/{id}', [StatusController::class, 'show']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/', [StatusController::class, 'store']);

        Route::post('/{id}/update', [StatusController::class, 'update']);

        Route::delete('/{id}', [StatusController::class, 'destroy']);
    });
});
