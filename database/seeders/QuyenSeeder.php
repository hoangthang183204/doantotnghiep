<?php

namespace Database\Seeders;

use App\Models\Quyen;
use Illuminate\Database\Seeder;

class QuyenSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // ========== DASHBOARD ==========
            ['name' => 'dashboard.view', 'ten_hien_thi' => 'Xem Dashboard', 'nhom' => 'dashboard'],
            ['name' => 'dashboard.admin', 'ten_hien_thi' => 'Xem Dashboard Admin', 'nhom' => 'dashboard'],
            ['name' => 'dashboard.manager', 'ten_hien_thi' => 'Xem Dashboard Trưởng phòng', 'nhom' => 'dashboard'],
            ['name' => 'dashboard.employee', 'ten_hien_thi' => 'Xem Dashboard Nhân viên', 'nhom' => 'dashboard'],

            // ========== NHÂN SỰ (HỒ SƠ) ==========
            ['name' => 'hoso.index', 'ten_hien_thi' => 'Xem danh sách hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.show', 'ten_hien_thi' => 'Xem chi tiết hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.create', 'ten_hien_thi' => 'Thêm hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.edit', 'ten_hien_thi' => 'Sửa hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.delete', 'ten_hien_thi' => 'Xóa hồ sơ', 'nhom' => 'hoso'],
            ['name' => 'hoso.resign', 'ten_hien_thi' => 'Xử lý nghỉ việc', 'nhom' => 'hoso'],
            ['name' => 'hoso.activate', 'ten_hien_thi' => 'Kích hoạt nhân viên', 'nhom' => 'hoso'],
            ['name' => 'hoso.personal', 'ten_hien_thi' => 'Xem hồ sơ cá nhân', 'nhom' => 'hoso'],

            // ========== NGƯỜI DÙNG ==========
            ['name' => 'user.view', 'ten_hien_thi' => 'Xem danh sách người dùng', 'nhom' => 'user'],
            ['name' => 'user.create', 'ten_hien_thi' => 'Thêm người dùng', 'nhom' => 'user'],
            ['name' => 'user.edit', 'ten_hien_thi' => 'Sửa người dùng', 'nhom' => 'user'],
            ['name' => 'user.delete', 'ten_hien_thi' => 'Xóa người dùng', 'nhom' => 'user'],
            ['name' => 'user.reset_password', 'ten_hien_thi' => 'Reset mật khẩu', 'nhom' => 'user'],
            ['name' => 'user.lock', 'ten_hien_thi' => 'Khóa/Mở khóa tài khoản', 'nhom' => 'user'],

            // ========== CHỨC VỤ ==========
            ['name' => 'chucvu.view', 'ten_hien_thi' => 'Xem danh sách chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.create', 'ten_hien_thi' => 'Thêm chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.edit', 'ten_hien_thi' => 'Sửa chức vụ', 'nhom' => 'chucvu'],
            ['name' => 'chucvu.delete', 'ten_hien_thi' => 'Xóa chức vụ', 'nhom' => 'chucvu'],

            // ========== PHÒNG BAN ==========
            ['name' => 'department.view', 'ten_hien_thi' => 'Xem danh sách phòng ban', 'nhom' => 'department'],
            ['name' => 'department.create', 'ten_hien_thi' => 'Thêm phòng ban', 'nhom' => 'department'],
            ['name' => 'department.edit', 'ten_hien_thi' => 'Sửa phòng ban', 'nhom' => 'department'],
            ['name' => 'department.delete', 'ten_hien_thi' => 'Xóa phòng ban', 'nhom' => 'department'],

            // ========== CHI NHÁNH ==========
            ['name' => 'branch.view', 'ten_hien_thi' => 'Xem danh sách chi nhánh', 'nhom' => 'branch'],
            ['name' => 'branch.create', 'ten_hien_thi' => 'Thêm chi nhánh', 'nhom' => 'branch'],
            ['name' => 'branch.edit', 'ten_hien_thi' => 'Sửa chi nhánh', 'nhom' => 'branch'],
            ['name' => 'branch.delete', 'ten_hien_thi' => 'Xóa chi nhánh', 'nhom' => 'branch'],

            // ========== VAI TRÒ ==========
            ['name' => 'role.view', 'ten_hien_thi' => 'Xem danh sách vai trò', 'nhom' => 'role'],
            ['name' => 'role.create', 'ten_hien_thi' => 'Thêm vai trò', 'nhom' => 'role'],
            ['name' => 'role.edit', 'ten_hien_thi' => 'Sửa vai trò', 'nhom' => 'role'],
            ['name' => 'role.delete', 'ten_hien_thi' => 'Xóa vai trò', 'nhom' => 'role'],
            ['name' => 'role.permission', 'ten_hien_thi' => 'Phân quyền cho vai trò', 'nhom' => 'role'],

            // ========== CHẤM CÔNG ==========
            ['name' => 'attendance.index', 'ten_hien_thi' => 'Xem danh sách chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.checkin', 'ten_hien_thi' => 'Chấm công vào', 'nhom' => 'attendance'],
            ['name' => 'attendance.checkout', 'ten_hien_thi' => 'Chấm công ra', 'nhom' => 'attendance'],
            ['name' => 'attendance.history', 'ten_hien_thi' => 'Xem lịch sử chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.export', 'ten_hien_thi' => 'Xuất Excel chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.import', 'ten_hien_thi' => 'Import chấm công', 'nhom' => 'attendance'],
            ['name' => 'attendance.location', 'ten_hien_thi' => 'Quản lý vị trí', 'nhom' => 'attendance'],
            ['name' => 'attendance.time_config', 'ten_hien_thi' => 'Quản lý thời gian', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime', 'ten_hien_thi' => 'Quản lý tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.overtime_approve', 'ten_hien_thi' => 'Phê duyệt tăng ca', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment', 'ten_hien_thi' => 'Yêu cầu chỉnh công', 'nhom' => 'attendance'],
            ['name' => 'attendance.adjustment_approve', 'ten_hien_thi' => 'Duyệt chỉnh công', 'nhom' => 'attendance'],

            // ========== LƯƠNG ==========
            ['name' => 'salary.index', 'ten_hien_thi' => 'Xem danh sách lương', 'nhom' => 'salary'],
            ['name' => 'salary.create', 'ten_hien_thi' => 'Tạo bảng lương', 'nhom' => 'salary'],
            ['name' => 'salary.calculate', 'ten_hien_thi' => 'Tính lương', 'nhom' => 'salary'],
            ['name' => 'salary.payslip', 'ten_hien_thi' => 'Xuất phiếu lương', 'nhom' => 'salary'],
            ['name' => 'salary.approve', 'ten_hien_thi' => 'Duyệt lương', 'nhom' => 'salary'],
            ['name' => 'salary.allowance', 'ten_hien_thi' => 'Quản lý phụ cấp', 'nhom' => 'salary'],
            ['name' => 'salary.deduction', 'ten_hien_thi' => 'Quản lý khấu trừ', 'nhom' => 'salary'],
            ['name' => 'salary.report', 'ten_hien_thi' => 'Báo cáo lương', 'nhom' => 'salary'],

            // ========== HỢP ĐỒNG ==========
            ['name' => 'contract.index', 'ten_hien_thi' => 'Xem danh sách hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.create', 'ten_hien_thi' => 'Thêm hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.edit', 'ten_hien_thi' => 'Sửa hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.delete', 'ten_hien_thi' => 'Xóa hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.renew', 'ten_hien_thi' => 'Gia hạn hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.terminate', 'ten_hien_thi' => 'Thanh lý hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.export', 'ten_hien_thi' => 'Xuất Excel/PDF hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.sign', 'ten_hien_thi' => 'Ký hợp đồng', 'nhom' => 'contract'],
            ['name' => 'contract.expiring', 'ten_hien_thi' => 'Hợp đồng sắp hết hạn', 'nhom' => 'contract'],

            // ========== TUYỂN DỤNG & ỨNG VIÊN ==========
            ['name' => 'recruitment.candidate', 'ten_hien_thi' => 'Xem danh sách ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_create', 'ten_hien_thi' => 'Thêm ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_edit', 'ten_hien_thi' => 'Sửa ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.candidate_delete', 'ten_hien_thi' => 'Xóa ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.interview', 'ten_hien_thi' => 'Sắp xếp phỏng vấn', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.email', 'ten_hien_thi' => 'Gửi email cho ứng viên', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.passed', 'ten_hien_thi' => 'Xử lý trúng tuyển', 'nhom' => 'recruitment'],
            ['name' => 'recruitment.post', 'ten_hien_thi' => 'Đăng tin tuyển dụng', 'nhom' => 'recruitment'],

            // ========== ĐƠN NGHỈ PHÉP ==========
            ['name' => 'leave.request', 'ten_hien_thi' => 'Tạo đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.history', 'ten_hien_thi' => 'Xem lịch sử nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.balance', 'ten_hien_thi' => 'Xem số dư phép', 'nhom' => 'leave'],
            ['name' => 'leave.cancel', 'ten_hien_thi' => 'Hủy đơn nghỉ phép', 'nhom' => 'leave'],
            ['name' => 'leave.approve', 'ten_hien_thi' => 'Duyệt đơn nghỉ phép', 'nhom' => 'leave'],

            // ========== LOẠI NGHỈ PHÉP ==========
            ['name' => 'leave_type.index', 'ten_hien_thi' => 'Xem danh sách loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.create', 'ten_hien_thi' => 'Thêm loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.edit', 'ten_hien_thi' => 'Sửa loại nghỉ phép', 'nhom' => 'leave_type'],
            ['name' => 'leave_type.delete', 'ten_hien_thi' => 'Xóa loại nghỉ phép', 'nhom' => 'leave_type'],

            // ========== DUYỆT ĐƠN ==========
            ['name' => 'approval.recruitment', 'ten_hien_thi' => 'Duyệt đơn tuyển dụng', 'nhom' => 'approval'],
            ['name' => 'approval.leave', 'ten_hien_thi' => 'Duyệt đơn nghỉ phép', 'nhom' => 'approval'],
            ['name' => 'approval.overtime', 'ten_hien_thi' => 'Duyệt đơn tăng ca', 'nhom' => 'approval'],
            ['name' => 'approval.adjustment', 'ten_hien_thi' => 'Duyệt chỉnh công', 'nhom' => 'approval'],

            // ========== QUY ĐỊNH ==========
            ['name' => 'regulation.view', 'ten_hien_thi' => 'Xem quy định', 'nhom' => 'regulation'],
            ['name' => 'regulation.edit', 'ten_hien_thi' => 'Sửa quy định', 'nhom' => 'regulation'],

            // ========== CÀI ĐẶT HỆ THỐNG ==========
            ['name' => 'setting.general', 'ten_hien_thi' => 'Cấu hình chung', 'nhom' => 'setting'],
            ['name' => 'setting.backup', 'ten_hien_thi' => 'Sao lưu dữ liệu', 'nhom' => 'setting'],
            ['name' => 'setting.log', 'ten_hien_thi' => 'Xem nhật ký hệ thống', 'nhom' => 'setting'],
            ['name' => 'setting.permission', 'ten_hien_thi' => 'Phân quyền hệ thống', 'nhom' => 'setting'],

            // ========== BÁO CÁO ==========
            ['name' => 'report.attendance', 'ten_hien_thi' => 'Báo cáo chấm công', 'nhom' => 'report'],
            ['name' => 'report.salary', 'ten_hien_thi' => 'Báo cáo lương', 'nhom' => 'report'],
            ['name' => 'report.employee', 'ten_hien_thi' => 'Báo cáo nhân sự', 'nhom' => 'report'],
            ['name' => 'report.contract', 'ten_hien_thi' => 'Báo cáo hợp đồng', 'nhom' => 'report'],
            ['name' => 'report.recruitment', 'ten_hien_thi' => 'Báo cáo tuyển dụng', 'nhom' => 'report'],

            // ========== TIN NHẮN ==========
            ['name' => 'chat.send', 'ten_hien_thi' => 'Gửi tin nhắn', 'nhom' => 'chat'],
            ['name' => 'chat.view', 'ten_hien_thi' => 'Xem tin nhắn', 'nhom' => 'chat'],

            // ========== THÔNG BÁO ==========
            ['name' => 'notification.view', 'ten_hien_thi' => 'Xem thông báo', 'nhom' => 'notification'],
            ['name' => 'notification.send', 'ten_hien_thi' => 'Gửi thông báo', 'nhom' => 'notification'],
        ];

        foreach ($permissions as $permission) {
            Quyen::create($permission);
        }
    }
}