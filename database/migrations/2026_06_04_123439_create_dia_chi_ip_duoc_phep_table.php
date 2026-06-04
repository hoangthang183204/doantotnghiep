<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dia_chi_ip_duoc_phep', function (Blueprint $table) {
            $table->id();
            $table->string('dia_chi_ip', 45)->nullable();
            $table->string('dai_ip_bat_dau', 45)->nullable();
            $table->string('dai_ip_ket_thuc', 45)->nullable();
            $table->string('ten_vi_tri')->nullable();
            $table->text('mo_ta')->nullable();
            // SỬA: Bỏ constrained(), chỉ để unsignedBigInteger
            $table->unsignedBigInteger('chi_nhanh_id')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dia_chi_ip_duoc_phep');
    }
};