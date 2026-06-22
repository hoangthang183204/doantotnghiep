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
use App\Http\Controllers\Admin\QuanLyThoiGianController;
use App\Http\Controllers\Admin\LuongController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Employee\DashboardEmployeeController;
use App\Http\Controllers\Employee\ChamCongController as EmployeeChamCongController;
use App\Http\Controllers\Employee\DonNghiController as EmployeeDonNghiController;
use App\Http\Controllers\Employee\HoSoController as EmployeeHoSoController;
use App\Http\Controllers\Employee\TangCaController as EmployeeTangCaController;
use App\Http\Controllers\Employee\YeuCauChinhCongController;
use App\Http\Controllers\Employee\HopDongController;
use App\Http\Controllers\Employee\QuyDinhController as EmployeeQuyDinhController;
use App\Http\Controllers\Admin\TrungTuyenController;

// =============================================
// ROUTE GỐC
// =============================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin', 'Admin'])->exists();
        return redirect($isAdmin ? route('admin.dashboard') : route('employee.dashboard'));
    }
    return redirect()->route('login');
});

// =============================================
// AUTH ROUTES
// =============================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =============================================
// ADMIN ROUTES - 1 KHỐI DUY NHẤT
// =============================================
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ========== DASHBOARD ==========
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
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
            Route::get('ho-so/{id}/view-cv', [HoSoController::class, 'viewCv'])->name('ho-so.view-cv');
            Route::get('/{id}/view-cv', [HoSoController::class, 'viewCv'])->name('view-cv');
            Route::get('/{id}/view-contract', [HoSoController::class, 'viewContract'])->name('view-contract');
            Route::get('/view-contract/{id}', [HoSoController::class, 'viewContract'])->name('view-contract');
        });

        // ========== QUẢN LÝ NGƯỜI DÙNG ==========
        Route::prefix('nguoi-dung')->name('nguoi-dung.')->group(function () {
            Route::get('/', [NguoiDungController::class, 'index'])->name('index');
            Route::get('/sync', [NguoiDungController::class, 'syncHoSo'])->name('sync');
            Route::get('/create', [NguoiDungController::class, 'create'])->name('create');
            Route::post('/', [NguoiDungController::class, 'store'])->name('store');
            Route::get('/{id}', [NguoiDungController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [NguoiDungController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NguoiDungController::class, 'update'])->name('update');
            Route::delete('/{id}', [NguoiDungController::class, 'destroy'])->name('destroy');
        });

        // ========== PHÒNG BAN ==========
        Route::get('/phong-ban', [PhongBanController::class, 'index'])->name('phong-ban.index');
        Route::get('/phong-ban/org-chart', [PhongBanController::class, 'orgChart'])->name('phong-ban.org-chart');
        Route::get('/phong-ban/statistics', [PhongBanController::class, 'statistics'])->name('phong-ban.statistics');
        Route::get('/phong-ban/create', [PhongBanController::class, 'create'])->name('phong-ban.create');
        Route::post('/phong-ban', [PhongBanController::class, 'store'])->name('phong-ban.store');
        Route::get('/phong-ban/{id}', [PhongBanController::class, 'show'])->name('phong-ban.show');
        Route::get('/phong-ban/{id}/edit', [PhongBanController::class, 'edit'])->name('phong-ban.edit');
        Route::put('/phong-ban/{id}', [PhongBanController::class, 'update'])->name('phong-ban.update');
        Route::delete('/phong-ban/{id}', [PhongBanController::class, 'destroy'])->name('phong-ban.destroy');

        // ========== CHỨC VỤ ==========
        Route::get('/chuc-vu/org-chart', [ChucVuController::class, 'orgChart'])->name('chuc-vu.org-chart');
        Route::get('/chuc-vu/statistics', [ChucVuController::class, 'statistics'])->name('chuc-vu.statistics');
        Route::get('/chuc-vu', [ChucVuController::class, 'index'])->name('chuc-vu.index');
        Route::get('/chuc-vu/create', [ChucVuController::class, 'create'])->name('chuc-vu.create');
        Route::post('/chuc-vu', [ChucVuController::class, 'store'])->name('chuc-vu.store');
        Route::get('/chuc-vu/{id}', [ChucVuController::class, 'show'])->name('chuc-vu.show');
        Route::get('/chuc-vu/{id}/edit', [ChucVuController::class, 'edit'])->name('chuc-vu.edit');
        Route::put('/chuc-vu/{id}', [ChucVuController::class, 'update'])->name('chuc-vu.update');
        Route::delete('/chuc-vu/{id}', [ChucVuController::class, 'destroy'])->name('chuc-vu.destroy');


        // ========== HỒ SƠ CÁ NHÂN ==========
        Route::get('/ho-so-ca-nhan', [HoSoCaNhanController::class, 'index'])->name('ho-so-ca-nhan.index');
        Route::put('/ho-so-ca-nhan/update', [HoSoCaNhanController::class, 'update'])->name('ho-so-ca-nhan.update');
        Route::post('/ho-so-ca-nhan/change-password', [HoSoCaNhanController::class, 'changePassword'])->name('ho-so-ca-nhan.change-password');

        Route::get('/chuc-vu/{id}', [ChucVuController::class, 'show'])->name('chuc-vu.show');
        Route::get('/chuc-vu/{id}/edit', [ChucVuController::class, 'edit'])->name('chuc-vu.edit');
        Route::put('/chuc-vu/{id}', [ChucVuController::class, 'update'])->name('chuc-vu.update');
        Route::delete('/chuc-vu/{id}', [ChucVuController::class, 'destroy'])->name('chuc-vu.destroy');


        // ========== CHẤM CÔNG ==========
        Route::get('/cham-cong', [ChamCongController::class, 'index'])->name('cham-cong.index');
        Route::get('/cham-cong/{id}', [ChamCongController::class, 'show'])->name('cham-cong.show');
        Route::get('/cham-cong/export', [ChamCongController::class, 'export'])->name('cham-cong.export');
        Route::post('/cham-cong/bulk-action', [ChamCongController::class, 'bulkAction'])->name('cham-cong.bulk-action');
        Route::post('/cham-cong/{id}/phe-duyet', [ChamCongController::class, 'pheDuyetDonLe'])->name('cham-cong.phe-duyet');

        // ========== ĐƠN NGHỈ ==========
        Route::prefix('don-nghi')->name('don_nghi.')->group(function () {
        Route::get('/', [DonNghiController::class, 'index'])->name('index');
        Route::get('/{id}', [DonNghiController::class, 'show'])->name('show');
        Route::post('/{id}/duyet', [DonNghiController::class, 'capNhatTrangThai'])->name('duyet');
        Route::post('/bulk-action', [DonNghiController::class, 'bulkAction'])->name('bulk');
        });

        // ========== LƯƠNG (BẢNG LƯƠNG THÁNG) ==========
        Route::get('/bang-luong', [BangLuongController::class, 'index'])->name('bang-luong.index');
        Route::get('/bang-luong/create', [BangLuongController::class, 'create'])->name('bang-luong.create');
        Route::post('/bang-luong/tinh', [BangLuongController::class, 'tinhLuong'])->name('bang-luong.tinh');
        Route::get('/bang-luong/{id}', [BangLuongController::class, 'show'])->whereNumber('id')->name('bang-luong.show');
        Route::get('/bang-luong/{id}/nhan-vien/{luongId}', [BangLuongController::class, 'chiTietNhanVien'])->whereNumber(['id', 'luongId'])->name('bang-luong.chi-tiet-nhan-vien');
        Route::put('/bang-luong/{id}/chot', [BangLuongController::class, 'chot'])->whereNumber('id')->name('bang-luong.chot');
        Route::put('/bang-luong/{id}/thanh-toan', [BangLuongController::class, 'thanhToan'])->whereNumber('id')->name('bang-luong.thanh-toan');
        Route::delete('/bang-luong/{id}', [BangLuongController::class, 'destroy'])->whereNumber('id')->name('bang-luong.destroy');

        // ========== PHỤ CẤP ==========
        Route::resource('phu-cap', PhuCapController::class);

        // ========== QUẢN LÝ LƯƠNG ==========

        Route::get('luong/export', [LuongController::class, 'export'])
            ->name('luong.export');

        Route::resource('luong', LuongController::class);

        // ========== TUYỂN DỤNG ==========
        Route::get('/tin-tuyen-dung', [TinTuyenDungController::class, 'index'])->name('tin-tuyen-dung.index');
        Route::get('/tin-tuyen-dung/{id}', [TinTuyenDungController::class, 'show'])->name('tin-tuyen-dung.show');
        Route::get('/tin-tuyen-dung/create', [TinTuyenDungController::class, 'create'])->name('tin-tuyen-dung.create');
        Route::get('/tin-tuyen-dung/create', [TinTuyenDungController::class, 'create'])->name('tin-tuyen-dung.create');
        Route::post('/tin-tuyen-dung', [TinTuyenDungController::class, 'store'])->name('tin-tuyen-dung.store');
        Route::get('/tin-tuyen-dung/{id}/edit', [TinTuyenDungController::class, 'edit'])->name('tin-tuyen-dung.edit');
        Route::put('/tin-tuyen-dung/{id}', [TinTuyenDungController::class, 'update'])->name('tin-tuyen-dung.update');
        Route::delete('/tin-tuyen-dung/{id}', [TinTuyenDungController::class, 'destroy'])->name('tin-tuyen-dung.destroy');

        // ========== VAI TRÒ ==========
        Route::get('/vai-tro', [VaiTroController::class, 'index'])->name('vai-tro.index');
        Route::get('/vai-tro/create', [VaiTroController::class, 'create'])->name('vai-tro.create');
        Route::post('/vai-tro', [VaiTroController::class, 'store'])->name('vai-tro.store');
        Route::get('/vai-tro/{id}', [VaiTroController::class, 'show'])->name('vai-tro.show');
        Route::get('/vai-tro/{id}/edit', [VaiTroController::class, 'edit'])->name('vai-tro.edit');
        Route::put('/vai-tro/{id}', [VaiTroController::class, 'update'])->name('vai-tro.update');
        Route::delete('/vai-tro/{id}', [VaiTroController::class, 'destroy'])->name('vai-tro.destroy');

        // ========== LOẠI NGHỈ PHÉP ==========
        Route::resource('loai-nghi-phep', LoaiNghiController::class);

        // ========== QUY ĐỊNH ==========
        Route::get('/quy-dinh', [QuyDinhController::class, 'index'])->name('quy-dinh.index');
        Route::get('/quy-dinh/edit', [QuyDinhController::class, 'edit'])->name('quydinh.edit');
        Route::post('/quy-dinh/update', [QuyDinhController::class, 'update'])->name('quydinh.update');

        // ========== QUẢN LÝ THỜI GIAN ==========
        Route::get('/quan-ly-thoi-gian', [QuanLyThoiGianController::class, 'index'])->name('quan-ly-thoi-gian.index');
        Route::put('/quan-ly-thoi-gian', [QuanLyThoiGianController::class, 'updateGioLamViec'])->name('quan-ly-thoi-gian.update');

        // ========== HỢP ĐỒNG ==========
        Route::prefix('hop-dong')->group(function () {
            Route::get('/', [HopDongLaoDongController::class, 'index'])->name('hop-dong.index');
            Route::get('/cua-toi', [HopDongLaoDongController::class, 'cuaToi'])->name('hop-dong.cua-toi');
            Route::get('/luu-tru', [HopDongLaoDongController::class, 'luuTru'])->name('hop-dong.luu-tru');
            Route::get('/thong-ke', [HopDongLaoDongController::class, 'thongKe'])->name('hop-dong.thong-ke');
            Route::get('/export', [HopDongLaoDongController::class, 'export'])->name('hop-dong.export');
            Route::get('/tao-moi', [HopDongLaoDongController::class, 'create'])->name('hop-dong.create');
            Route::post('/tao-moi', [HopDongLaoDongController::class, 'store'])->name('hop-dong.store');
            Route::get('/{id}', [HopDongLaoDongController::class, 'show'])->name('hop-dong.show');
            Route::get('/{id}/sua', [HopDongLaoDongController::class, 'edit'])->name('hop-dong.edit');
            Route::put('/{id}', [HopDongLaoDongController::class, 'update'])->name('hop-dong.update');
            Route::put('/{id}', [HopDongLaoDongController::class, 'update'])->name('hop-dong.update');
            Route::delete('/{id}', [HopDongLaoDongController::class, 'destroy'])->name('hop-dong.destroy');
            Route::post('/{id}/gui-ky', [HopDongLaoDongController::class, 'guiKy'])->name('hop-dong.gui-ky');
            Route::post('/{id}/huy', [HopDongLaoDongController::class, 'huy'])->name('hop-dong.huy');
            Route::get('/get-nhan-vien-info/{id}', [HopDongLaoDongController::class, 'getNhanVienInfo'])->name('get-nhan-vien-info');
            Route::post('/hop-dong/tai-ky/{id}', [HopDongLaoDongController::class, 'taiKy'])
                ->name('hop-dong.tai-ky');
        });

        // ========== PHÂN QUYỀN ==========
        Route::prefix('phan-quyen')->name('phan-quyen.')->group(function () {
            Route::get('/', [PhanQuyenController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [PhanQuyenController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PhanQuyenController::class, 'update'])->name('update');
        });

        // ========== YÊU CẦU ĐIỀU CHỈNH CÔNG ==========
        Route::get('/yeu-cau-dieu-chinh-cong', [YeuCauDieuChinhCongAdminController::class, 'index'])->name('yeu-cau-dieu-chinh-cong.index');
        Route::get('/yeu-cau-dieu-chinh-cong/bao-cao', [YeuCauDieuChinhCongAdminController::class, 'baoCao'])->name('yeu-cau-dieu-chinh-cong.bao-cao');
        Route::get('/yeu-cau-dieu-chinh-cong/{id}', [YeuCauDieuChinhCongAdminController::class, 'show'])->name('yeu-cau-dieu-chinh-cong.show');
        Route::post('/yeu-cau-dieu-chinh-cong/{id}/duyet', [YeuCauDieuChinhCongAdminController::class, 'duyet'])->name('yeu-cau-dieu-chinh-cong.duyet');
        Route::post('/yeu-cau-dieu-chinh-cong/duyet-hang-loat', [YeuCauDieuChinhCongAdminController::class, 'duyetHangLoat'])->name('yeu-cau-dieu-chinh-cong.duyet-hang-loat');
        Route::delete('/yeu-cau-dieu-chinh-cong/{id}', [YeuCauDieuChinhCongAdminController::class, 'destroy'])->name('yeu-cau-dieu-chinh-cong.destroy');
        Route::get('/yeu-cau-dieu-chinh-cong/{id}/download', [YeuCauDieuChinhCongAdminController::class, 'downloadFile'])->name('yeu-cau-dieu-chinh-cong.download');

        // ========== TĂNG CA ==========
        Route::get('/tang-ca', [TangCaController::class, 'index'])->name('tang-ca.index');
        Route::get('/tang-ca/{id}', [TangCaController::class, 'show'])->name('tang-ca.show');
        Route::post('/tang-ca/{id}/duyet', [TangCaController::class, 'duyet'])->name('tang-ca.duyet');
        Route::post('/tang-ca/{id}/tu-choi', [TangCaController::class, 'tuChoi'])->name('tang-ca.tu-choi');
        Route::post('/tang-ca/duyet-hang-loat', [TangCaController::class, 'duyetHangLoat'])->name('tang-ca.duyet-hang-loat');

        // ========== THỰC HIỆN TĂNG CA ==========
        Route::get('/thuc-hien-tang-ca', [ThucHienTangCaController::class, 'index'])->name('thuc-hien-tang-ca.index');
        Route::get('/thuc-hien-tang-ca/{id}', [ThucHienTangCaController::class, 'show'])->name('thuc-hien-tang-ca.show');
        Route::get('/thuc-hien-tang-ca/{id}/edit', [ThucHienTangCaController::class, 'edit'])->name('thuc-hien-tang-ca.edit');
        Route::put('/thuc-hien-tang-ca/{id}', [ThucHienTangCaController::class, 'update'])->name('thuc-hien-tang-ca.update');

        // ========== DUYỆT ĐƠN TUYỂN DỤNG ==========
        Route::get('/duyetdon/tuyendung', [DuyetDonController::class, 'index'])->name('duyetdon.tuyendung.index');
        Route::get('/duyetdon/tuyendung/{id}', [DuyetDonController::class, 'show'])->name('duyetdon.tuyendung.show');
        Route::post('/duyetdon/tuyendung/{id}/duyet', [DuyetDonController::class, 'duyet'])->name('duyetdon.tuyendung.duyet');
        Route::post('/duyetdon/tuyendung/{id}/tuchoi', [DuyetDonController::class, 'tuChoi'])->name('duyetdon.tuyendung.tuchoi');

        // ========== ỨNG VIÊN ==========
        Route::prefix('ung-vien')->name('ung_vien.')->group(function () {
            Route::get('/', [UngVienController::class, 'index'])->name('index');
            Route::get('/create', [UngVienController::class, 'create'])->name('create');
            Route::post('/store', [UngVienController::class, 'store'])->name('store');
            Route::get('/{id}', [UngVienController::class, 'show'])->name('show');
            Route::put('/{id}', [UngVienController::class, 'update'])->name('update');
            Route::delete('/{id}', [UngVienController::class, 'destroy'])->name('destroy');
            Route::get('/archived/list', [UngVienController::class, 'archived'])->name('archived');
            Route::post('/{id}/archive', [UngVienController::class, 'archive'])->name('archive');
            Route::post('/{id}/restore', [UngVienController::class, 'restore'])->name('restore');
            Route::get('/email-phong-van', [UngVienController::class, 'emailList'])->name('email.index');
            Route::get('/email-phong-van/create', [UngVienController::class, 'createEmail'])->name('email.create');
            Route::post('/email-phong-van/send', [UngVienController::class, 'sendEmail'])->name('email.send');
        });
        //=========Trúng Tuyển=========//
        Route::prefix('trung-tuyen')->name('trung-tuyen.')->group(function () {
    Route::get('/', [TrungTuyenController::class, 'index'])->name('index');
});
    });

// =============================================
// EMPLOYEE ROUTES
// =============================================
Route::prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [DashboardEmployeeController::class, 'index'])->name('dashboard');

        // ========== HỢP ĐỒNG CỦA TÔI ==========
        Route::get('/hop-dong-cua-toi', [HopDongController::class, 'getHopDongCuaToi'])->name('hop-dong.index');
        Route::patch('/hop-dong/{id}/update-status', [HopDongController::class, 'updateTrangThaiKy'])->name('hopdong.update-status');
        Route::patch('/hop-dong/{id}/tu-choi-ky', [HopDongController::class, 'tuChoiKy'])
            ->name('hop-dong.tu-choi-ky'); 

        // ========== CHẤM CÔNG ==========
        Route::prefix('cham-cong')->name('cham-cong.')->group(function () {
            Route::get('/', [EmployeeChamCongController::class, 'index'])->name('index');
            Route::post('/check-in', [EmployeeChamCongController::class, 'checkIn'])->name('check-in');
            Route::post('/check-out', [EmployeeChamCongController::class, 'checkOut'])->name('check-out');
            Route::get('/history', [EmployeeChamCongController::class, 'history'])->name('history');
        });

        // ========== TĂNG CA ==========
        Route::prefix('tang-ca')->name('tang-ca.')->group(function () {
            Route::get('/', [EmployeeTangCaController::class, 'index'])->name('index');
            Route::get('/create', [EmployeeTangCaController::class, 'create'])->name('create');
            Route::post('/', [EmployeeTangCaController::class, 'store'])->name('store');
            Route::get('/{id}', [EmployeeTangCaController::class, 'show'])->name('show');
            Route::post('/{id}/huy', [EmployeeTangCaController::class, 'huy'])->name('huy');
        });

        // ========== YÊU CẦU CHỈNH CÔNG ==========
        Route::prefix('yeu-cau-chinh-cong')->name('yeu-cau-chinh-cong.')->group(function () {
            Route::get('/', [YeuCauChinhCongController::class, 'index'])->name('index');
            Route::get('/create', [YeuCauChinhCongController::class, 'create'])->name('create');
            Route::post('/', [YeuCauChinhCongController::class, 'store'])->name('store');
            Route::get('/{id}', [YeuCauChinhCongController::class, 'show'])->name('show');
            Route::post('/{id}/huy', [YeuCauChinhCongController::class, 'huy'])->name('huy');
            Route::get('/{id}/download', [YeuCauChinhCongController::class, 'download'])->name('download');
        });

        // ========== ĐƠN NGHỈ PHÉP ==========
        Route::prefix('don-nghi')->name('don-nghi.')->group(function () {
            Route::get('/', [EmployeeDonNghiController::class, 'index'])->name('index');
            Route::get('/create', [EmployeeDonNghiController::class, 'create'])->name('create');
            Route::post('/', [EmployeeDonNghiController::class, 'store'])->name('store');
            Route::get('/{id}', [EmployeeDonNghiController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [EmployeeDonNghiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EmployeeDonNghiController::class, 'update'])->name('update');
            Route::post('/{id}/huy', [EmployeeDonNghiController::class, 'huy'])->name('huy');
        });

        // ========== HỒ SƠ CÁ NHÂN ==========
        Route::prefix('ho-so')->name('ho-so.')->group(function () {
            Route::get('/', [EmployeeHoSoController::class, 'index'])->name('index');
            Route::put('/', [EmployeeHoSoController::class, 'update'])->name('update');
            Route::post('/change-password', [EmployeeHoSoController::class, 'changePassword'])->name('change-password');
        });
    });
// ========== QUY ĐỊNH ==========
Route::get('/quy-dinh', [EmployeeQuyDinhController::class, 'index'])->name('employee.quydinh.index');
