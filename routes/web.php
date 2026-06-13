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
    });

    // ========== QUẢN LÝ NGƯỜI DÙNG ==========
    Route::middleware('permission:user.view')->group(function () {
        Route::get('/nguoi-dung', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
        Route::get('/nguoi-dung/{id}', [NguoiDungController::class, 'show'])->name('nguoi-dung.show');
    });

    Route::middleware('permission:user.create')->group(function () {
        Route::get('/nguoi-dung/create', [NguoiDungController::class, 'create'])->name('nguoi-dung.create');
        Route::post('/nguoi-dung', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
    });

    Route::middleware('permission:user.edit')->group(function () {
        Route::get('/nguoi-dung/{id}/edit', [NguoiDungController::class, 'edit'])->name('nguoi-dung.edit');
        Route::put('/nguoi-dung/{id}', [NguoiDungController::class, 'update'])->name('nguoi-dung.update');
    });

    Route::middleware('permission:user.delete')->group(function () {
        Route::delete('/nguoi-dung/{id}', [NguoiDungController::class, 'destroy'])->name('nguoi-dung.destroy');
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
    Route::middleware('permission:role.view')->group(function () {
        Route::get('/vai-tro', [VaiTroController::class, 'index'])->name('vai-tro.index');
        Route::get('/vai-tro/{id}', [VaiTroController::class, 'show'])->name('vai-tro.show');
    });

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

    // ========== HỢP ĐỒNG ==========
    Route::middleware('permission:contract.view')->group(function () {
        Route::get('/hop-dong', [HopDongLaoDongController::class, 'index'])->name('hop-dong.index');
        Route::get('/hop-dong/{id}', [HopDongLaoDongController::class, 'show'])->name('hop-dong.show');
        Route::get('/hop-dong/sap-het-han', [HopDongLaoDongController::class, 'sapHetHan'])->name('hop-dong.sap-het-han');
    });

    Route::middleware('permission:contract.create')->group(function () {
        Route::get('/hop-dong/tao-moi', [HopDongLaoDongController::class, 'create'])->name('hop-dong.create');
        Route::post('/hop-dong/tao-moi', [HopDongLaoDongController::class, 'store'])->name('hop-dong.store');
    });

    Route::middleware('permission:contract.edit')->group(function () {
        Route::get('/hop-dong/{id}/sua', [HopDongLaoDongController::class, 'edit'])->name('hop-dong.edit');
        Route::put('/hop-dong/{id}', [HopDongLaoDongController::class, 'update'])->name('hop-dong.update');
    });

    Route::middleware('permission:contract.delete')->group(function () {
        Route::delete('/hop-dong/{id}', [HopDongLaoDongController::class, 'destroy'])->name('hop-dong.destroy');
    });

    Route::middleware('permission:contract.renew')->group(function () {
        Route::post('/hop-dong/{id}/gia-han', [HopDongLaoDongController::class, 'giaHan'])->name('hop-dong.gia-han');
    });

    Route::middleware('permission:contract.terminate')->group(function () {
        Route::post('/hop-dong/{id}/thanh-ly', [HopDongLaoDongController::class, 'thanhLy'])->name('hop-dong.thanh-ly');
    });

    Route::middleware('permission:contract.sign')->group(function () {
        Route::post('/hop-dong/{id}/gui-ky', [HopDongLaoDongController::class, 'guiYeuCauKy'])->name('hop-dong.gui-ky');
    });

    Route::middleware('permission:contract.export')->group(function () {
        Route::get('/hop-dong/export/excel', [HopDongLaoDongController::class, 'exportExcel'])->name('hop-dong.export.excel');
        Route::get('/hop-dong/export/pdf', [HopDongLaoDongController::class, 'exportPdf'])->name('hop-dong.export.pdf');
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
});
