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
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('chuc_vu_id')->nullable()->constrained('chuc_vu')->nullOnDelete();
            $table->string('so_hop_dong')->unique();
            $table->enum('loai_hop_dong', ['khong_xac_dinh', 'xac_dinh_thoi_han', 'thoi_vu']);
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc')->nullable();
            $table->decimal('luong_co_ban', 15, 2)->default(0);
            $table->decimal('phu_cap', 15, 2)->default(0);
            $table->string('hinh_thuc_lam_viec', 50)->nullable();
            $table->string('dia_diem_lam_viec', 100)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->text('ly_do_huy')->nullable();
            $table->string('duong_dan_file')->nullable();
            $table->text('file_dinh_kem')->nullable();
            $table->string('file_hop_dong_da_ky')->nullable();
            $table->text('dieu_khoan')->nullable();
            $table->enum('trang_thai_hop_dong', ['dang_soan', 'cho_ky', 'da_ky', 'huy', 'het_han'])->default('dang_soan');
            $table->enum('trang_thai_ky', ['chua_ky', 'da_ky'])->default('chua_ky');
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
