<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\PhongBanController;
use App\Http\Controllers\Admin\ChucVuController;
use App\Http\Controllers\Admin\ChamCongController;
use App\Http\Controllers\Admin\DonNghiController;
use App\Http\Controllers\Admin\BangLuongController;
use App\Http\Controllers\Admin\PhuCapController;
use App\Http\Controllers\Admin\TinTuyenDungController;
use App\Http\Controllers\Admin\UngVienController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\BaoCaoController;
use App\Http\Controllers\Auth\LoginController;  // ← Dùng cái này

/*
|--------------------------------------------------------------------------
| Authentication Routes (Chung cho tất cả)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Cần đăng nhập và phân quyền)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('role')->group(function () {

    // Dashboard - Tất cả user đã login đều xem được
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/cham-cong', [ChamCongController::class, 'index'])->name('cham-cong.index');

    // Chấm công & Đơn nghỉ - Tất cả nhân viên

    Route::get('/don-nghi', [DonNghiController::class, 'index'])->name('don-nghi');

    // Quản lý nhân sự - Chỉ Admin và Trưởng phòng
    Route::middleware('role:Super Admin,Admin,Trưởng phòng')->group(function () {
        Route::resource('nguoi-dung', NguoiDungController::class);
        Route::resource('phong-ban', PhongBanController::class);
        Route::resource('chuc-vu', ChucVuController::class);
    });

    // Lương thưởng - Chỉ Kế toán và Admin
    Route::middleware('role:Kế toán,Super Admin,Admin')->group(function () {
        Route::resource('bang-luong', BangLuongController::class);
        Route::resource('phu-cap', PhuCapController::class);
        Route::get('/bao-cao-tai-chinh', [BaoCaoController::class, 'index'])->name('bao-cao');
    });

    // Tuyển dụng - Chỉ Admin và Trưởng phòng
    Route::middleware('role:Super Admin,Admin,Trưởng phòng')->group(function () {
        Route::resource('tin-tuyen-dung', TinTuyenDungController::class);
        Route::resource('ung-vien', UngVienController::class);
    });

    // Quản trị hệ thống - Chỉ Admin
    Route::middleware('role:Super Admin,Admin')->group(function () {
        Route::get('/cai-dat', [SettingController::class, 'index'])->name('settings');
        Route::resource('vai-tro', VaiTroController::class);
    });
});
