<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\PhongBanController;
use App\Http\Controllers\Admin\ChucVuController;
use App\Http\Controllers\Admin\ChamCongController;
use App\Http\Controllers\Admin\DonNghiController;
use App\Http\Controllers\Admin\LoaiNghiController;
use App\Http\Controllers\Admin\BangLuongController;
use App\Http\Controllers\Admin\PhuCapController;
use App\Http\Controllers\Admin\TinTuyenDungController;
use App\Http\Controllers\Admin\UngVienController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý nhân sự
    Route::resource('nguoi-dung', NguoiDungController::class);
    Route::resource('phong-ban', PhongBanController::class);
    Route::resource('chuc-vu', ChucVuController::class);
    
    // Chấm công
    Route::resource('cham-cong', ChamCongController::class);
    Route::resource('tang-ca', TangCaController::class);
    
    // Nghỉ phép
    Route::resource('don-nghi', DonNghiController::class);
    Route::resource('loai-nghi', LoaiNghiController::class);
    
    // Lương thưởng
    Route::resource('bang-luong', BangLuongController::class);
    Route::resource('phu-cap', PhuCapController::class);
    
    // Tuyển dụng
    Route::resource('tin-tuyen-dung', TinTuyenDungController::class);
    Route::resource('ung-vien', UngVienController::class);
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');