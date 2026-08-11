<?php
// Tạo migration: php artisan make:migration add_overtime_extra_fields_to_dang_ky_tang_ca_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            // ⭐ Người tạo đơn (có thể khác với người thực hiện)
            $table->unsignedBigInteger('nguoi_tao_id')->nullable()->after('nguoi_dung_id');
            
            // ⭐ Loại tạo: 'nhan_vien' hoặc 'truong_phong'
            $table->string('loai_tao')->default('nhan_vien')->after('nguoi_tao_id');
            
            // ⭐ Đánh dấu đã checkout thay thế (không cần checkout giờ hành chính)
            $table->boolean('da_checkout_thay_the')->default(false)->after('da_hoan_thanh');
            
            // Foreign key
            $table->foreign('nguoi_tao_id')->references('id')->on('nguoi_dung')->onDelete('set null');
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