<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MunkaController;
use App\Http\Controllers\Api\FuvarozoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('munkak', MunkaController::class);

Route::patch('fuvarozok/{fuvarozo}/statusz', [FuvarozoController::class, 'updateStatus']);
