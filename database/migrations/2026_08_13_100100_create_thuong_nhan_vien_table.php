<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Khoản thưởng gán cho nhân viên.
 *
 * Hai hình thức:
 *  - dinh_ky : thưởng mặc định lặp lại HÀNG THÁNG trong khoảng hiệu lực
 *              (ngay_bat_dau → ngay_ket_thuc, để trống ngày kết thúc = áp dụng vô thời hạn).
 *              VD: thưởng chuyên cần 300k/tháng, thưởng trách nhiệm 10% lương CB.
 *  - mot_lan : thưởng chỉ áp dụng ĐÚNG 1 kỳ lương (thang/nam).
 *              VD: thưởng Tết, thưởng dự án, thưởng lễ 2/9.
 *
 * TinhLuongService cộng các khoản "hieu_luc" vào tổng lương của tháng tương ứng.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thuong_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('loai_thuong_id')->constrained('loai_thuong')->cascadeOnDelete();

            $table->enum('hinh_thuc', ['dinh_ky', 'mot_lan'])->default('mot_lan');

            // Cách tính riêng cho khoản này (mặc định lấy theo loại thưởng)
            $table->enum('cach_tinh', ['so_tien_co_dinh', 'phan_tram_luong_cb'])->default('so_tien_co_dinh');
            $table->decimal('gia_tri', 12, 2)->default(0);

            // Ghi đè thuế của loại thưởng (null = theo loại thưởng)
            $table->boolean('chiu_thue')->nullable();

            // Áp dụng 1 lần: kỳ lương cụ thể
            $table->unsignedTinyInteger('thang')->nullable();
            $table->unsignedSmallInteger('nam')->nullable();

            // Áp dụng định kỳ: khoảng hiệu lực
            $table->date('ngay_bat_dau')->nullable();
            $table->date('ngay_ket_thuc')->nullable();

            $table->string('ly_do', 255)->nullable();
            $table->enum('trang_thai', ['hieu_luc', 'tam_dung', 'huy'])->default('hieu_luc');

            $table->foreignId('nguoi_tao_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamps();

            $table->index(['nguoi_dung_id', 'thang', 'nam']);
            $table->index(['hinh_thuc', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thuong_nhan_vien');
    }
};
