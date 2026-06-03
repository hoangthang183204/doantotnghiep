<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ung_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tin_tuyen_dung_id')->constrained('tin_tuyen_dung')->cascadeOnDelete();
            $table->string('ma_ho_so')->unique();
            $table->string('ho');
            $table->string('ten');
            $table->string('email');
            $table->string('so_dien_thoai');
            $table->text('dia_chi')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->nullable();
            $table->string('trinh_do_hoc_van')->nullable();
            $table->decimal('so_nam_kinh_nghiem', 5, 1)->default(0);
            $table->decimal('luong_hien_tai', 15, 2)->nullable();
            $table->decimal('luong_mong_muon', 15, 2)->nullable();
            $table->string('duong_dan_cv')->nullable();
            $table->text('thu_xin_viec')->nullable();
            $table->string('url_portfolio')->nullable();
            $table->string('url_linkedin')->nullable();
            $table->json('ky_nang')->nullable();
            $table->date('ngay_co_the_lam_viec')->nullable();
            $table->enum('nguon_ung_tuyen', ['linkedin', 'facebook', 'web', 'gioi_thieu', 'khac'])->nullable();
            $table->string('ten_nguoi_gioi_thieu')->nullable();
            $table->enum('trang_thai', ['moi_nop', 'cho_phong_van', 'da_phong_van', 'trung_tuyen', 'tu_choi'])->default('moi_nop');
            $table->decimal('diem_phong_van', 5, 2)->nullable();
            $table->text('ghi_chu_phong_van')->nullable();
            $table->text('ly_do_tu_choi')->nullable();
            $table->foreignId('nguoi_dung_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_nop')->useCurrent();
            $table->foreignId('nguoi_cap_nhat_cuoi_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ung_vien');
    }
};