<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            // Kiểm tra nếu chưa có cột deleted_at thì mới thêm
            if (!Schema::hasColumn('hop_dong_lao_dong', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};