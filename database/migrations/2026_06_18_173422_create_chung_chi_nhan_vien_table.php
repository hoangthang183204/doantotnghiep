<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chung_chi_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ho_so_id')->constrained('ho_so_nguoi_dung')->onDelete('cascade');
            $table->string('ten_chung_chi');
            $table->string('to_chuc_cap');
            $table->year('nam_cap');
            $table->date('ngay_het_han')->nullable();
            $table->string('file_dinh_kem')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chung_chi_nhan_vien');
    }
};