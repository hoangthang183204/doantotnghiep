<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khau_tru_luong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('luong_nhan_vien_id')->constrained('luong_nhan_vien')->cascadeOnDelete();
            $table->enum('loai_khau_tru', ['bao_hiem_xa_hoi', 'bao_hiem_y_te', 'bao_hiem_that_nghiep', 'thue_thu_nhap', 'khac']);
            $table->decimal('so_tien', 15, 2)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khau_tru_luong');
    }
};