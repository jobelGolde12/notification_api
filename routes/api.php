<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/notifications', [NotificationController::class, 'index']);        // GET all
Route::post('/notifications', [NotificationController::class, 'store']);       // POST create
Route::get('/notifications/{id}', [NotificationController::class, 'show']);    // GET one
Route::put('/notifications/{id}', [NotificationController::class, 'update']);  // PUT update
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']); // DELETE