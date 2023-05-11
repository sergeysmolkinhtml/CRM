<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\V1\Admin\ProjectAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'api.'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::middleware('auth:sanctum')->get('clients', [ClientController::class, 'index']);

    Route::namespace('Api\V1\Admin')->apiResource('projects', ProjectAPIController::class);

});


