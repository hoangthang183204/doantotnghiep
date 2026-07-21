<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yeu_cau_xem_xet_luongs', function (Blueprint $table) {
            $table->id();

            // Phiếu lương cần xem xét
            $table->foreignId('luong_nhan_vien_id')
                ->constrained('luong_nhan_vien')
                ->cascadeOnDelete();

            // Người gửi yêu cầu
            $table->foreignId('nguoi_dung_id')
                ->constrained('nguoi_dung')
                ->cascadeOnDelete();

            // Lý do
            $table->text('ly_do');

            // Trạng thái
            $table->enum('trang_thai', [
                'cho_duyet',
                'da_duyet',
                'tu_choi'
            ])->default('cho_duyet');

            // Phản hồi của HR
            $table->text('phan_hoi')->nullable();

            // Người duyệt
            $table->foreignId('nguoi_duyet_id')
                ->nullable()
                ->constrained('nguoi_dung')
                ->nullOnDelete();

            $table->timestamp('thoi_gian_duyet')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yeu_cau_xem_xet_luongs');
    }
};
