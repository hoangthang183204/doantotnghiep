<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bổ sung các cột chi tiết phục vụ tính lương theo công thức:
     *  - tong_phu_cap      : tổng phụ cấp (đã bị migration trước xoá nhầm, thêm lại)
     *  - luong_theo_cong   : lương cơ bản đã quy đổi theo số ngày công thực tế
     *  - tien_tang_ca      : tiền tăng ca (quy ra tiền từ giờ tăng ca)
     *  - so_ngay_cong_chuan: số ngày công chuẩn của tháng (mặc định 26)
     */
    public function up(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            if (!Schema::hasColumn('luong_nhan_vien', 'tong_phu_cap')) {
                $table->decimal('tong_phu_cap', 12, 2)->default(0)->after('luong_co_ban');
            }
            $table->decimal('luong_theo_cong', 12, 2)->default(0)->after('tong_phu_cap');
            $table->decimal('tien_tang_ca', 12, 2)->default(0)->after('luong_theo_cong');
            $table->decimal('so_ngay_cong_chuan', 5, 2)->default(26)->after('so_ngay_cong');
        });
    }

    public function down(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            $table->dropColumn(['luong_theo_cong', 'tien_tang_ca', 'so_ngay_cong_chuan']);
            // tong_phu_cap giữ nguyên vì là cột gốc của bảng
        });
    }
};
