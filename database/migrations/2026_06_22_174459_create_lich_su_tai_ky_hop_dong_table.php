<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lich_su_tai_ky_hop_dong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hop_dong_cu_id')->constrained('hop_dong_lao_dong')->onDelete('cascade');
            $table->foreignId('hop_dong_moi_id')->constrained('hop_dong_lao_dong')->onDelete('cascade');
            $table->foreignId('nguoi_thuc_hien_id')->constrained('nguoi_dung')->onDelete('cascade');
            $table->text('ly_do_tai_ky')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_su_tai_ky_hop_dong');
    }
};