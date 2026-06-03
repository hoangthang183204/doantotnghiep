<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('so_du_nghi_phep_nhan_vien', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
        $table->foreignId('loai_nghi_phep_id')->constrained('loai_nghi_phep');
        $table->year('nam');
        $table->decimal('so_ngay_duoc_cap', 8, 2)->default(0);
        $table->decimal('so_ngay_da_dung', 8, 2)->default(0);
        $table->decimal('so_ngay_cho_duyet', 8, 2)->default(0);
        $table->decimal('so_ngay_con_lai', 8, 2)->default(0);
        $table->decimal('so_ngay_chuyen_tu_nam_truoc', 8, 2)->default(0);
        $table->timestamps();
        
        $table->unique(['nguoi_dung_id', 'loai_nghi_phep_id', 'nam'], 'sud_nghi_phep_unique');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('so_du_nghi_phep_nhan_vien');
    }
};