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
use App\Http\Controllers\Admin\LoaiNghiController;
use App\Http\Controllers\Admin\HoSoController;
use App\Http\Controllers\Admin\QuyDinhController;
use App\Http\Controllers\Auth\LoginController;  // ← Dùng cái này
use App\Http\Controllers\Admin\HopDongLaoDongController;
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

    // HO SO
    Route::get('/ho-so', [HoSoController::class, 'index'])->name('ho-so.index');

    Route::get('/ho-so/{id}/edit', [HoSoController::class, 'edit'])->name('ho-so.edit');

    Route::put('/ho-so/{id}', [HoSoController::class, 'update'])
        ->name('ho-so.update');

    Route::get('/ho-so/{id}', [HoSoController::class, 'show'])->name('ho-so.show');

    // 🔴 NGHỈ VIỆC (FIX)
    Route::post('/ho-so/{id}/resign', [HoSoController::class, 'resign'])
        ->name('ho-so.resign');

    // 🟢 KÍCH HOẠT
    Route::post('/ho-so/{id}/activate', [HoSoController::class, 'activate'])
        ->name('ho-so.activate');

    Route::get('/don-nghi', [DonNghiController::class, 'index'])->name('don-nghi');

    // Quản lý nhân sự - Chỉ Admin và Trưởng phòng
    Route::middleware('role:Super Admin,Admin,Trưởng phòng')->group(function () {
        Route::resource('nguoi-dung', NguoiDungController::class);
        Route::resource('phong-ban', PhongBanController::class);
        Route::resource('chuc-vu', ChucVuController::class);
    });

    // Chấm công & Đơn nghỉ - Tất cả nhân viên
    Route::get(
        'cham-cong/export',
        [ChamCongController::class, 'export']
    )->name('cham-cong.export');
    Route::resource('cham-cong', ChamCongController::class);

    // Lương thưởng - Chỉ Kế toán và Admin
    Route::middleware('role:Kế toán,Super Admin,Admin')->group(function () {
        Route::resource('bang-luong', BangLuongController::class);
        Route::resource('phu-cap', PhuCapController::class);
        // Route::get('/bao-cao-tai-chinh', [BaoCaoController::class, 'index'])->name('bao-cao');
    });

    // Tuyển dụng - Chỉ Admin và Trưởng phòng
    Route::middleware('role:Super Admin,Admin,Trưởng phòng')->group(function () {
        // Route::resource('tin-tuyen-dung', TinTuyenDungController::class);
        Route::resource('ung-vien', UngVienController::class);
        Route::resource('hop-dong', HopDongLaoDongController::class);
    });

    // Quản trị hệ thống - Chỉ Admin
    Route::middleware('role:Super Admin,Admin')->group(function () {
        // Route::get('/cai-dat', [SettingController::class, 'index'])->name('settings');
        // Route::resource('vai-tro', VaiTroController::class);
    });

    // Quản lý loại nghỉ phép
    Route::resource('loai_nghi_phep', LoaiNghiController::class);

    // Route cho chức năng vai trò
    Route::resource('vai-tro', VaiTroController::class);
    // Route cho chức năng quy định
    Route::get('quy_dinh', [QuyDinhController::class, 'index'])->name('quy_dinh.index');
    // Quản lý duyệt đơn nghỉ phép
    Route::get('/don-nghi', [DonNghiController::class, 'index'])->name('don_nghi.index');
    
    // Xử lý duyệt/từ chối đơn
    Route::post('/don-nghi/{id}/duyet', [DonNghiController::class, 'capNhatTrangThai'])->name('don_nghi.duyet');
});


// Bảng lương
Route::prefix('admin')->name('admin.')->middleware('role')->group(function () {
    Route::get('/bang-luong', [BangLuongController::class, 'index'])->name('bang-luong.index');
    Route::get('/bang-luong/create', [BangLuongController::class, 'create'])->name('bang-luong.create');
    Route::post('/bang-luong/tinh', [BangLuongController::class, 'tinhLuong'])->name('bang-luong.tinh');
    Route::get('/bang-luong/{id}', [BangLuongController::class, 'show'])->name('bang-luong.show');
    Route::put('/bang-luong/{id}/duyet', [BangLuongController::class, 'duyet'])->name('bang-luong.duyet');
    Route::delete('/bang-luong/{id}', [BangLuongController::class, 'destroy'])->name('bang-luong.destroy');
});

//danh sách ứng viên
Route::prefix('admin/ung-vien')->name('admin.ung_vien.')->group(function () {
    Route::get('/', [UngVienController::class, 'index'])->name('index');
    Route::get('/{id}', [UngVienController::class, 'show'])->name('show');
    Route::get('/create', [UngVienController::class, 'create'])->name('create');
    Route::post('/store', [UngVienController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [UngVienController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UngVienController::class, 'update'])->name('update');
    Route::delete('/{id}', [UngVienController::class, 'destroy'])->name('destroy');
});
