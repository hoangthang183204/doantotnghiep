<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phu_cap_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('phu_cap_id')->constrained('phu_cap');
            $table->decimal('so_tien', 12, 2);
            $table->date('ngay_hieu_luc');
            $table->date('ngay_ket_thuc')->nullable();
            $table->enum('trang_thai', ['hieu_luc', 'tam_dung', 'ket_thuc'])->default('hieu_luc');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phu_cap_nhan_vien');
    }
};