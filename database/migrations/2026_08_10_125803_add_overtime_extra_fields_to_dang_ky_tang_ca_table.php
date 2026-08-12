<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            // ⭐ Thêm cột người tạo đơn (có thể khác với người thực hiện)
            if (!Schema::hasColumn('dang_ky_tang_ca', 'nguoi_tao_id')) {
                $table->unsignedBigInteger('nguoi_tao_id')->nullable()->after('nguoi_dung_id');
                $table->foreign('nguoi_tao_id')->references('id')->on('nguoi_dung')->onDelete('set null');
            }
            
            // ⭐ Thêm cột loại tạo: 'nhan_vien' hoặc 'truong_phong'
            if (!Schema::hasColumn('dang_ky_tang_ca', 'loai_tao')) {
                $table->string('loai_tao')->default('nhan_vien')->after('nguoi_tao_id');
            }
            
            // ⭐ Thêm cột đánh dấu đã checkout thay thế
            if (!Schema::hasColumn('dang_ky_tang_ca', 'da_checkout_thay_the')) {
                $table->boolean('da_checkout_thay_the')->default(false)->after('da_hoan_thanh');
            }
        });
    }

    public function down()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            $table->dropForeign(['nguoi_tao_id']);
            $table->dropColumn(['nguoi_tao_id', 'loai_tao', 'da_checkout_thay_the']);
        });
    }
};