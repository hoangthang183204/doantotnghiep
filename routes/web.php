<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\PhongBanController;
use App\Http\Controllers\Admin\ChucVuController;
use App\Http\Controllers\Admin\ChamCongController;
use App\Http\Controllers\Admin\DonNghiController;
use App\Http\Controllers\Admin\BangLuongController;
use App\Http\Controllers\Admin\KhauTruKhacController;
use App\Http\Controllers\Admin\ThongKeLuongController;
use App\Http\Controllers\Admin\ChungChiNhanVienController;
use App\Http\Controllers\Admin\DaoTaoController;
use App\Http\Controllers\Admin\PhuCapController;
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
use App\Http\Controllers\Admin\KhenThuongKyLuatController;
use App\Http\Controllers\Admin\TangLuongController;
use App\Http\Controllers\Employee\BangLuongController as EmployeeBangLuongController;
use App\Http\Controllers\Employee\DashboardEmployeeController;
use App\Http\Controllers\Employee\ChamCongController as EmployeeChamCongController;
use App\Http\Controllers\Employee\DonNghiController as EmployeeDonNghiController;
use App\Http\Controllers\Employee\HoSoController as EmployeeHoSoController;
use App\Http\Controllers\Employee\TangCaController as EmployeeTangCaController;
use App\Http\Controllers\Employee\YeuCauChinhCongController;
use App\Http\Controllers\Employee\HopDongController;
use App\Http\Controllers\Employee\UngLuongController;
use App\Http\Controllers\Employee\QuyDinhController as EmployeeQuyDinhController;
use App\Http\Controllers\Api\NotificationController;
use App\Models\DonXinNghi;
use App\Models\NguoiDung;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\TruongPhong\DashboardTruongPhongController;

// =============================================
// ⭐⭐ ROUTE DUYỆT ĐƠN DÙNG CHUNG ⭐⭐
// =============================================
use App\Http\Controllers\DuyetDonController as DuyetDonChungController;
use App\Http\Controllers\TruongPhong\BaoCaoController;
use App\Http\Controllers\TruongPhong\NhanVienController;

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
// ⭐⭐ ROUTE DUYỆT ĐƠN - DÙNG CHUNG CHO ADMIN VÀ TRƯỞNG PHÒNG ⭐⭐
// =============================================
Route::middleware(['auth'])->group(function () {
    Route::prefix('duyet-don')->name('duyet-don.')->group(function () {
        Route::get('/', [DuyetDonChungController::class, 'index'])->name('index');
        Route::get('/{id}', [DuyetDonChungController::class, 'show'])->name('show');
        Route::post('/{id}/duyet', [DuyetDonChungController::class, 'duyet'])->name('duyet');
        Route::post('/{id}/tu-choi', [DuyetDonChungController::class, 'tuChoi'])->name('tu-choi');
        Route::post('/duyet-hang-loat', [DuyetDonChungController::class, 'duyetHangLoat'])->name('duyet-hang-loat');
    });
});

Route::prefix('duyet-tang-ca')->name('duyet-tang-ca.')->group(function () {
    Route::get('/', [TangCaController::class, 'index'])->name('index');
    Route::get('/{id}', [TangCaController::class, 'show'])->name('show');
    Route::post('/{id}/duyet', [TangCaController::class, 'duyet'])->name('duyet');
    Route::post('/{id}/tu-choi', [TangCaController::class, 'tuChoi'])->name('tu-choi');
    Route::post('/duyet-hang-loat', [TangCaController::class, 'duyetHangLoat'])->name('duyet-hang-loat');
});

// =============================================
// ADMIN ROUTES - 1 KHỐI DUY NHẤT
// =============================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        // ========== API NOTIFICATIONS (KHÔNG CẦN PHÂN QUYỀN) ==========
        Route::get('/api/notifications', function () {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['data' => [], 'unread_count' => 0]);
            }
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($notification) {
                    $data = $notification->data;
                    if (is_string($data)) {
                        $data = json_decode($data, true);
                    }
                    return [
                        'id' => $notification->id,
                        'data' => $data,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at,
                    ];
                });
            return response()->json([
                'data' => $notifications,
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        })->name('api.notifications');

        Route::get('/api/notifications/unread-count', function () {
            $user = auth()->user();
            return response()->json(['count' => $user ? $user->unreadNotifications()->count() : 0]);
        });

        Route::post('/api/notifications/{id}/mark-as-read', function ($id) {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false], 401);
            }
            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();
            return response()->json(['success' => true]);
        });

        Route::post('/api/notifications/mark-all-as-read', function () {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false], 401);
            }
            $user->unreadNotifications()->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        });

        // ========== DASHBOARD (AI CŨNG XEM ĐƯỢC) ==========
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ========== HỒ SƠ - CHỈ HR VÀ ADMIN ==========
        Route::prefix('ho-so')->name('ho-so.')->middleware(['CheckPermission:hoso.index'])->group(function () {
            Route::get('/', [HoSoController::class, 'index'])->name('index');
            Route::get('/create', [HoSoController::class, 'create'])->name('create');
            Route::get('/template', [HoSoController::class, 'downloadTemplate'])->name('template');

            Route::get('/export', [HoSoController::class, 'export'])->name('export');
            Route::post('/import', [HoSoController::class, 'import'])->name('import');
            Route::get('/{id}/edit', [HoSoController::class, 'edit'])->name('edit')->middleware('CheckPermission:hoso.edit');
            Route::put('/{id}', [HoSoController::class, 'update'])->name('update')->middleware('CheckPermission:hoso.edit');
            Route::get('/{id}', [HoSoController::class, 'show'])->name('show')->middleware('CheckPermission:hoso.show');
            Route::post('/{id}/resign', [HoSoController::class, 'resign'])->name('resign')->middleware('CheckPermission:hoso.edit');
            Route::post('/{id}/activate', [HoSoController::class, 'activate'])->name('activate')->middleware('CheckPermission:hoso.edit');
            Route::get('/cv/view/{id}', [HoSoController::class, 'viewCv'])->name('cv.view')->middleware('CheckPermission:hoso.show');
            Route::get('/{id}/view-cv', [HoSoController::class, 'viewCv'])->name('view-cv')->middleware('CheckPermission:hoso.show');
            Route::get('/{id}/view-contract', [HoSoController::class, 'viewContract'])->name('view-contract')->middleware('CheckPermission:hoso.show');
            Route::post('/{id}/resign', [HoSoController::class, 'resign'])->name('resign');
            Route::post('/{id}/activate', [HoSoController::class, 'activate'])->name('activate');
        });

        // ========== QUẢN LÝ NGƯỜI DÙNG - CHỈ ADMIN ==========
        Route::prefix('nguoi-dung')->name('nguoi-dung.')->middleware(['CheckPermission:user.view'])->group(function () {
            Route::get('/', [NguoiDungController::class, 'index'])->name('index');
            Route::get('/sync', [NguoiDungController::class, 'syncHoSo'])->name('sync');
            Route::get('/create', [NguoiDungController::class, 'create'])->name('create');
            Route::post('/', [NguoiDungController::class, 'store'])->name('store');
            Route::get('/{id}', [NguoiDungController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [NguoiDungController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NguoiDungController::class, 'update'])->name('update');
            Route::delete('/{id}', [NguoiDungController::class, 'destroy'])->name('destroy');
        });

        // ========== PHÒNG BAN - CHỈ HR VÀ ADMIN ==========
        Route::prefix('phong-ban')->name('phong-ban.')->middleware(['CheckPermission:hoso.edit'])->group(function () {
            Route::get('/', [PhongBanController::class, 'index'])->name('index');
            Route::get('/org-chart', [PhongBanController::class, 'orgChart'])->name('org-chart');
            Route::get('/statistics', [PhongBanController::class, 'statistics'])->name('statistics');
            Route::get('/create', [PhongBanController::class, 'create'])->name('create');
            Route::post('/', [PhongBanController::class, 'store'])->name('store');
            Route::get('/{id}', [PhongBanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PhongBanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PhongBanController::class, 'update'])->name('update');
            Route::delete('/{id}', [PhongBanController::class, 'destroy'])->name('destroy');
        });

        // ========== CHỨC VỤ - CHỈ HR VÀ ADMIN ==========
        Route::prefix('chuc-vu')->name('chuc-vu.')->middleware(['CheckPermission:hoso.edit'])->group(function () {
            Route::get('/org-chart', [ChucVuController::class, 'orgChart'])->name('org-chart');
            Route::get('/statistics', [ChucVuController::class, 'statistics'])->name('statistics');
            Route::get('/', [ChucVuController::class, 'index'])->name('index');
            Route::get('/create', [ChucVuController::class, 'create'])->name('create');
            Route::post('/', [ChucVuController::class, 'store'])->name('store');
            Route::get('/{id}', [ChucVuController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ChucVuController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ChucVuController::class, 'update'])->name('update');
            Route::delete('/{id}', [ChucVuController::class, 'destroy'])->name('destroy');
        });

        // ========== HỒ SƠ CÁ NHÂN - AI CŨNG CÓ ==========
        Route::prefix('ho-so-ca-nhan')->name('ho-so-ca-nhan.')->group(function () {
            Route::get('/', [HoSoCaNhanController::class, 'index'])->name('index');
            Route::put('/update', [HoSoCaNhanController::class, 'update'])->name('update');
            Route::post('/change-password', [HoSoCaNhanController::class, 'changePassword'])->name('change-password');
        });

        // ========== CHẤM CÔNG - AI CŨNG CÓ ==========
        Route::prefix('cham-cong')->name('cham-cong.')->group(function () {
            Route::get('/', [ChamCongController::class, 'index'])->name('index')->middleware('CheckPermission:attendance.index');
            Route::get('/{id}', [ChamCongController::class, 'show'])->name('show')->middleware('CheckPermission:attendance.index');
            Route::get('/export', [ChamCongController::class, 'export'])->name('export')->middleware('CheckPermission:attendance.export');
            Route::post('/bulk-action', [ChamCongController::class, 'bulkAction'])->name('bulk-action')->middleware('CheckPermission:attendance.index');
            Route::post('/{id}/phe-duyet', [ChamCongController::class, 'pheDuyetDonLe'])->name('phe-duyet')->middleware('CheckPermission:attendance.index');
        });

        // ========== ĐƠN NGHỈ - AI CŨNG CÓ ==========
        Route::prefix('don-nghi')->name('don_nghi.')->group(function () {
            Route::get('/', [DonNghiController::class, 'index'])->name('index')
                ->middleware('CheckPermission:leave.index');
            Route::get('/{id}', [DonNghiController::class, 'show'])->name('show')
                ->middleware('CheckPermission:leave.show');
            Route::post('/{id}/duyet', [DonNghiController::class, 'capNhatTrangThai'])->name('duyet')
                ->middleware('CheckPermission:leave.approve');
            Route::post('/bulk-action', [DonNghiController::class, 'bulkAction'])->name('bulk')
                ->middleware('CheckPermission:leave.approve');
            Route::delete('/{id}', [DonNghiController::class, 'destroy'])->name('destroy')
                ->middleware('CheckPermission:leave.delete');
        });

        // ========== THÔNG BÁO - AI CŨNG CÓ ==========
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
            Route::get('/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::get('/{id}/mark-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::delete('/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
        });

        // ========== BẢNG LƯƠNG - CHỈ HR VÀ ADMIN ==========
        Route::prefix('bang-luong')->name('bang-luong.')->middleware(['CheckPermission:salary.index'])->group(function () {
            Route::get('/', [BangLuongController::class, 'index'])->name('index');
            Route::get('/create', [BangLuongController::class, 'create'])->name('create')->middleware('CheckPermission:salary.create');
            Route::post('/tinh', [BangLuongController::class, 'tinhLuong'])->name('tinh')->middleware('CheckPermission:salary.calculate');
            Route::get('/{id}', [BangLuongController::class, 'show'])->whereNumber('id')->name('show');
            Route::get('/{id}/nhan-vien/{luongId}', [BangLuongController::class, 'chiTietNhanVien'])->whereNumber(['id', 'luongId'])->name('chi-tiet-nhan-vien');
            Route::post('/{id}/gui-tat-ca-email', [BangLuongController::class, 'guiTatCaEmail'])->name('gui-tat-ca-email')->middleware('CheckPermission:salary.export');
            Route::post('/luong-nhan-vien/{luongId}/gui-email', [BangLuongController::class, 'guiEmailLuong'])->whereNumber('luongId')->name('gui-email')->middleware('CheckPermission:salary.export');
            Route::get('/{id}/export', [BangLuongController::class, 'export'])->whereNumber('id')->name('export')->middleware('CheckPermission:salary.export');
            Route::get('/{id}/export-pdf', [BangLuongController::class, 'exportPdf'])->whereNumber('id')->name('export-pdf')->middleware('CheckPermission:salary.export');
            Route::get('/{id}/nhan-vien/{luongId}/pdf', [BangLuongController::class, 'phieuLuongPdf'])->whereNumber(['id', 'luongId'])->name('phieu-luong-pdf')->middleware('CheckPermission:salary.export');
            Route::put('/{id}/chot', [BangLuongController::class, 'chot'])->whereNumber('id')->name('chot')->middleware('CheckPermission:salary.approve');
            Route::put('/{id}/thanh-toan', [BangLuongController::class, 'thanhToan'])->whereNumber('id')->name('thanh-toan')->middleware('CheckPermission:salary.approve');
            Route::delete('/{id}', [BangLuongController::class, 'destroy'])->whereNumber('id')->name('destroy')->middleware('CheckPermission:salary.index');
        });

        // ========== PHỤ CẤP - CHỈ HR VÀ ADMIN ==========
        Route::resource('phu-cap', PhuCapController::class)->middleware(['CheckPermission:salary.allowance']);

        // ========== KHẤU TRỪ KHÁC (tạm ứng, phạt...) - CHỈ HR VÀ ADMIN ==========
        Route::prefix('khau-tru-khac')->name('khau-tru-khac.')->middleware(['CheckPermission:salary.index'])->group(function () {

            Route::get('/', [KhauTruKhacController::class, 'index'])->name('index');
            Route::get('/{id}', [KhauTruKhacController::class, 'show'])->middleware(['CheckPermission:salary.show'])->name('show');
            Route::get('/create', [KhauTruKhacController::class, 'create'])->middleware('CheckPermission:salary.create')->name('create');
            Route::post('/', [KhauTruKhacController::class, 'store'])->middleware('CheckPermission:salary.create')->name('store');
            Route::get('/{id}/edit', [KhauTruKhacController::class, 'edit'])->middleware('CheckPermission:salary.create')->name('edit');
            Route::put('/{id}', [KhauTruKhacController::class, 'update'])->middleware('CheckPermission:salary.create')->name('update');
            Route::delete('/{id}', [KhauTruKhacController::class, 'destroy'])->middleware('CheckPermission:salary.create')->name('destroy');
            Route::post('/{id}/approve', [KhauTruKhacController::class, 'approve'])->middleware(['CheckPermission:salary.approve'])->name('approve');
            Route::post('/{id}/reject', [KhauTruKhacController::class, 'reject'])->middleware(['CheckPermission:salary.reject'])->name('reject');
            Route::post('/{id}/undo', [KhauTruKhacController::class, 'undo'])->middleware(['CheckPermission:salary.undo'])->name('undo');
});

        // ========== THỐNG KÊ QUỸ LƯƠNG THEO PHÒNG BAN - CHỈ HR VÀ ADMIN ==========
        Route::prefix('thong-ke-luong')->name('thong-ke-luong.')->middleware(['CheckPermission:salary.index'])->group(function () {
            Route::get('/', [ThongKeLuongController::class, 'index'])->name('index');
            Route::get('/pdf', [ThongKeLuongController::class, 'exportPdf'])->name('pdf')->middleware('CheckPermission:salary.export');
        });

        // ========== TỔNG LƯƠNG THEO NĂM - CHỈ HR VÀ ADMIN ==========
        Route::prefix('tong-luong')->name('tong-luong.')->middleware(['CheckPermission:salary.index'])->group(function () {
            Route::get('/', [ThongKeLuongController::class, 'theoNam'])->name('index');
            Route::get('/{nam}', [ThongKeLuongController::class, 'chiTietNam'])->whereNumber('nam')->name('chi-tiet');
        });

        // ========== QUẢN LÝ LƯƠNG - CHỈ HR VÀ ADMIN ==========
        Route::prefix('luong')->name('luong.')->middleware(['CheckPermission:salary.index'])->group(function () {
            Route::get('export', [LuongController::class, 'export'])->name('export')->middleware('CheckPermission:salary.export');
            Route::get('/', [LuongController::class, 'index'])->name('index');
            Route::get('/create', [LuongController::class, 'create'])->name('create')->middleware('CheckPermission:salary.create');
            Route::post('/', [LuongController::class, 'store'])->name('store')->middleware('CheckPermission:salary.create');
            Route::get('/{id}', [LuongController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [LuongController::class, 'edit'])->name('edit')->middleware('CheckPermission:salary.edit');
            Route::put('/{id}', [LuongController::class, 'update'])->name('update')->middleware('CheckPermission:salary.edit');
            Route::delete('/{id}', [LuongController::class, 'destroy'])->name('destroy')->middleware('CheckPermission:salary.delete');
        });

        // ========== VAI TRÒ - CHỈ ADMIN ==========
        Route::prefix('vai-tro')->name('vai-tro.')->middleware(['CheckPermission:role.view'])->group(function () {
            Route::get('/', [VaiTroController::class, 'index'])->name('index');
            Route::get('/create', [VaiTroController::class, 'create'])->name('create')->middleware('CheckPermission:role.create');
            Route::post('/', [VaiTroController::class, 'store'])->name('store')->middleware('CheckPermission:role.create');
            Route::get('/{id}', [VaiTroController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [VaiTroController::class, 'edit'])->name('edit')->middleware('CheckPermission:role.edit');
            Route::put('/{id}', [VaiTroController::class, 'update'])->name('update')->middleware('CheckPermission:role.edit');
            Route::delete('/{id}', [VaiTroController::class, 'destroy'])->name('destroy')->middleware('CheckPermission:role.delete');
        });

        // ========== LOẠI NGHỈ PHÉP - CHỈ HR VÀ ADMIN ==========
        Route::resource('loai-nghi-phep', LoaiNghiController::class)->middleware(['CheckPermission:leave_type.index']);

        // ========== KHEN THƯỞNG VÀ KỶ LUẬT - CHỈ HR VÀ ADMIN ==========
        Route::prefix('khen-thuong-ky-luat')
            ->name('khen-thuong-ky-luat.')
            ->middleware(['CheckPermission:hoso.edit'])
            ->group(function () {

                Route::get('/', [KhenThuongKyLuatController::class, 'index'])
                    ->name('index');

                Route::get('/create/khen-thuong', [KhenThuongKyLuatController::class, 'createKhenThuong'])
                    ->name('khen-thuong.create');

                Route::get('/create/ky-luat', [KhenThuongKyLuatController::class, 'createKyLuat'])
                    ->name('ky-luat.create');

                Route::post('/khen-thuong', [KhenThuongKyLuatController::class, 'storeKhenThuong'])
                    ->name('khen-thuong.store');

                Route::post('/ky-luat', [KhenThuongKyLuatController::class, 'storeKyLuat'])
                    ->name('ky-luat.store');

                Route::get('/export/excel', [KhenThuongKyLuatController::class, 'export'])
                    ->name('export');

                Route::get('/thong-ke', [KhenThuongKyLuatController::class, 'thongKe'])
                    ->name('thong-ke');

                Route::get('/thuong-cuoi-nam', [KhenThuongKyLuatController::class, 'tinhThuong'])
                    ->name('thuong-cuoi-nam');

                Route::get('/{id}', [KhenThuongKyLuatController::class, 'show'])
                    ->name('show');

                Route::get('/{id}/edit', [KhenThuongKyLuatController::class, 'edit'])
                    ->name('edit');

                Route::put('/{id}/khen-thuong', [KhenThuongKyLuatController::class, 'updateKhenThuong'])
                    ->name('khen-thuong.update');

                Route::put('/{id}/ky-luat', [KhenThuongKyLuatController::class, 'updateKyLuat'])
                    ->name('ky-luat.update');

                Route::delete('/{id}', [KhenThuongKyLuatController::class, 'destroy'])
                    ->name('destroy');
            });

        // ========== QUY ĐỊNH - CHỈ ADMIN ==========
        Route::prefix('quy-dinh')->name('quy-dinh.')->middleware(['CheckPermission:setting.general'])->group(function () {
            Route::get('/', [QuyDinhController::class, 'index'])->name('index');
            Route::get('/edit', [QuyDinhController::class, 'edit'])->name('edit');
            Route::post('/update', [QuyDinhController::class, 'update'])->name('update');
        });

        // ========== ĐÀO TẠO ==========
        Route::prefix('dao-tao')->name('dao-tao.')->middleware(['CheckPermission:hoso.edit'])->group(function () {

            Route::get('/', [DaoTaoController::class, 'index'])->middleware(['CheckPermission:dao-tao.index'])->name('index');

            Route::get('/create', [DaoTaoController::class, 'create'])->middleware(['CheckPermission:dao-tao.create'])->name('create');

            Route::post('/', [DaoTaoController::class, 'store'])->middleware(['CheckPermission:dao-tao.store'])->name('store');

            Route::get('/thong-ke', [DaoTaoController::class, 'thongKe'])->middleware(['CheckPermission:dao-tao.thong-ke'])->name('thong-ke');

            Route::get('/export/excel', [DaoTaoController::class, 'export'])->middleware(['CheckPermission:dao-tao.export'])->name('export');

            Route::get('/{id}', [DaoTaoController::class, 'show'])->middleware(['CheckPermission:dao-tao.show'])->name('show');

            Route::get('/{id}/edit', [DaoTaoController::class, 'edit'])->middleware(['CheckPermission:dao-tao.edit'])->name('edit');

            Route::put('/{id}', [DaoTaoController::class, 'update'])->middleware(['CheckPermission:dao-tao.update'])->name('update');

            Route::delete('/{id}', [DaoTaoController::class, 'destroy'])->middleware(['CheckPermission:dao-tao.destroy'])->name('destroy');
        });

        // ========== CHỨNG CHỈ ==========
        Route::prefix('chung-chi')->name('chung-chi.')->middleware(['CheckPermission:hoso.edit'])->group(function () {

            Route::get('/', [ChungChiNhanVienController::class, 'index'])->middleware(['CheckPermission:chung-chi.index'])
                ->name('index');

            Route::get('/{id}', [ChungChiNhanVienController::class, 'show'])->middleware(['CheckPermission:chung-chi.show'])
                ->name('show');

            Route::get('/{id}/edit', [ChungChiNhanVienController::class, 'edit'])->middleware(['CheckPermission:chung-chi.edit'])
                ->name('edit');

            Route::put('/{id}', [ChungChiNhanVienController::class, 'update'])->middleware(['CheckPermission:chung-chi.update'])
                ->name('update');

            Route::delete('/{id}', [ChungChiNhanVienController::class, 'destroy'])->middleware(['CheckPermission:chung-chi.destroy'])
                ->name('destroy');
        });

        // ========== HỢP ĐỒNG - CHỈ HR VÀ ADMIN ==========
        Route::prefix('hop-dong')->name('hop-dong.')->middleware(['CheckPermission:contract.index'])->group(function () {
            Route::get('/', [HopDongLaoDongController::class, 'index'])->name('index');
            Route::get('/cua-toi', [HopDongLaoDongController::class, 'cuaToi'])->name('cua-toi');
            Route::get('/luu-tru', [HopDongLaoDongController::class, 'luuTru'])->name('luu-tru');
            Route::get('/thong-ke', [HopDongLaoDongController::class, 'thongKe'])->name('thong-ke');
            Route::get('/export', [HopDongLaoDongController::class, 'export'])->name('export')->middleware('CheckPermission:contract.export');
            Route::get('/tao-moi', [HopDongLaoDongController::class, 'create'])->name('create')->middleware('CheckPermission:contract.create');
            Route::post('/tao-moi', [HopDongLaoDongController::class, 'store'])->name('store')->middleware('CheckPermission:contract.create');
            Route::get('/{id}', [HopDongLaoDongController::class, 'show'])->name('show');
            Route::get('/{id}/sua', [HopDongLaoDongController::class, 'edit'])->name('edit')->middleware('CheckPermission:contract.edit');
            Route::put('/{id}', [HopDongLaoDongController::class, 'update'])->name('update')->middleware('CheckPermission:contract.edit');
            Route::delete('/{id}', [HopDongLaoDongController::class, 'destroy'])->name('destroy')->middleware('CheckPermission:contract.delete');
            Route::post('/{id}/gui-ky', [HopDongLaoDongController::class, 'guiKy'])->name('gui-ky')->middleware('CheckPermission:contract.sign');
            Route::post('/{id}/huy', [HopDongLaoDongController::class, 'huy'])->name('huy')->middleware('CheckPermission:contract.edit');
            Route::get('/get-nhan-vien-info/{id}', [HopDongLaoDongController::class, 'getNhanVienInfo'])->name('get-nhan-vien-info');
            Route::post('/tai-ky/{id}', [HopDongLaoDongController::class, 'taiKy'])->name('tai-ky')->middleware('CheckPermission:contract.sign');
        });

        Route::prefix('tang-luong')->name('tang-luong.')->middleware(['CheckPermission:contract.edit'])->group(function () {
            Route::get('/', [TangLuongController::class, 'index'])->name('index');
            Route::get('/hop-dong/{id}/create', [TangLuongController::class, 'create'])->name('create');
            Route::post('/', [TangLuongController::class, 'store'])->name('store');
            Route::post('/{id}/duyet', [TangLuongController::class, 'duyet'])->name('duyet');
            Route::post('/{id}/tu-choi', [TangLuongController::class, 'tuChoi'])->name('tu-choi');
        });

        // ========== PHÂN QUYỀN - CHỈ ADMIN ==========
        Route::prefix('phan-quyen')->name('phan-quyen.')->middleware(['CheckPermission:setting.permission'])->group(function () {
            Route::get('/', [PhanQuyenController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [PhanQuyenController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PhanQuyenController::class, 'update'])->name('update');
        });

        // ========== YÊU CẦU ĐIỀU CHỈNH CÔNG - CHỈ HR VÀ ADMIN ==========
        Route::prefix('yeu-cau-dieu-chinh-cong')->name('yeu-cau-dieu-chinh-cong.')->middleware(['CheckPermission:attendance.adjustment_approve'])->group(function () {
            Route::get('/', [YeuCauDieuChinhCongAdminController::class, 'index'])->name('index');
            Route::get('/bao-cao', [YeuCauDieuChinhCongAdminController::class, 'baoCao'])->name('bao-cao');
            Route::get('/{id}', [YeuCauDieuChinhCongAdminController::class, 'show'])->name('show');
            Route::post('/{id}/duyet', [YeuCauDieuChinhCongAdminController::class, 'duyet'])->name('duyet');
            Route::post('/duyet-hang-loat', [YeuCauDieuChinhCongAdminController::class, 'duyetHangLoat'])->name('duyet-hang-loat');
            Route::delete('/{id}', [YeuCauDieuChinhCongAdminController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/download', [YeuCauDieuChinhCongAdminController::class, 'downloadFile'])->name('download');
        });

        // ========== TĂNG CA - CHỈ HR VÀ ADMIN ==========
        Route::prefix('tang-ca')->name('tang-ca.')->middleware(['CheckPermission:attendance.overtime_approve'])->group(function () {
            Route::get('/', [TangCaController::class, 'index'])->name('index');
            Route::get('/{id}', [TangCaController::class, 'show'])->name('show');
            Route::post('/{id}/duyet', [TangCaController::class, 'duyet'])->name('duyet');
            Route::post('/{id}/tu-choi', [TangCaController::class, 'tuChoi'])->name('tu-choi');
            Route::post('/duyet-hang-loat', [TangCaController::class, 'duyetHangLoat'])->name('duyet-hang-loat');
        });

        // ========== THỰC HIỆN TĂNG CA - CHỈ HR VÀ ADMIN ==========
        Route::prefix('thuc-hien-tang-ca')->name('thuc-hien-tang-ca.')->middleware(['CheckPermission:attendance.overtime_approve'])->group(function () {
            Route::get('/', [ThucHienTangCaController::class, 'index'])->name('index');
            Route::get('/{id}', [ThucHienTangCaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ThucHienTangCaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ThucHienTangCaController::class, 'update'])->name('update');
        });

        // ========== DUYỆT ĐƠN TUYỂN DỤNG - CHỈ HR VÀ ADMIN ==========
        // Route::prefix('duyetdon')->name('duyetdon.')->group(function () {
        //     Route::prefix('tuyendung')->name('tuyendung.')->middleware(['CheckPermission:recruitment.index'])->group(function () {
        //         Route::get('/', [DuyetDonController::class, 'index'])->name('index');
        //         Route::get('/{id}', [DuyetDonController::class, 'show'])->name('show');
        //         Route::post('/{id}/duyet', [DuyetDonController::class, 'duyet'])->name('duyet');
        //         Route::post('/{id}/tuchoi', [DuyetDonController::class, 'tuChoi'])->name('tuchoi');
        //     });
        // });

        // ========== TEST ROUTE ==========
        Route::get('/test-mail', function () {
            Mail::raw('Test gửi mail HRFlow', function ($message) {
                $message->to('lehuuvan16092006@gmail.com')->subject('Test Mail');
            });
            return 'OK';
        });
    });

// =============================================
// EMPLOYEE ROUTES
// =============================================
Route::prefix('employee')
    ->name('employee.')
    ->middleware(['auth'])
    ->group(function () {

        // ========== DASHBOARD ==========
        Route::get('/dashboard', [DashboardEmployeeController::class, 'index'])->name('dashboard');

        // ========== HỢP ĐỒNG CỦA TÔI ==========
        Route::prefix('hop-dong-cua-toi')->name('hop-dong.')->group(function () {
            Route::get('/', [HopDongController::class, 'getHopDongCuaToi'])->name('index');
            Route::patch('/{id}/update-status', [HopDongController::class, 'updateTrangThaiKy'])->name('update-status');
            Route::patch('/{id}/tu-choi-ky', [HopDongController::class, 'tuChoiKy'])->name('tu-choi-ky');
        });

        // ========== CHẤM CÔNG ==========
        Route::prefix('cham-cong')->name('cham-cong.')->group(function () {
            Route::get('/', [EmployeeChamCongController::class, 'index'])->name('index');
            Route::post('/check-in', [EmployeeChamCongController::class, 'checkIn'])->name('check-in');
            Route::post('/check-out', [EmployeeChamCongController::class, 'checkOut'])->name('check-out');
            Route::get('/history', [EmployeeChamCongController::class, 'history'])->name('history');
            Route::post('/save-device-info', [EmployeeChamCongController::class, 'saveDeviceInfo'])->name('save-device-info');
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

        // ========== BẢNG LƯƠNG ==========
        Route::prefix('bang-luong')->name('bang-luong.')->group(function () {
            Route::get('/', [EmployeeBangLuongController::class, 'index'])->name('index');
            Route::get('/nam/{year}', [EmployeeBangLuongController::class, 'year'])->whereNumber('year')->name('year');
            Route::get('/{id}', [EmployeeBangLuongController::class, 'show'])->whereNumber('id')->name('show');
        });

        // ========== THÔNG BÁO ==========
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::get('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        });
    });

// ========== QUY ĐỊNH (CÔNG KHAI) ==========
Route::get('/quy-dinh', [EmployeeQuyDinhController::class, 'index'])->name('employee.quydinh.index');

// ========== TEST NOTIFICATION ==========
Route::get('/test-notification', function () {
    $user = auth()->user();
    if (!$user) {
        return '❌ Chưa đăng nhập!';
    }
    try {
        $admin = NguoiDung::where('vai_tro_id', 1)->first();
        if (!$admin) {
            return '❌ Không tìm thấy admin!';
        }
        $donNghi = new DonXinNghi([
            'id' => 999,
            'ma_don_nghi' => 'DN_TEST_' . time(),
            'nguoi_dung_id' => $user->id,
            'loai_nghi_phep_id' => 1,
            'ngay_bat_dau' => now(),
            'ngay_ket_thuc' => now()->addDay(),
            'so_ngay_nghi' => 1,
            'ly_do' => 'Test notification',
            'trang_thai' => 'cho_duyet',
        ]);
        $donNghi->nguoiDung = $user;
        $service = app(NotificationService::class);
        $service->sendToUser($admin, new \App\Notifications\LeaveRequestNotification($donNghi, 'created'));
        return '✅ Đã gửi thông báo test đến admin: ' . $admin->email;
    } catch (\Exception $e) {
        return '❌ Lỗi: ' . $e->getMessage();
    }
});

// =============================================
// TRƯỞNG PHÒNG ROUTES
// =============================================
Route::middleware(['auth', 'truong_phong'])
    ->prefix('truong-phong')
    ->name('truong-phong.')
    ->group(function () {

        // Dashboard trưởng phòng
        Route::get('/dashboard', [DashboardTruongPhongController::class, 'index'])
            ->name('dashboard');

        // API lấy danh sách nhân viên
        Route::get('/api/nhan-vien', [DashboardTruongPhongController::class, 'getNhanVien'])
            ->name('api.nhan-vien');

        Route::prefix('bao-cao')->name('bao-cao.')->group(function () {
            Route::get('/overview', [BaoCaoController::class, 'overview'])->name('overview');
            Route::get('/attendance', [BaoCaoController::class, 'attendance'])->name('attendance');
            Route::get('/leave', [BaoCaoController::class, 'leave'])->name('leave');
            Route::get('/export', [BaoCaoController::class, 'export'])->name('export');
        });

        Route::prefix('nhan-vien')->name('nhan-vien.')->group(function () {
            Route::get('/', [NhanVienController::class, 'index'])->name('index');
            Route::get('/{id}', [NhanVienController::class, 'show'])->name('show');
        });
    });
