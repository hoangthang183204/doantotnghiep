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
            $table->foreignId('phong_ban_id')->constrained('phong_ban');
            $table->foreignId('chuc_vu_id')->constrained('chuc_vu');
            $table->foreignId('vai_tro_id')->nullable()->constrained('vai_tro')->nullOnDelete();
            $table->enum('loai_hop_dong', ['thu_viec', 'xac_dinh_thoi_han', 'khong_xac_dinh_thoi_han']);
            $table->enum('cap_do_kinh_nghiem', ['intern', 'fresher', 'junior', 'middle', 'senior']);
            $table->tinyInteger('kinh_nghiem_toi_thieu')->default(0);
            $table->tinyInteger('kinh_nghiem_toi_da')->default(0);
            $table->decimal('luong_toi_thieu', 12, 2)->nullable();
            $table->decimal('luong_toi_da', 12, 2)->nullable();
            $table->smallInteger('so_vi_tri')->default(1);
            $table->longText('mo_ta_cong_viec');
            $table->json('yeu_cau')->nullable();
            $table->json('phuc_loi')->nullable();
            $table->json('ky_nang_yeu_cau')->nullable();
            $table->string('trinh_do_hoc_van')->nullable();
            $table->date('han_nop_ho_so');
            $table->boolean('lam_viec_tu_xa')->default(false);
            $table->boolean('tuyen_gap')->default(false);
            $table->enum('trang_thai', ['nhap', 'dang_tuyen', 'tam_dung', 'ket_thuc'])->default('nhap');
            $table->foreignId('nguoi_dang_id')->constrained('nguoi_dung');
            $table->timestamp('thoi_gian_dang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_tuyen_dung');
    }
};