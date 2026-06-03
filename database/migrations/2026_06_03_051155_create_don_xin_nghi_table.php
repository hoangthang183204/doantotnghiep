<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_xin_nghi', function (Blueprint $table) {
            $table->id();
            $table->string('ma_don_nghi')->unique();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('loai_nghi_phep_id')->constrained('loai_nghi_phep');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->decimal('so_ngay_nghi', 5, 2);
            $table->text('ly_do');
            $table->json('tai_lieu_ho_tro')->nullable();
            $table->string('lien_he_khan_cap')->nullable();
            $table->string('sdt_khan_cap')->nullable();
            $table->foreignId('ban_giao_cho_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->text('ghi_chu_ban_giao')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi', 'da_huy'])->default('cho_duyet');
            $table->tinyInteger('cap_duyet_hien_tai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_xin_nghi');
    }
};