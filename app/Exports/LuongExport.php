<?php

namespace App\Exports;

use App\Models\Luong;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LuongExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Luong::with([
            'nguoiDung.hoSo',
            'hopDongLaoDong.chucVu'
        ])->get()->map(function ($luong, $index) {

            $tongLuong =
                $luong->luong_co_ban +
                $luong->phu_cap +
                $luong->tien_thuong -
                $luong->tien_phat;

            $hoSo = $luong->nguoiDung ? $luong->nguoiDung->hoSo : null;

            return [
                $index + 1,
                $luong->nguoiDung->ho_ten ?? '',
                $luong->hopDongLaoDong->chucVu->ten ?? '',
                $luong->hopDongLaoDong->so_hop_dong ?? '',
                optional($hoSo)->so_tai_khoan ?? '',
                optional($hoSo)->ten_ngan_hang ?? '',
                $luong->luong_co_ban,
                $luong->phu_cap,
                $tongLuong,
                optional($luong->created_at)->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'STT',
            'Nhân viên',
            'Chức vụ',
            'Hợp đồng',
            'Số tài khoản',
            'Tên ngân hàng',
            'Lương cơ bản',
            'Phụ cấp',
            'Tổng lương',
            'Ngày tạo',
        ];
    }
}