<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::middleware('guest')->group(function () {
    // ===Done===
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');

        // Route::post('/forgot-password', 'forgotPassword');
        // Route::post('/reset-password', 'resetPassword');
    });

    Route::get('/plans', [PlanController::class, 'index']);

    Route::get('/plans/{plan}', [PlanController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/auth/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('/subscriptions', SubscriptionController::class)
        ->except('index', 'update');
});