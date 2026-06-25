<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\NguoiDung;
use App\Notifications\LeaveRequestNotification;
use App\Notifications\OvertimeNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendToUser($user, $notification): void
    {
        if ($user) {
            try {
                $user->notify($notification);
                Log::info('✅ Notification sent to user: ' . $user->id . ' - ' . $user->email);
            } catch (\Exception $e) {
                Log::error('❌ Send notification error: ' . $e->getMessage());
            }
        } else {
            Log::warning('⚠️ User is null, cannot send notification');
        }
    }

    public function sendToAdmins($notification): void
    {
        Log::info('📨 sendToAdmins called');
        
        $admins = NguoiDung::whereHas('vaiTro', function($query) {
            $query->where('name', 'admin');
        })->get();

        Log::info('📨 Found ' . $admins->count() . ' admins');

        if ($admins->isEmpty()) {
            Log::warning('⚠️ No admin found!');
            return;
        }

        foreach ($admins as $admin) {
            Log::info('📨 Sending to admin: ' . $admin->id . ' - ' . $admin->email);
            $this->sendToUser($admin, $notification);
        }
    }

    public function sendToEmployee($employee, $notification): void
    {
        if ($employee) {
            Log::info('📨 Sending to employee: ' . $employee->id . ' - ' . $employee->email);
            $this->sendToUser($employee, $notification);
        } else {
            Log::warning('⚠️ Employee not found');
        }
    }

    /**
     * Gửi thông báo đơn nghỉ phép
     */
    public function notifyLeaveRequest($donNghi, string $action = 'created'): void
    {
        Log::info('📝 notifyLeaveRequest called - Action: ' . $action);
        Log::info('📝 DonNghi ID: ' . $donNghi->id);
        
        if ($action === 'created') {
            $this->sendToAdmins(new LeaveRequestNotification($donNghi, $action));
        } else {
            $employee = $donNghi->nguoiDung;
            Log::info('📝 Employee ID: ' . ($employee ? $employee->id : 'null'));
            
            if ($employee) {
                $this->sendToEmployee($employee, new LeaveRequestNotification($donNghi, $action));
            } else {
                Log::warning('⚠️ Employee not found for donNghi: ' . $donNghi->id);
            }
        }
    }

    /**
     * Gửi thông báo tăng ca
     */
    public function notifyOvertime($tangCa, string $action = 'created'): void
    {
        Log::info('📝 notifyOvertime called - Action: ' . $action);
        Log::info('📝 TangCa ID: ' . $tangCa->id);
        
        if ($action === 'created') {
            $this->sendToAdmins(new OvertimeNotification($tangCa, $action));
        } else {
            $employee = $tangCa->nguoiDung;
            Log::info('📝 Employee ID: ' . ($employee ? $employee->id : 'null'));
            
            if ($employee) {
                $this->sendToEmployee($employee, new OvertimeNotification($tangCa, $action));
            } else {
                Log::warning('⚠️ Employee not found for tangCa: ' . $tangCa->id);
            }
        }
    }
}