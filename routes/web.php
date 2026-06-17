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
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\LoaiNghiController;
use App\Http\Controllers\Admin\HoSoController;
use App\Http\Controllers\Admin\QuyDinhController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\HopDongLaoDongController;
use App\Http\Controllers\Admin\DuyetDonController;
use App\Http\Controllers\Admin\TangCaController;
use App\Http\Controllers\Admin\ThucHienTangCaController;
use App\Http\Controllers\Admin\YeuCauDieuChinhCongAdminController;
use App\Http\Controllers\Admin\PhanQuyenController;
use App\Http\Controllers\Admin\HoSoCaNhanController;
use App\Http\Controllers\Employee\HopDongController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PHÂN QUYỀN - ĐẶT NGOÀI ĐỂ LUÔN TRUY CẬP (QUAN TRỌNG)
|--------------------------------------------------------------------------
*/
Route::prefix('admin/phan-quyen')->name('admin.phan-quyen.')->group(function () {
    Route::get('/', [PhanQuyenController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [PhanQuyenController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PhanQuyenController::class, 'update'])->name('update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Có middleware role để kiểm tra token JWT
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('role')->group(function () {

    // ========== DASHBOARD ==========
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ========== HỒ SƠ ==========
    Route::prefix('ho-so')->name('ho-so.')->group(function () {
        Route::get('/', [HoSoController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [HoSoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HoSoController::class, 'update'])->name('update');
        Route::get('/{id}', [HoSoController::class, 'show'])->name('show');
        Route::post('/{id}/resign', [HoSoController::class, 'resign'])->name('resign');
        Route::post('/{id}/activate', [HoSoController::class, 'activate'])->name('activate');
        Route::get('/cv/view/{id}', [HoSoController::class, 'viewCv'])->name('cv.view');
    });

    // ========== QUẢN LÝ NGƯỜI DÙNG ==========
    Route::middleware('permission:user.view')->group(function () {
        Route::get('/nguoi-dung', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
        Route::get('/nguoi-dung/{id}', [NguoiDungController::class, 'show'])->whereNumber('id')->name('nguoi-dung.show');
    });

    Route::middleware('permission:user.create')->group(function () {
        Route::get('/nguoi-dung/create', [NguoiDungController::class, 'create'])->name('nguoi-dung.create');
        Route::post('/nguoi-dung', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
    });

    Route::middleware('permission:user.edit')->group(function () {

        Route::get('/nguoi-dung/{id}/edit', [NguoiDungController::class, 'edit'])
            ->whereNumber('id')
            ->name('nguoi-dung.edit');

        Route::put('/nguoi-dung/{id}', [NguoiDungController::class, 'update'])
            ->whereNumber('id')
            ->name('nguoi-dung.update');
    });

    Route::middleware('permission:user.delete')->group(function () {
        Route::delete('/nguoi-dung/{id}', [NguoiDungController::class, 'destroy'])->whereNumber('id')->name('nguoi-dung.destroy');
    });

    Route::middleware('permission:user.reset_password')->group(function () {
        Route::get('/nguoi-dung/sync-ho-so', [NguoiDungController::class, 'syncHoSo'])->name('nguoi-dung.sync-ho-so');
    });

    // ========== PHÒNG BAN ==========

    Route::middleware('permission:department.create')->group(function () {
        Route::get('/phong-ban/create', [PhongBanController::class, 'create'])->name('phong-ban.create');
        Route::post('/phong-ban', [PhongBanController::class, 'store'])->name('phong-ban.store');
    });

    Route::middleware('permission:department.view')->group(function () {
        Route::get('/phong-ban', [PhongBanController::class, 'index'])->name('phong-ban.index');
        Route::get('/phong-ban/{id}', [PhongBanController::class, 'show'])->name('phong-ban.show');
    });

    Route::middleware('permission:department.edit')->group(function () {
        Route::get('/phong-ban/{id}/edit', [PhongBanController::class, 'edit'])->name('phong-ban.edit');
        Route::put('/phong-ban/{id}', [PhongBanController::class, 'update'])->name('phong-ban.update');
    });

    Route::middleware('permission:department.delete')->group(function () {
        Route::delete('/phong-ban/{id}', [PhongBanController::class, 'destroy'])->name('phong-ban.destroy');
    });

    // ========== CHỨC VỤ ==========
    Route::middleware('permission:chucvu.view')->group(function () {
        Route::get('/chuc-vu', [ChucVuController::class, 'index'])->name('chuc-vu.index');
    });

    Route::middleware('permission:chucvu.create')->group(function () {
        Route::get('/chuc-vu/create', [ChucVuController::class, 'create'])->name('chuc-vu.create');
        Route::post('/chuc-vu', [ChucVuController::class, 'store'])->name('chuc-vu.store');
    });
    Route::middleware('permission:chucvu.view')->group(function () {
        Route::get('/chuc-vu/{id}', [ChucVuController::class, 'show'])->name('chuc-vu.show');
    });

    Route::middleware('permission:chucvu.edit')->group(function () {
        Route::get('/chuc-vu/{id}/edit', [ChucVuController::class, 'edit'])->name('chuc-vu.edit');
        Route::put('/chuc-vu/{id}', [ChucVuController::class, 'update'])->name('chuc-vu.update');
    });

    Route::middleware('permission:chucvu.delete')->group(function () {
        Route::delete('/chuc-vu/{id}', [ChucVuController::class, 'destroy'])->name('chuc-vu.destroy');
    });
    // ========== HỒ SƠ CÁ NHÂN ==========

    Route::get('/ho-so-ca-nhan', [HoSoCaNhanController::class, 'index'])->name('ho-so-ca-nhan.index');

    // Thêm 2 route mới này:
    Route::put('/ho-so-ca-nhan/update', [HoSoCaNhanController::class, 'update'])->name('ho-so-ca-nhan.update');
    Route::post('/ho-so-ca-nhan/change-password', [HoSoCaNhanController::class, 'changePassword'])->name('ho-so-ca-nhan.change-password');

    // ========== CHẤM CÔNG ==========
    Route::middleware('permission:attendance.index')->group(function () {
        Route::get('/cham-cong', [ChamCongController::class, 'index'])->name('cham-cong.index');
        Route::get('/cham-cong/{id}', [ChamCongController::class, 'show'])->name('cham-cong.show');
    });

    Route::middleware('permission:attendance.export')->group(function () {
        Route::get('/cham-cong/export', [ChamCongController::class, 'export'])->name('cham-cong.export');
    });

    Route::middleware('permission:attendance.overtime_approve')->group(function () {
        Route::post('/cham-cong/bulk-action', [ChamCongController::class, 'bulkAction'])->name('cham-cong.bulk-action');
    });

    // ========== ĐƠN NGHỈ ==========
    Route::middleware('permission:leave.approve')->group(function () {
        Route::get('/don-nghi', [DonNghiController::class, 'index'])->name('don_nghi.index');
        Route::post('/don-nghi/{id}/duyet', [DonNghiController::class, 'capNhatTrangThai'])->name('don_nghi.duyet');
    });

    // ========== LƯƠNG ==========
    Route::middleware('permission:salary.view')->group(function () {
        Route::get('/bang-luong', [BangLuongController::class, 'index'])->name('bang-luong.index');
        Route::get('/bang-luong/{id}', [BangLuongController::class, 'show'])->name('bang-luong.show');
    });

    Route::middleware('permission:salary.create')->group(function () {
        Route::get('/bang-luong/create', [BangLuongController::class, 'create'])->name('bang-luong.create');
        Route::post('/bang-luong', [BangLuongController::class, 'store'])->name('bang-luong.store');
    });

    Route::middleware('permission:salary.calculate')->group(function () {
        Route::post('/bang-luong/tinh', [BangLuongController::class, 'tinhLuong'])->name('bang-luong.tinh');
    });

    Route::middleware('permission:salary.approve')->group(function () {
        Route::put('/bang-luong/{id}/duyet', [BangLuongController::class, 'duyet'])->name('bang-luong.duyet');
    });

    Route::middleware('permission:salary.delete')->group(function () {
        Route::delete('/bang-luong/{id}', [BangLuongController::class, 'destroy'])->name('bang-luong.destroy');
    });

    // ========== PHỤ CẤP ==========
    Route::middleware('permission:salary.allowance')->group(function () {
        Route::resource('phu-cap', PhuCapController::class);
    });

    // ========== TUYỂN DỤNG ==========
    Route::middleware('permission:recruitment.candidate')->group(function () {
        Route::get('/tin-tuyen-dung', [TinTuyenDungController::class, 'index'])->name('tin-tuyen-dung.index');
        Route::get('/tin-tuyen-dung/{id}', [TinTuyenDungController::class, 'show'])->name('tin-tuyen-dung.show');
    });

    Route::middleware('permission:recruitment.candidate_create')->group(function () {
        Route::get('/tin-tuyen-dung/create', [TinTuyenDungController::class, 'create'])->name('tin-tuyen-dung.create');
        Route::post('/tin-tuyen-dung', [TinTuyenDungController::class, 'store'])->name('tin-tuyen-dung.store');
    });

    Route::middleware('permission:recruitment.candidate_edit')->group(function () {
        Route::get('/tin-tuyen-dung/{id}/edit', [TinTuyenDungController::class, 'edit'])->name('tin-tuyen-dung.edit');
        Route::put('/tin-tuyen-dung/{id}', [TinTuyenDungController::class, 'update'])->name('tin-tuyen-dung.update');
    });

    Route::middleware('permission:recruitment.candidate_delete')->group(function () {
        Route::delete('/tin-tuyen-dung/{id}', [TinTuyenDungController::class, 'destroy'])->name('tin-tuyen-dung.destroy');
    });

    // ========== VAI TRÒ ==========


    Route::middleware('permission:role.create')->group(function () {
        Route::get('/vai-tro/create', [VaiTroController::class, 'create'])->name('vai-tro.create');
        Route::post('/vai-tro', [VaiTroController::class, 'store'])->name('vai-tro.store');
    });

    Route::middleware('permission:role.edit')->group(function () {
        Route::get('/vai-tro/{id}/edit', [VaiTroController::class, 'edit'])->name('vai-tro.edit');
        Route::put('/vai-tro/{id}', [VaiTroController::class, 'update'])->name('vai-tro.update');
    });

    Route::middleware('permission:role.delete')->group(function () {
        Route::delete('/vai-tro/{id}', [VaiTroController::class, 'destroy'])->name('vai-tro.destroy');
    });
    Route::middleware('permission:role.view')->group(function () {
        Route::get('/vai-tro', [VaiTroController::class, 'index'])->name('vai-tro.index');
        Route::get('/vai-tro/{id}', [VaiTroController::class, 'show'])->name('vai-tro.show');
    });

    // ========== LOẠI NGHỈ PHÉP ==========
    Route::middleware('permission:leave_type.view')->group(function () {
        Route::resource('loai-nghi-phep', LoaiNghiController::class);
    });

    // ========== QUY ĐỊNH ==========
    Route::middleware('permission:regulation.view')->group(function () {
        Route::get('/quy-dinh', [QuyDinhController::class, 'index'])->name('quy-dinh.index');
    });

    Route::middleware('permission:regulation.edit')->group(function () {
        Route::put('/quy-dinh', [QuyDinhController::class, 'update'])->name('quy-dinh.update');
    });

    Route::prefix('hop-dong')->group(function () {

        Route::get('/cua-toi', [HopDongLaoDongController::class, 'cuaToi'])->name('hop-dong.cua-toi');
        Route::get('/luu-tru', [HopDongLaoDongController::class, 'luuTru'])->name('hop-dong.luu-tru');
        Route::get('/thong-ke', [HopDongLaoDongController::class, 'thongKe'])->name('hop-dong.thong-ke');
        Route::get('/export', [HopDongLaoDongController::class, 'export'])->name('hop-dong.export');


        Route::get('/', [HopDongLaoDongController::class, 'index'])->name('hop-dong.index');

        // Đưa lên trước
        Route::get('/tao-moi', [HopDongLaoDongController::class, 'create'])
            ->name('hop-dong.create');

        Route::post('/tao-moi', [HopDongLaoDongController::class, 'store'])
            ->name('hop-dong.store');

        // Để xuống dưới cùng
        Route::get('/{id}', [HopDongLaoDongController::class, 'show'])
            ->name('hop-dong.show');

        Route::get('/{id}/sua', [HopDongLaoDongController::class, 'edit'])
            ->name('hop-dong.edit');

        Route::put('/{id}', [HopDongLaoDongController::class, 'update'])
            ->name('hop-dong.update');

        Route::delete('/{id}', [HopDongLaoDongController::class, 'destroy'])
            ->name('hop-dong.destroy');

        Route::post('/{id}/gui-ky', [HopDongLaoDongController::class, 'guiKy'])
            ->name('hop-dong.gui-ky');

        Route::post('/{id}/huy', [HopDongLaoDongController::class, 'huy'])
            ->name('hop-dong.huy');
    });

    // ========== YÊU CẦU ĐIỀU CHỈNH CÔNG ==========
    Route::middleware('permission:attendance.adjustment_approve')->group(function () {
        Route::get('/yeu-cau-dieu-chinh-cong', [YeuCauDieuChinhCongAdminController::class, 'index'])->name('yeu-cau-dieu-chinh-cong.index');
        Route::get('/yeu-cau-dieu-chinh-cong/bao-cao', [YeuCauDieuChinhCongAdminController::class, 'baoCao'])->name('yeu-cau-dieu-chinh-cong.bao-cao');
        Route::get('/yeu-cau-dieu-chinh-cong/export-bao-cao', [YeuCauDieuChinhCongAdminController::class, 'exportBaoCao'])->name('yeu-cau-dieu-chinh-cong.export-bao-cao');
        Route::get('/yeu-cau-dieu-chinh-cong/{id}', [YeuCauDieuChinhCongAdminController::class, 'show'])->name('yeu-cau-dieu-chinh-cong.show');
        Route::post('/yeu-cau-dieu-chinh-cong/{id}/duyet', [YeuCauDieuChinhCongAdminController::class, 'duyet'])->name('yeu-cau-dieu-chinh-cong.duyet');
        Route::post('/yeu-cau-dieu-chinh-cong/duyet-hang-loat', [YeuCauDieuChinhCongAdminController::class, 'duyetHangLoat'])->name('yeu-cau-dieu-chinh-cong.duyet-hang-loat');
        Route::delete('/yeu-cau-dieu-chinh-cong/{id}', [YeuCauDieuChinhCongAdminController::class, 'destroy'])->name('yeu-cau-dieu-chinh-cong.destroy');
        Route::get('/yeu-cau-dieu-chinh-cong/{id}/download', [YeuCauDieuChinhCongAdminController::class, 'downloadFile'])->name('yeu-cau-dieu-chinh-cong.download');
    });

    // ========== TĂNG CA ==========
    Route::middleware('permission:attendance.overtime_approve')->group(function () {
        Route::get('/tang-ca', [TangCaController::class, 'index'])->name('tang-ca.index');
        Route::get('/tang-ca/{id}', [TangCaController::class, 'show'])->name('tang-ca.show');
        Route::post('/tang-ca/{id}/duyet', [TangCaController::class, 'duyet'])->name('tang-ca.duyet');
        Route::post('/tang-ca/{id}/tu-choi', [TangCaController::class, 'tuChoi'])->name('tang-ca.tu-choi');
        Route::post('/tang-ca/duyet-hang-loat', [TangCaController::class, 'duyetHangLoat'])->name('tang-ca.duyet-hang-loat');
    });

    // ========== THỰC HIỆN TĂNG CA ==========
    Route::middleware('permission:attendance.overtime')->group(function () {
        Route::get('/thuc-hien-tang-ca', [ThucHienTangCaController::class, 'index'])->name('thuc-hien-tang-ca.index');
        Route::get('/thuc-hien-tang-ca/{id}', [ThucHienTangCaController::class, 'show'])->name('thuc-hien-tang-ca.show');
        Route::get('/thuc-hien-tang-ca/{id}/edit', [ThucHienTangCaController::class, 'edit'])->name('thuc-hien-tang-ca.edit');
        Route::put('/thuc-hien-tang-ca/{id}', [ThucHienTangCaController::class, 'update'])->name('thuc-hien-tang-ca.update');
    });

    // ========== DUYỆT ĐƠN TUYỂN DỤNG ==========
    Route::middleware('permission:approval.recruitment')->group(function () {
        Route::get('/duyetdon/tuyendung', [DuyetDonController::class, 'index'])->name('duyetdon.tuyendung.index');
        Route::get('/duyetdon/tuyendung/{id}', [DuyetDonController::class, 'show'])->name('duyetdon.tuyendung.show');
        Route::post('/duyetdon/tuyendung/{id}/duyet', [DuyetDonController::class, 'duyet'])->name('duyetdon.tuyendung.duyet');
        Route::post('/duyetdon/tuyendung/{id}/tuchoi', [DuyetDonController::class, 'tuChoi'])->name('duyetdon.tuyendung.tuchoi');
    });
});

// ========== ỨNG VIÊN (Public - Không cần middleware role) ==========
Route::prefix('admin/ung-vien')->name('admin.ung_vien.')->group(function () {
    Route::get('/email-phong-van', [UngVienController::class, 'emailList'])->name('email.index');
    Route::get('/email-phong-van/create', [UngVienController::class, 'createEmail'])->name('email.create');
    Route::post('/email-phong-van/send', [UngVienController::class, 'sendEmail'])->name('email.send');
    Route::get('/', [UngVienController::class, 'index'])->name('index');
    Route::get('/create', [UngVienController::class, 'create'])->name('create');
    Route::post('/store', [UngVienController::class, 'store'])->name('store');
    Route::get('/{id}', [UngVienController::class, 'show'])->name('show');
    Route::put('/{id}', [UngVienController::class, 'update'])->name('update');
    Route::delete('/{id}', [UngVienController::class, 'destroy'])->name('destroy');
    Route::get('/archived/list', [UngVienController::class, 'archived'])->name('archived');
    Route::post('/{id}/archive', [UngVienController::class, 'archive'])->name('archive');
    Route::post('/{id}/restore', [UngVienController::class, 'restore'])->name('restore');
});




use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Employee\DashboardEmployeeController;
use App\Http\Controllers\Employee\ChamCongController as EmployeeChamCongController;
use App\Http\Controllers\Employee\DonNghiController as EmployeeDonNghiController;
use App\Http\Controllers\Employee\HoSoController as EmployeeHoSoController;

// =============================================
// AUTH ROUTES
// =============================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin', 'Admin'])->exists();
        return redirect($isAdmin ? route('admin.dashboard') : route('employee.dashboard'));
    }
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =============================================
// ADMIN ROUTES - Chỉ Admin mới vào được
// =============================================
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Các route admin khác...
        Route::get('/cham-cong', [App\Http\Controllers\Admin\ChamCongController::class, 'index'])->name('cham-cong.index');
        Route::get('/bang-luong', [App\Http\Controllers\Admin\BangLuongController::class, 'index'])->name('bang-luong.index');
        Route::get('/ho-so', [App\Http\Controllers\Admin\HoSoController::class, 'index'])->name('ho-so.index');
        Route::get('/nguoi-dung', [App\Http\Controllers\Admin\NguoiDungController::class, 'index'])->name('nguoi-dung.index');
        Route::get('/phong-ban', [App\Http\Controllers\Admin\PhongBanController::class, 'index'])->name('phong-ban.index');
        Route::get('/chuc-vu', [App\Http\Controllers\Admin\ChucVuController::class, 'index'])->name('chuc-vu.index');
        Route::get('/vai-tro', [App\Http\Controllers\Admin\VaiTroController::class, 'index'])->name('vai-tro.index');
        Route::get('/phan-quyen', [App\Http\Controllers\Admin\PhanQuyenController::class, 'index'])->name('phan-quyen.index');
        Route::get('/hop-dong', [App\Http\Controllers\Admin\HopDongLaoDongController::class, 'index'])->name('hop-dong.index');
        Route::get('/ung-vien', [App\Http\Controllers\Admin\UngVienController::class, 'index'])->name('ung-vien.index');
        Route::get('/tin-tuyen-dung', [App\Http\Controllers\Admin\TinTuyenDungController::class, 'index'])->name('tin-tuyen-dung.index');
        Route::get('/duyet-don', [App\Http\Controllers\Admin\DuyetDonController::class, 'index'])->name('duyet-don.index');
        Route::get('/don-nghi', [App\Http\Controllers\Admin\DonNghiController::class, 'index'])->name('don-nghi.index');
        Route::get('/loai-nghi-phep', [App\Http\Controllers\Admin\LoaiNghiController::class, 'index'])->name('loai-nghi-phep.index');
        Route::get('/phu-cap', [App\Http\Controllers\Admin\PhuCapController::class, 'index'])->name('phu-cap.index');
        Route::get('/tang-ca', [App\Http\Controllers\Admin\TangCaController::class, 'index'])->name('tang-ca.index');
        Route::get('/thuc-hien-tang-ca', [App\Http\Controllers\Admin\ThucHienTangCaController::class, 'index'])->name('thuc-hien-tang-ca.index');
        Route::get('/yeu-cau-dieu-chinh-cong', [App\Http\Controllers\Admin\YeuCauDieuChinhCongAdminController::class, 'index'])->name('yeu-cau-dieu-chinh-cong.index');
        Route::get('/quy-dinh', [App\Http\Controllers\Admin\QuyDinhController::class, 'index'])->name('quy-dinh.index');
        Route::get('/ho-so-ca-nhan', [App\Http\Controllers\Admin\HoSoCaNhanController::class, 'index'])->name('ho-so-ca-nhan.index');
    });

// =============================================
// EMPLOYEE ROUTES - Chỉ Nhân viên mới vào được
// =============================================
Route::prefix('employee')
    ->middleware(['auth', 'employee'])
    ->name('employee.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardEmployeeController::class, 'index'])->name('dashboard');
        // Hợp Đồng của tôi
        Route::get('/hop-dong-cua-toi', [HopDongController::class, 'getHopDongCuaToi'])
            ->name('hop-dong.index');
            Route::patch('/hop-dong/{id}/update-status', [HopDongController::class, 'updateTrangThaiKy'])->name('hopdong.update-status');
        // Chấm công
        Route::prefix('cham-cong')->name('cham-cong.')->group(function () {
            Route::get('/', [EmployeeChamCongController::class, 'index'])->name('index');
            Route::post('/check-in', [EmployeeChamCongController::class, 'checkIn'])->name('check-in');
            Route::post('/check-out', [EmployeeChamCongController::class, 'checkOut'])->name('check-out');
            Route::get('/history', [EmployeeChamCongController::class, 'history'])->name('history');
        });

        // Đơn nghỉ phép
        Route::prefix('don-nghi')->name('don-nghi.')->group(function () {
            Route::get('/', [EmployeeDonNghiController::class, 'index'])->name('index');
            Route::get('/create', [EmployeeDonNghiController::class, 'create'])->name('create');
            Route::post('/', [EmployeeDonNghiController::class, 'store'])->name('store');
            Route::get('/{id}', [EmployeeDonNghiController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [EmployeeDonNghiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EmployeeDonNghiController::class, 'update'])->name('update');
            Route::post('/{id}/huy', [EmployeeDonNghiController::class, 'huy'])->name('huy');
        });

        // Hồ sơ cá nhân
        Route::prefix('ho-so')->name('ho-so.')->group(function () {
            Route::get('/', [EmployeeHoSoController::class, 'index'])->name('index');
            Route::put('/', [EmployeeHoSoController::class, 'update'])->name('update');
            Route::post('/change-password', [EmployeeHoSoController::class, 'changePassword'])->name('change-password');
        });

        Route::prefix('cham-cong')
            ->name('cham-cong.')
            ->group(function () {
                Route::get('/', [EmployeeChamCongController::class, 'index'])->name('index');
                Route::post('/check-in', [EmployeeChamCongController::class, 'checkIn'])
                    ->middleware(['attendance.location'])->name('check-in');
                Route::post('/check-out', [EmployeeChamCongController::class, 'checkOut'])
                    ->middleware(['attendance.location'])->name('check-out');
                Route::get('/history', [EmployeeChamCongController::class, 'history'])->name('history');
            });
    });
