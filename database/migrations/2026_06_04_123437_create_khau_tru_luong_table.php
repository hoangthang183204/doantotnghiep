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
            // SỬA: Bỏ constrained() để không tạo foreign key
            $table->unsignedBigInteger('luong_nhan_vien_id');
            $table->enum('loai_khau_tru', ['bhxh', 'bhyt', 'bhtn', 'thue_tncn', 'khau_tru_khac']);
            $table->decimal('so_tien', 12, 2);
            $table->text('ghi_chu')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khau_tru_luong');
    }
};