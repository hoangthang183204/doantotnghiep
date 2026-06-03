<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ky_nang_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('ky_nang_id')->constrained('ky_nang')->cascadeOnDelete();
            $table->enum('trinh_do', ['co_ban', 'trung_binh', 'kha', 'tot', 'xuat_sac'])->default('trung_binh');
            $table->decimal('so_nam_kinh_nghiem', 5, 1)->default(0);
            $table->string('chung_chi')->nullable();
            $table->date('ngay_cap_chung_chi')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->tinyInteger('da_xac_minh')->default(0);
            $table->foreignId('nguoi_xac_minh_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamps();
            
            $table->unique(['nguoi_dung_id', 'ky_nang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ky_nang_nhan_vien');
    }
};