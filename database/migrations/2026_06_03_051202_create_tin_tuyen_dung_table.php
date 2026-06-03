<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tin_tuyen_dung', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->string('ma')->unique();
            $table->foreignId('phong_ban_id')->nullable()->constrained('phong_ban')->nullOnDelete();
            $table->foreignId('chuc_vu_id')->nullable()->constrained('chuc_vu')->nullOnDelete();
            $table->foreignId('vai_tro_id')->nullable()->constrained('vai_tro')->nullOnDelete();
            $table->enum('loai_hop_dong', ['khong_xac_dinh', 'xac_dinh_thoi_han', 'thoi_vu'])->nullable();
            $table->enum('cap_do_kinh_nghiem', ['thuc_tap', 'junior', 'senior', 'chuyen_gia'])->nullable();
            $table->tinyInteger('kinh_nghiem_toi_thieu')->nullable();
            $table->tinyInteger('kinh_nghiem_toi_da')->nullable();
            $table->decimal('luong_toi_thieu', 15, 2)->nullable();
            $table->decimal('luong_toi_da', 15, 2)->nullable();
            $table->smallInteger('so_vi_tri')->default(1);
            $table->longText('mo_ta_cong_viec');
            $table->json('yeu_cau')->nullable();
            $table->json('phuc_loi')->nullable();
            $table->json('ky_nang_yeu_cau')->nullable();
            $table->string('trinh_do_hoc_van')->nullable();
            $table->date('han_nop_ho_so');
            $table->tinyInteger('lam_viec_tu_xa')->default(0);
            $table->tinyInteger('tuyen_gap')->default(0);
            $table->enum('trang_thai', ['dang_dang', 'da_dong', 'huy'])->default('dang_dang');
            $table->foreignId('nguoi_dang_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_dang')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_tuyen_dung');
    }
};