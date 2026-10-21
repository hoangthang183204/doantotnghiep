<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cham_cong') && !$this->coIndex('cham_cong', 'cham_cong_ngay_nhan_vien_index')) {
            Schema::table('cham_cong', function (Blueprint $table) {
                $table->index(['ngay_cham_cong', 'nguoi_dung_id'], 'cham_cong_ngay_nhan_vien_index');
            });
        }

        if (Schema::hasTable('luong_nhan_vien') && !$this->coIndex('luong_nhan_vien', 'luong_nv_ky_index')) {
            Schema::table('luong_nhan_vien', function (Blueprint $table) {
                $table->index(['nguoi_dung_id', 'luong_nam', 'luong_thang'], 'luong_nv_ky_index');
            });
        }

        if (Schema::hasTable('ho_so_nguoi_dung')) {
            $this->boUnique('ho_so_nguoi_dung', 'cmnd_cccd');
            foreach (['cmnd_cccd', 'so_ho_chieu', 'ma_so_thue', 'so_bhxh', 'so_tai_khoan'] as $cot) {
                if (Schema::hasColumn('ho_so_nguoi_dung', $cot)) {
                    DB::statement("ALTER TABLE ho_so_nguoi_dung MODIFY {$cot} TEXT NULL");
                }
            }
            Schema::table('ho_so_nguoi_dung', function (Blueprint $table) {
                if (!Schema::hasColumn('ho_so_nguoi_dung', 'cmnd_cccd_hash')) {
                    $table->string('cmnd_cccd_hash', 64)->nullable()->unique();
                }
                if (!Schema::hasColumn('ho_so_nguoi_dung', 'ma_so_thue_hash')) {
                    $table->string('ma_so_thue_hash', 64)->nullable()->unique();
                }
            });
        }
    }

    public function down(): void
    {
        // Giữ dữ liệu đã mã hóa. Không thu hẹp cột về varchar.
    }

    private function coIndex(string $bang, string $ten): bool
    {
        $dong = DB::select('SHOW INDEX FROM `' . $bang . '` WHERE Key_name = ?', [$ten]);
        return count($dong) > 0;
    }

    private function boUnique(string $bang, string $cot): void
    {
        $dong = DB::select('SHOW INDEX FROM `' . $bang . '` WHERE Column_name = ? AND Non_unique = 0', [$cot]);
        foreach ($dong as $chiMuc) {
            if ($chiMuc->Key_name === 'PRIMARY') {
                continue;
            }
            DB::statement('ALTER TABLE `' . $bang . '` DROP INDEX `' . $chiMuc->Key_name . '`');
        }
    }
};
