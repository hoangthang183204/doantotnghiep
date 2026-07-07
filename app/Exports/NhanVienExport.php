<?php
// app/Exports/NhanVienExport.php

namespace App\Exports;

use App\Models\HoSo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class NhanVienExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = HoSo::with([
            'nguoi_dung.chuc_vu',
            'nguoi_dung.phong_ban',
        ]);

        // Áp dụng filter nếu có
        if (!empty($this->filters['phong_ban_id'])) {
            $query->whereHas('nguoi_dung', function ($q) {
                $q->where('phong_ban_id', $this->filters['phong_ban_id']);
            });
        }

        if (isset($this->filters['trang_thai']) && $this->filters['trang_thai'] !== '') {
            $query->whereHas('nguoi_dung', function ($q) {
                $q->where('trang_thai', $this->filters['trang_thai']);
            });
        }

        return $query->orderBy('ma_nhan_vien');
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã nhân viên',
            'Họ',
            'Tên',
            'Email',
            'Số điện thoại',
            'Ngày sinh',
            'Giới tính',
            'Tình trạng hôn nhân',
            'Phòng ban',
            'Chức vụ',
            'Địa chỉ hiện tại',
            'Địa chỉ thường trú',
            'CMND/CCCD',
            'Số hộ chiếu',
            'Liên hệ khẩn cấp',
            'SĐT khẩn cấp',
            'Quan hệ khẩn cấp',
            'Chủ tài khoản',
            'Số tài khoản',
            'Ngân hàng',
            'Chi nhánh',
            'Số BHXH',
            'Mã số thuế',
            'Nơi đăng ký KCB',
            'Trạng thái',
            'Ngày vào làm',
        ];
    }

    public function map($hoSo): array
    {
        static $stt = 0;
        $stt++;

        $nguoiDung = $hoSo->nguoi_dung;

        return [
            $stt,
            $hoSo->ma_nhan_vien ?? '',
            $hoSo->ho ?? '',
            $hoSo->ten ?? '',
            $nguoiDung->email ?? '',
            $hoSo->so_dien_thoai ?? '',
            $hoSo->ngay_sinh ? $hoSo->ngay_sinh->format('d/m/Y') : '',
            $hoSo->gioi_tinh_text,
            $hoSo->tinh_trang_hon_nhan_text,
            $nguoiDung->phong_ban->ten_phong_ban ?? '',
            $nguoiDung->chuc_vu->ten ?? '',
            $hoSo->dia_chi_hien_tai ?? '',
            $hoSo->dia_chi_thuong_tru ?? '',
            $hoSo->cmnd_cccd ?? '',
            $hoSo->so_ho_chieu ?? '',
            $hoSo->lien_he_khan_cap ?? '',
            $hoSo->sdt_khan_cap ?? '',
            $hoSo->quan_he_khan_cap ?? '',
            $hoSo->chu_tai_khoan ?? '',
            $hoSo->so_tai_khoan ?? '',
            $hoSo->ten_ngan_hang ?? '',
            $hoSo->chi_nhanh_ngan_hang ?? '',
            $hoSo->so_bhxh ?? '',
            $hoSo->ma_so_thue ?? '',
            $hoSo->noi_dang_ky_kcb ?? '',
            $nguoiDung->trang_thai == 1 ? 'Đang làm việc' : 'Đã nghỉ việc',
            $nguoiDung->created_at ? $nguoiDung->created_at->format('d/m/Y') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style cho header
        $sheet->getStyle('A1:AA1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border cho toàn bộ
        $sheet->getStyle('A1:AA' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0D0D0'],
                ],
            ],
        ]);

        // Chiều cao dòng header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Đóng băng dòng header
        $sheet->freezePane('A2');

        return $sheet;
    }
}