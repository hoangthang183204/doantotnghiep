<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ho_so_nguoi_dung', function (Blueprint $table) {
            // Thông tin ngân hàng
            $table->string('chu_tai_khoan')->nullable()->after('anh_cccd_sau');
            $table->string('so_tai_khoan')->nullable()->after('chu_tai_khoan');
            $table->string('ten_ngan_hang')->nullable()->after('so_tai_khoan');
            $table->string('chi_nhanh_ngan_hang')->nullable()->after('ten_ngan_hang');
            
            // Thông tin bảo hiểm & thuế
            $table->string('so_bhxh')->nullable()->after('chi_nhanh_ngan_hang');
            $table->string('ma_so_thue')->nullable()->after('so_bhxh');
            $table->string('noi_dang_ky_kcb')->nullable()->after('ma_so_thue');
        });
    }

    public function down(): void
    {
        Schema::table('ho_so_nguoi_dung', function (Blueprint $table) {
            $table->dropColumn([
                'chu_tai_khoan',
                'so_tai_khoan',
                'ten_ngan_hang',
                'chi_nhanh_ngan_hang',
                'so_bhxh',
                'ma_so_thue',
                'noi_dang_ky_kcb'
            ]);
        });
    }
};