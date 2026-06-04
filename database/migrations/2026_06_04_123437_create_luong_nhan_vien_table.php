<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('luong_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bang_luong_id')->constrained('bang_luong')->cascadeOnDelete();
            $table->tinyInteger('luong_thang');
            $table->year('luong_nam');
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->decimal('luong_co_ban', 12, 2);
            $table->decimal('tong_phu_cap', 12, 2)->default(0);
            $table->decimal('tong_khau_tru', 12, 2)->default(0);
            $table->decimal('tong_luong', 12, 2);
            $table->decimal('luong_thuc_nhan', 12, 2);
            $table->decimal('so_ngay_cong', 5, 2)->default(0);
            $table->decimal('gio_tang_ca', 8, 2)->default(0);
            $table->decimal('cong_tang_ca', 12, 2)->default(0);
            $table->decimal('ngay_nghi_phep', 5, 2)->default(0);
            $table->decimal('ngay_nghi_khong_phep', 5, 2)->default(0);
            $table->decimal('ngay_le', 5, 2)->default(0);
            $table->decimal('thue_thu_nhap_ca_nhan', 12, 2)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luong_nhan_vien');
    }
};