<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\DonXinNghi;
use App\Models\DangKyTangCa;
use App\Models\YeuCauDieuChinhCong; // ⭐ THÊM DÒNG NÀY
use App\Models\NguoiDung;
use App\Notifications\LeaveRequestNotification;
use App\Notifications\OvertimeNotification;
use App\Notifications\PheDuyetYeuCauChinhCong; // ⭐ THÊM DÒNG NÀY
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
            if ($action === 'created') {
                $admins = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'Super Admin', 'Admin']);
                })->get();

                foreach ($admins as $admin) {
                    $admin->notify(new OvertimeNotification($tangCa, $action));
                }
            }

            if (in_array($action, ['approved', 'rejected', 'cancelled'])) {
                $employee = $tangCa->nguoiDung;
                if ($employee) {
                    $employee->notify(new OvertimeNotification($tangCa, $action));
                }
            }

            Log::info('Đã gửi thông báo tăng ca: ' . $tangCa->id . ' - Action: ' . $action);
        } catch (\Exception $e) {
            Log::error('Lỗi gửi thông báo tăng ca: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ GỬI THÔNG BÁO YÊU CẦU CHỈNH CÔNG
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
}