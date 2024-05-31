<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [LogoutController::class, 'delete']);
        Route::post('/logout-all', [LogoutController::class, 'deleteAll']);

        Route::get('/documents', [DocumentController::class, 'index']);
        Route::post('/documents', [DocumentController::class, 'store']);

        Route::get("/files/{file_path}", FileController::class);
    });

Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);

// display not found message for all other routes
Route::fallback(function () {
    return response()->json([
        'message' => 'Not Found',
    ], 404);
});
