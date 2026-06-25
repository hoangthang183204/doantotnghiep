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
        return LuongNhanVien::with('nguoiDung')
            ->where('bang_luong_id', $this->bangLuongId)
            ->get()
            ->map(function ($item) {

                return [
                    $item->nguoiDung->id ?? '',
                    $item->nguoiDung->ten_dang_nhap ?? '',

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
            'Tên nhân viên',

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