<?php

namespace App\Exports;

use App\Models\LuongNhanVien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChiTietBangLuongExport implements FromCollection, WithHeadings
{
    protected $bangLuongId;

    public function __construct($bangLuongId)
    {
        $this->bangLuongId = $bangLuongId;
    }

    public function collection()
    {
        return LuongNhanVien::with('nguoiDung.hoSo')
            ->where('bang_luong_id', $this->bangLuongId)
            ->get()
            ->map(function ($item) {
                $hoSo = $item->nguoiDung ? $item->nguoiDung->hoSo : null;

                return [
                    $item->nguoiDung->id ?? '',
                    // Lấy họ tên từ bảng ho_so_nguoi_dung nếu có
                    optional($hoSo)->ho_ten ?? trim((optional($hoSo)->ho ?? '') . ' ' . (optional($hoSo)->ten ?? '')) ?: ($item->nguoiDung->ten_dang_nhap ?? ''),

                    optional($hoSo)->so_tai_khoan ?? '',
                    optional($hoSo)->ten_ngan_hang ?? '',

                    $item->luong_co_ban,
                    $item->so_ngay_cong,
                    $item->so_ngay_cong_chuan,

                    $item->tong_phu_cap,
                    $item->tien_tang_ca,

                    $item->tong_khau_tru,
                    $item->thue_thu_nhap_ca_nhan,

                    $item->tong_luong,
                    $item->luong_thuc_nhan,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Mã NV',
            'Họ và tên',

            'Số tài khoản',
            'Tên ngân hàng',

            'Lương cơ bản',
            'Ngày công',
            'Ngày công chuẩn',

            'Tổng phụ cấp',
            'Tiền tăng ca',

            'Khấu trừ',
            'Thuế TNCN',

            'Tổng lương',
            'Thực nhận',
        ];
    }
}