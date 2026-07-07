<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bảng "khấu trừ khác" — các khoản trừ lương thủ công theo nhân viên/tháng:
 * tạm ứng, phạt vi phạm, bồi thường, khác...
 * Được TinhLuongService cộng vào tổng khấu trừ khi tính lương tháng.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khau_tru_khac', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoi_dung_id');
            $table->unsignedTinyInteger('thang');   // 1..12
            $table->unsignedSmallInteger('nam');     // 2000..2100
            $table->enum('loai', ['tam_ung', 'phat', 'boi_thuong', 'khac'])->default('khac');
            $table->decimal('so_tien', 12, 2)->default(0);
            $table->string('ly_do', 255)->nullable();
            $table->enum('trang_thai', ['hieu_luc', 'huy'])->default('hieu_luc');
            $table->unsignedBigInteger('nguoi_tao_id')->nullable();
            $table->timestamps();

            $table->index(['nguoi_dung_id', 'thang', 'nam']);
            $table->index(['thang', 'nam', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khau_tru_khac');
    }
};
