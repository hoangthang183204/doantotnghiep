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
use App\Http\Controllers\Admin\DuyetDonController;
use App\Http\Controllers\Admin\TangCaController;
use App\Http\Controllers\Admin\ThucHienTangCaController;

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

        Route::get('/admin/nguoi-dung/sync-ho-so', [NguoiDungController::class, 'syncHoSo'])
            ->name('admin.nguoi-dung.sync-ho-so');
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
        Route::resource('tin-tuyen-dung', TinTuyenDungController::class);
        // Route::resource('ung-vien', UngVienController::class);
    });

    // Quản trị hệ thống - Chỉ Admin
    Route::middleware('role:Super Admin,Admin')->group(function () {
        // Route::get('/cai-dat', [SettingController::class, 'index'])->name('settings');
        Route::resource('vai-tro', VaiTroController::class);
    });

    // Quản lý loại nghỉ phép
    Route::resource('loai-nghi-phep', LoaiNghiController::class);

    // Route quy định phải nằm trong group này
    Route::get('quy-dinh', [QuyDinhController::class, 'index'])->name('quy-dinh.index');

    // Quản lý duyệt đơn nghỉ phép
    Route::get('/don-nghi', [DonNghiController::class, 'index'])->name('don_nghi.index');

    // Xử lý duyệt/từ chối đơn
    Route::post('/don-nghi/{id}/duyet', [DonNghiController::class, 'capNhatTrangThai'])->name('don_nghi.duyet');


    Route::prefix('hop-dong')->name('hop-dong.')->middleware('role:Super Admin,Admin,HR Hành chính')->group(function () {
        Route::get('/', [HopDongLaoDongController::class, 'index'])->name('index');
        Route::get('/tao-moi', [HopDongLaoDongController::class, 'create'])->name('create');
        Route::post('/tao-moi', [HopDongLaoDongController::class, 'store'])->name('store');
        Route::get('/{id}', [HopDongLaoDongController::class, 'show'])->name('show');
        Route::get('/{id}/sua', [HopDongLaoDongController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HopDongLaoDongController::class, 'update'])->name('update');
        Route::delete('/{id}', [HopDongLaoDongController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/gia-han', [HopDongLaoDongController::class, 'giaHan'])->name('gia-han');
        Route::post('/{id}/thanh-ly', [HopDongLaoDongController::class, 'thanhLy'])->name('thanh-ly');
        Route::post('/{id}/gui-ky', [HopDongLaoDongController::class, 'guiYeuCauKy'])->name('gui-ky');
        Route::get('/sap-het-han', [HopDongLaoDongController::class, 'sapHetHan'])->name('sap-het-han');
        Route::get('/export/excel', [HopDongLaoDongController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [HopDongLaoDongController::class, 'exportPdf'])->name('export.pdf');
    });
});

// Bảng lương
Route::prefix('admin')->name('admin.')->middleware('role')->group(function () {
    Route::get('/bang-luong', [BangLuongController::class, 'index'])->name('bang-luong.index');
    Route::get('/bang-luong/create', [BangLuongController::class, 'create'])->name('bang-luong.create');

    // Tuyển dụng - Duyệt đơn
    Route::get('duyetdon/tuyendung', [DuyetDonController::class, 'index'])->name('duyetdon.tuyendung.index');
    Route::get('duyetdon/tuyendung/{id}', [DuyetDonController::class, 'show'])->name('duyetdon.tuyendung.show');
    Route::post('duyetdon/tuyendung/{id}/duyet', [DuyetDonController::class, 'duyet'])->name('duyetdon.tuyendung.duyet');
    Route::post('duyetdon/tuyendung/{id}/tuchoi', [DuyetDonController::class, 'tuChoi'])->name('duyetdon.tuyendung.tuchoi');



    // Alias for don_nghi route used by sidebar
    Route::post('/bang-luong/tinh', [BangLuongController::class, 'tinhLuong'])->name('bang-luong.tinh');
    Route::get('/bang-luong/{id}', [BangLuongController::class, 'show'])->name('bang-luong.show');
    Route::put('/bang-luong/{id}/duyet', [BangLuongController::class, 'duyet'])->name('bang-luong.duyet');
    Route::delete('/bang-luong/{id}', [BangLuongController::class, 'destroy'])->name('bang-luong.destroy');
});

//danh sách ứng viên
Route::prefix('admin/ung-vien')->name('admin.ung_vien.')->group(function () {

    Route::get('/email-phong-van', [UngVienController::class, 'emailList'])
        ->name('email.index');

    Route::get('/email-phong-van/create', [UngVienController::class, 'createEmail'])
        ->name('email.create');

    Route::post('/email-phong-van/send', [UngVienController::class, 'sendEmail'])
        ->name('email.send');

    // các route còn lại
    Route::get('/', [UngVienController::class, 'index'])->name('index');
    Route::get('/create', [UngVienController::class, 'create'])->name('create');
    Route::post('/store', [UngVienController::class, 'store'])->name('store');

    Route::get('/{id}', [UngVienController::class, 'show'])->name('show');

    Route::put('/{id}', [UngVienController::class, 'update'])->name('update');
    Route::delete('/{id}', [UngVienController::class, 'destroy'])->name('destroy');
});

// =========================================================================
// Tăng ca — Phê duyệt
// =========================================================================
Route::prefix('admin')->name('admin.')->middleware('role')->group(function () {
    Route::get('tang-ca', [TangCaController::class, 'index'])->name('tang-ca.index');
    Route::get('tang-ca/{id}', [TangCaController::class, 'show'])->name('tang-ca.show');
    Route::post('tang-ca/{id}/duyet', [TangCaController::class, 'duyet'])->name('tang-ca.duyet');
    Route::post('tang-ca/{id}/tu-choi', [TangCaController::class, 'tuChoi'])->name('tang-ca.tu-choi');
    Route::post('tang-ca/duyet-hang-loat', [TangCaController::class, 'duyetHangLoat'])->name('tang-ca.duyet-hang-loat');

    // Thực hiện tăng ca 

    Route::get('thuc-hien-tang-ca', [ThucHienTangCaController::class, 'index'])->name('thuc-hien-tang-ca.index');
    Route::get('thuc-hien-tang-ca/{id}', [ThucHienTangCaController::class, 'show'])->name('thuc-hien-tang-ca.show');
    Route::get('thuc-hien-tang-ca/{id}/edit',[ThucHienTangCaController::class, 'edit'])->name('thuc-hien-tang-ca.edit');
    Route::put('thuc-hien-tang-ca/{id}',[ThucHienTangCaController::class, 'update'])->name('thuc-hien-tang-ca.update');
});
