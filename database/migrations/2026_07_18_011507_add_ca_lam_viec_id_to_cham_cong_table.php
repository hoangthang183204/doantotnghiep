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
        Schema::table('cham_cong', function (Blueprint $table) {
            // Kiểm tra nếu cột chưa tồn tại thì mới thêm
            if (!Schema::hasColumn('cham_cong', 'ca_lam_viec_id')) {
                $table->unsignedBigInteger('ca_lam_viec_id')->nullable()->after('loai_cham_cong');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            // Chỉ xóa cột nếu nó tồn tại
            if (Schema::hasColumn('cham_cong', 'ca_lam_viec_id')) {
                $table->dropColumn('ca_lam_viec_id');
            }
        });
    }
};