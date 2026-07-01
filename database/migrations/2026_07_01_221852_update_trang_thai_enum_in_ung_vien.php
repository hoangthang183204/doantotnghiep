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
        // Cách 2: Chuyển sang VARCHAR để linh hoạt hơn
        Schema::table('ung_vien', function (Blueprint $table) {
            // Đổi sang VARCHAR trước
            DB::statement("ALTER TABLE ung_vien MODIFY trang_thai VARCHAR(50) DEFAULT 'moi_nop'");

            // Sau đó có thể thêm index nếu cần
            $table->index('trang_thai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            // Rollback về ENUM cũ
            DB::statement("ALTER TABLE ung_vien MODIFY trang_thai ENUM('moi_nop', 'cho_duyet', 'da_duyet', 'dat', 'khong_dat') DEFAULT 'moi_nop'");
        });
    }
};
