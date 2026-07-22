<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lưu lại đầy đủ căn cứ tính khấu trừ của từng phiếu lương để phiếu lương
     * diễn giải được "trừ gì – trừ vào đâu – trừ như thế nào":
     *  - phu_cap_chiu_thue        : phần phụ cấp phải chịu thuế TNCN
     *  - bhxh / bhyt / bhtn       : từng khoản bảo hiểm bắt buộc
     *  - tong_bao_hiem            : tổng bảo hiểm bắt buộc (8% + 1.5% + 1%)
     *  - so_nguoi_phu_thuoc       : số NPT được tính giảm trừ trong kỳ
     *  - giam_tru_ban_than        : mức giảm trừ bản thân tại kỳ tính lương
     *  - giam_tru_nguoi_phu_thuoc : tổng giảm trừ cho NPT
     *  - giam_tru_gia_canh        : bản thân + NPT
     *  - thu_nhap_chiu_thue       : căn cứ trước khi trừ bảo hiểm & giảm trừ
     *  - thu_nhap_tinh_thue       : căn cứ áp biểu luỹ tiến
     *  - tong_khau_tru_khac       : tạm ứng, phạt, bồi thường...
     *
     * Các cột là snapshot: mức giảm trừ đổi theo luật cũng không làm sai
     * phiếu lương của những kỳ đã chốt.
     */
    public function up(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            $cols = [
                'phu_cap_chiu_thue'        => 'tong_phu_cap',
                'bhxh'                     => 'thue_thu_nhap_ca_nhan',
                'bhyt'                     => 'thue_thu_nhap_ca_nhan',
                'bhtn'                     => 'thue_thu_nhap_ca_nhan',
                'tong_bao_hiem'            => 'thue_thu_nhap_ca_nhan',
                'giam_tru_ban_than'        => 'thue_thu_nhap_ca_nhan',
                'giam_tru_nguoi_phu_thuoc' => 'thue_thu_nhap_ca_nhan',
                'giam_tru_gia_canh'        => 'thue_thu_nhap_ca_nhan',
                'thu_nhap_chiu_thue'       => 'thue_thu_nhap_ca_nhan',
                'thu_nhap_tinh_thue'       => 'thue_thu_nhap_ca_nhan',
                'tong_khau_tru_khac'       => 'thue_thu_nhap_ca_nhan',
            ];

            foreach ($cols as $col => $after) {
                if (!Schema::hasColumn('luong_nhan_vien', $col)) {
                    $table->decimal($col, 15, 2)->default(0)->after($after);
                }
            }

            if (!Schema::hasColumn('luong_nhan_vien', 'so_nguoi_phu_thuoc')) {
                $table->unsignedSmallInteger('so_nguoi_phu_thuoc')
                    ->default(0)
                    ->after('thue_thu_nhap_ca_nhan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            $table->dropColumn([
                'phu_cap_chiu_thue',
                'bhxh',
                'bhyt',
                'bhtn',
                'tong_bao_hiem',
                'so_nguoi_phu_thuoc',
                'giam_tru_ban_than',
                'giam_tru_nguoi_phu_thuoc',
                'giam_tru_gia_canh',
                'thu_nhap_chiu_thue',
                'thu_nhap_tinh_thue',
                'tong_khau_tru_khac',
            ]);
        });
    }
};
