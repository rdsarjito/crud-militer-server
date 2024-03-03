<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MilitariesController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('militaries', [MilitariesController::class, 'index']);
Route::post('militaries', [MilitariesController::class, 'store']);
Route::get('militaries/{id}', [MilitariesController::class, 'show']);
Route::post('militaries/{id}/update', [MilitariesController::class, 'update']);
Route::delete('militaries/{id}/delete', [MilitariesController::class, 'delete']);
Route::get('militaries/image/{imageName}', [MilitariesController::class, 'getImage']);