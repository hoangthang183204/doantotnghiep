<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vi_tri_cong_ty', function (Blueprint $table) {
            $table->id();
            $table->string('dia_chi');
            $table->tinyInteger('su_dung_kiem_tra_ip')->default(1);
            $table->string('dai_ip_cho_phep')->nullable();
            $table->string('yeu_cau_ten_wifi')->nullable();
            $table->tinyInteger('yeu_cau_loc_dia_chi_mac')->default(0);
            $table->json('dia_chi_mac_duoc_phep')->nullable();
            $table->foreignId('chi_nhanh_id')->nullable()->constrained('chi_nhanh_cong_ty')->nullOnDelete();
            $table->integer('ban_kinh_cho_phep')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vi_tri_cong_ty');
    }
};