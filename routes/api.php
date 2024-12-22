<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\TrainingClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/settings', [SettingController::class, 'index']);

Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{plan}', [PlanController::class, 'show']);

Route::get('/training-classes', [TrainingClassController::class, 'index']);
Route::get('/training-classes/{id}', [TrainingClassController::class, 'show']);
Route::get('/trainers', [TrainerController::class, 'index']);

Route::middleware('guest')->group(function () {
    // ===Done===
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');

        // Route::post('/forgot-password', 'forgotPassword');
        // Route::post('/reset-password', 'resetPassword');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/auth/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::get('/auth/me', [AuthController::class, 'fetchUser']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::apiResource('/subscriptions', SubscriptionController::class)
        ->except('index', 'update');

    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
});
