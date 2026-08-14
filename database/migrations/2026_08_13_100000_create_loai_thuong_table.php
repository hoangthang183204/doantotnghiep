<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Danh mục loại thưởng — do admin/HR tự định nghĩa.
 *
 * VN có rất nhiều loại thưởng (chuyên cần, KPI, tháng 13, Tết, thâm niên,
 * sáng kiến, lễ 30/4, 2/9...) nên danh mục này để mở, không cố định enum.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loai_thuong', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ma')->unique();
            $table->text('mo_ta')->nullable();

            // Gợi ý hình thức áp dụng mặc định khi tạo khoản thưởng từ loại này
            $table->enum('hinh_thuc_mac_dinh', ['dinh_ky', 'mot_lan'])->default('mot_lan');

            // Cách quy đổi ra tiền: số tiền cố định hoặc % lương cơ bản
            $table->enum('cach_tinh', ['so_tien_co_dinh', 'phan_tram_luong_cb'])->default('so_tien_co_dinh');
            $table->decimal('gia_tri_mac_dinh', 12, 2)->default(0);

            // Thưởng có tính vào thu nhập chịu thuế TNCN hay không
            $table->boolean('chiu_thue')->default(true);

            $table->boolean('trang_thai')->default(true);
            $table->timestamps();

            $table->index(['trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_thuong');
    }
};
