<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\DonXinNghi;
use App\Models\DangKyTangCa;
use App\Models\YeuCauDieuChinhCong;
use App\Models\NguoiDung;
use App\Notifications\LeaveRequestNotification;
use App\Notifications\OvertimeNotification;
use App\Notifications\PheDuyetYeuCauChinhCong;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Gửi thông báo đơn nghỉ phép
     */
    public function notifyLeaveRequest(DonXinNghi $donNghi, string $action): void
    {
        try {
            if ($action === 'created') {
                $admins = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'Super Admin', 'Admin']);
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new LeaveRequestNotification($donNghi, $action));
                }
            }

            if (in_array($action, ['approved', 'rejected', 'cancelled'])) {
                $employee = $donNghi->nguoiDung;
                if ($employee) {
                    $employee->notify(new LeaveRequestNotification($donNghi, $action));
                }
            }

            Log::info('Đã gửi thông báo đơn nghỉ: ' . $donNghi->id . ' - Action: ' . $action);
        } catch (\Exception $e) {
            Log::error('Lỗi gửi thông báo đơn nghỉ: ' . $e->getMessage());
        }
    }

    /**
     * Gửi thông báo tăng ca
     */
    public function notifyOvertime(DangKyTangCa $tangCa, string $action): void
    {
        try {
            $employee = $tangCa->nguoiDung;
            
            // Lấy tên nhân viên
            $tenNhanVien = optional($employee->hoSo)
                ? $employee->hoSo->ho . ' ' . $employee->hoSo->ten
                : $employee->ten_dang_nhap;

            if ($action === 'created') {
                // Gửi cho Admin khi tạo mới
                $admins = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'Super Admin', 'Admin']);
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new OvertimeNotification($tangCa, $action));
                }
                Log::info('📧 Gửi thông báo tạo đơn tăng ca đến Admin');
            }

            if ($action === 'approved') {
                // Gửi cho nhân viên khi được duyệt
                if ($employee) {
                    $employee->notify(new OvertimeNotification($tangCa, $action));
                    Log::info('📧 Gửi thông báo duyệt đơn tăng ca đến nhân viên: ' . $employee->email);
                }
            }

            if ($action === 'rejected') {
                // Gửi cho nhân viên khi bị từ chối
                if ($employee) {
                    $employee->notify(new OvertimeNotification($tangCa, $action));
                    Log::info('📧 Gửi thông báo từ chối đơn tăng ca đến nhân viên: ' . $employee->email);
                }
            }

            if ($action === 'cancelled') {
                // Gửi cho Admin khi nhân viên hủy đơn
                $admins = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'Super Admin', 'Admin']);
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new OvertimeNotification($tangCa, $action));
                }
                Log::info('📧 Gửi thông báo hủy đơn tăng ca đến Admin');
            }

            // ⭐ THÊM MỚI: Xác nhận đã làm tăng ca (nhân viên)
            if ($action === 'employee_confirmed') {
                // Gửi cho Admin và Quản lý khi nhân viên xác nhận đã làm tăng ca
                $admins = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'Super Admin', 'Admin', 'truong_phong', 'quan_ly']);
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new OvertimeNotification($tangCa, $action));
                }
                Log::info('📧 Gửi thông báo nhân viên đã xác nhận làm tăng ca đến Admin/Quản lý');
            }

            // ⭐ THÊM MỚI: Quản lý xác nhận hoàn thành
            if ($action === 'manager_approved') {
                // Gửi cho nhân viên khi quản lý xác nhận hoàn thành
                if ($employee) {
                    $employee->notify(new OvertimeNotification($tangCa, $action));
                    Log::info('📧 Gửi thông báo hoàn thành tăng ca đến nhân viên: ' . $employee->email);
                }

                // Gửi cho HR/Kế toán để tính lương
                $hrUsers = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['hr', 'HR', 'ke_toan', 'Ke Toan', 'admin', 'Super Admin']);
                })->get();

                foreach ($hrUsers as $hr) {
                    $hr->notify(new OvertimeNotification($tangCa, $action));
                }
                Log::info('📧 Gửi thông báo hoàn thành tăng ca đến HR/Kế toán để tính lương');
            }

            Log::info('✅ Đã gửi thông báo tăng ca: ' . $tangCa->id . ' - Action: ' . $action);
        } catch (\Exception $e) {
            Log::error('❌ Lỗi gửi thông báo tăng ca: ' . $e->getMessage());
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * GỬI THÔNG BÁO YÊU CẦU CHỈNH CÔNG
     */
    public function notifyYeuCauChinhCong($yeuCau, string $trangThai): void
    {
        try {
            Log::info('📝 notifyYeuCauChinhCong called - Trạng thái: ' . $trangThai);
            Log::info('📝 YeuCau ID: ' . $yeuCau->id);

            // Gửi thông báo cho NHÂN VIÊN (người gửi yêu cầu)
            $employee = $yeuCau->nguoiDung;
            if ($employee) {
                $employee->notify(new PheDuyetYeuCauChinhCong($yeuCau, $trangThai));
                Log::info('📝 Đã gửi thông báo đến nhân viên: ' . $employee->email);
            }

            Log::info('✅ Đã gửi thông báo yêu cầu chỉnh công: ' . $yeuCau->id);
        } catch (\Exception $e) {
            Log::error('❌ Lỗi gửi thông báo yêu cầu chỉnh công: ' . $e->getMessage());
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * ⭐ GỬI THÔNG BÁO ĐẾN MỘT USER CỤ THỂ
     */
    public function sendToUser(NguoiDung $user, $notification): void
    {
        try {
            $user->notify($notification);
            Log::info('📧 Đã gửi thông báo đến user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('❌ Lỗi gửi thông báo đến user: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ GỬI THÔNG BÁO ĐẾN NHIỀU USER
     */
    public function sendToUsers($users, $notification): void
    {
        try {
            foreach ($users as $user) {
                $user->notify($notification);
            }
            Log::info('📧 Đã gửi thông báo đến ' . count($users) . ' user');
        } catch (\Exception $e) {
            Log::error('❌ Lỗi gửi thông báo đến nhiều user: ' . $e->getMessage());
        }
    }
}