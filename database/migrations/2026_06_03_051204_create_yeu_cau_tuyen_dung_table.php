<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_cau_tuyen_dung', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->foreignId('nguoi_tao_id')->constrained('nguoi_dung');
            $table->foreignId('phong_ban_id')->constrained('phong_ban');
            $table->foreignId('chuc_vu_id')->constrained('chuc_vu');
            $table->integer('so_luong')->default(1);
            $table->enum('loai_hop_dong', ['khong_xac_dinh', 'xac_dinh_thoi_han', 'thoi_vu']);
            $table->integer('luong_toi_thieu')->nullable();
            $table->integer('luong_toi_da')->nullable();
            $table->string('trinh_do_hoc_van')->nullable();
            $table->integer('kinh_nghiem_toi_thieu')->nullable();
            $table->integer('kinh_nghiem_toi_da')->nullable();
            $table->text('mo_ta_cong_viec');
            $table->text('yeu_cau');
            $table->json('ky_nang_yeu_cau')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet');
            $table->enum('trang_thai_dang', ['chua_dang', 'da_dang'])->default('chua_dang');
            $table->foreignId('nguoi_duyet_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_duyet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_cau_tuyen_dung');
    }
};