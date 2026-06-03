<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes (không cần token)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (cần token)
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Trong routes/api.php

// Admin only (vai_tro_id = 1 - super_admin hoặc admin)
Route::middleware(['auth:api', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Welcome Admin!']);
    });
});

// Trưởng phòng và Admin (vai_tro_id = 1,2,3)
Route::middleware(['auth:api', 'role:super_admin,admin,truong_phong'])->group(function () {
    Route::get('/phong-ban/dashboard', function () {
        return response()->json(['message' => 'Welcome Manager!']);
    });
});

// Nhân viên (tất cả user đã login)
Route::middleware('auth:api')->group(function () {
    Route::get('/nhan-vien/dashboard', function () {
        return response()->json(['message' => 'Welcome Employee!']);
    });
});