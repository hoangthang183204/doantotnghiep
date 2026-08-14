<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bổ sung cho bảng lương nhân viên:
 *  - Tách giờ/tiền tăng ca theo 3 loại: ngày thường 150%, ngày nghỉ (T7/CN) 200%, lễ Tết 400%.
 *  - Tổng thưởng và phần thưởng chịu thuế TNCN.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            // --- Tăng ca tách theo loại ---
            if (!Schema::hasColumn('luong_nhan_vien', 'gio_tang_ca_ngay_thuong')) {
                $table->decimal('gio_tang_ca_ngay_thuong', 8, 2)->default(0)->after('gio_tang_ca');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'gio_tang_ca_ngay_nghi')) {
                $table->decimal('gio_tang_ca_ngay_nghi', 8, 2)->default(0)->after('gio_tang_ca_ngay_thuong');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'gio_tang_ca_le_tet')) {
                $table->decimal('gio_tang_ca_le_tet', 8, 2)->default(0)->after('gio_tang_ca_ngay_nghi');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'tien_tang_ca_ngay_thuong')) {
                $table->decimal('tien_tang_ca_ngay_thuong', 12, 2)->default(0)->after('gio_tang_ca_le_tet');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'tien_tang_ca_ngay_nghi')) {
                $table->decimal('tien_tang_ca_ngay_nghi', 12, 2)->default(0)->after('tien_tang_ca_ngay_thuong');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'tien_tang_ca_le_tet')) {
                $table->decimal('tien_tang_ca_le_tet', 12, 2)->default(0)->after('tien_tang_ca_ngay_nghi');
            }

            // --- Thưởng ---
            if (!Schema::hasColumn('luong_nhan_vien', 'tong_thuong')) {
                $table->decimal('tong_thuong', 12, 2)->default(0)->after('tong_phu_cap');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'thuong_chiu_thue')) {
                $table->decimal('thuong_chiu_thue', 12, 2)->default(0)->after('tong_thuong');
            }
        });
    }

    public function down(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            $table->dropColumn([
                'gio_tang_ca_ngay_thuong',
                'gio_tang_ca_ngay_nghi',
                'gio_tang_ca_le_tet',
                'tien_tang_ca_ngay_thuong',
                'tien_tang_ca_ngay_nghi',
                'tien_tang_ca_le_tet',
                'tong_thuong',
                'thuong_chiu_thue',
            ]);
        });
    }
};
