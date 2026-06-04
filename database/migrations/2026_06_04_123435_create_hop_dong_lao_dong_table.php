<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hop_dong_lao_dong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung');
            $table->foreignId('chuc_vu_id')->constrained('chuc_vu');
            $table->string('so_hop_dong')->unique();
            $table->enum('loai_hop_dong', ['thu_viec', 'xac_dinh_thoi_han', 'khong_xac_dinh_thoi_han', 'mua_vu']);
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc')->nullable();
            $table->decimal('luong_co_ban', 12, 2);
            $table->decimal('phu_cap', 15, 2)->nullable();
            $table->string('hinh_thuc_lam_viec', 50)->nullable();
            $table->string('dia_diem_lam_viec', 100)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->text('ly_do_huy')->nullable();
            $table->text('duong_dan_file')->nullable();
            $table->text('file_dinh_kem')->nullable();
            $table->text('file_hop_dong_da_ky')->nullable();
            $table->text('dieu_khoan')->nullable();
            $table->enum('trang_thai_hop_dong', ['tao_moi', 'chua_hieu_luc', 'hieu_luc', 'het_han', 'huy_bo'])->default('tao_moi');
            $table->enum('trang_thai_ky', ['cho_ky', 'da_ky', 'tu_choi_ky'])->default('cho_ky');
            $table->string('trang_thai_tai_ky')->nullable();
            $table->foreignId('nguoi_ky_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_ky')->nullable();
            $table->foreignId('nguoi_huy_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_huy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hop_dong_lao_dong');
    }
};