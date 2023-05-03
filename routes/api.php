<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users',[UserController::class,'index']);
Route::middleware('auth:sanctum')->get('clients', [ClientController::class, 'index']);
