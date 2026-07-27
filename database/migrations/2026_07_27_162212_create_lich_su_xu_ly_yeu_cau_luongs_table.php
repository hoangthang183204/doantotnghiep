<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_xu_ly_yeu_cau_luong', function (Blueprint $table) {

            $table->id();

           $table->foreignId('yeu_cau_xem_xet_luong_id')
      ->constrained('yeu_cau_xem_xet_luongs')
      ->cascadeOnDelete();

            $table->foreignId('nguoi_thuc_hien_id')
                ->constrained('nguoi_dung')
                ->cascadeOnDelete();

            $table->enum('hanh_dong',[
                'tao',
                'cap_nhat',
                'duyet',
                'tu_choi'
            ]);

            $table->json('du_lieu_cu')->nullable();

            $table->json('du_lieu_moi')->nullable();

            $table->text('ghi_chu')->nullable();

            $table->timestamp('thoi_gian')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_xu_ly_yeu_cau_luong');
    }
};