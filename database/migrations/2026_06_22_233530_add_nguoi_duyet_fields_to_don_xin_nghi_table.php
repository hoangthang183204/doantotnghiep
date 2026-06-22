<?php
// database/migrations/xxxx_xx_xx_add_nguoi_duyet_fields_to_don_xin_nghi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('don_xin_nghi', function (Blueprint $table) {
            // Thêm cột ghi_chu nếu chưa có
            if (!Schema::hasColumn('don_xin_nghi', 'ghi_chu')) {
                $table->text('ghi_chu')->nullable()->after('ly_do');
            }
            
            // Thêm cột nguoi_duyet_id và thoi_gian_duyet
            $table->foreignId('nguoi_duyet_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_duyet')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('don_xin_nghi', function (Blueprint $table) {
            $table->dropForeign(['nguoi_duyet_id']);
            $table->dropColumn(['nguoi_duyet_id', 'thoi_gian_duyet']);
        });
    }
};