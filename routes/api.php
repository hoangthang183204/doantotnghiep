<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

// Public routes (không cần token)
Route::post('/login', [AuthController::class, 'login']);

// ⭐ THÊM MIDDLEWARE WEB ĐỂ DÙNG SESSION CHO NOTIFICATIONS
Route::middleware(['web'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});

// Protected routes (cần token)
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Admin only
Route::middleware(['auth:api', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Welcome Admin!']);
    });
});

// Trưởng phòng và Admin
Route::middleware(['auth:api', 'role:super_admin,admin,truong_phong'])->group(function () {
    Route::get('/phong-ban/dashboard', function () {
        return response()->json(['message' => 'Welcome Manager!']);
    });
});

// Nhân viên
Route::middleware('auth:api')->group(function () {
    Route::get('/nhan-vien/dashboard', function () {
        return response()->json(['message' => 'Welcome Employee!']);
    });
});