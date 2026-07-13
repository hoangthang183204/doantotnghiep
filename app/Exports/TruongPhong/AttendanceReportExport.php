<?php
// app/Exports/TruongPhong/AttendanceReportExport.php

namespace App\Exports\TruongPhong;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AttendanceReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $phongBan;
    protected $thang;
    protected $nam;

    public function __construct($data, $phongBan, $thang, $nam)
    {
        $this->data = $data;
        $this->phongBan = $phongBan;
        $this->thang = $thang;
        $this->nam = $nam;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã nhân viên',
            'Họ tên',
            'Chức vụ',
            'Số ngày công',
            'Đi muộn',
            'Về sớm',
            'Tổng giờ làm',
        ];
    }

    public function map($nv): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $nv['ma_nhan_vien'],
            $nv['ho_ten'],
            $nv['chuc_vu'],
            $nv['so_ngay_cham_cong'],
            $nv['so_ngay_di_muon'],
            $nv['so_ngay_ve_som'],
            $nv['tong_gio_lam'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:H1')->applyFromArray([
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

        // Border
        $sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0D0D0'],
                ],
            ],
        ]);

        // Chiều cao header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Đóng băng dòng header
        $sheet->freezePane('A2');

        // Thêm tiêu đề báo cáo
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'BÁO CÁO CHẤM CÔNG');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Phòng: ' . $this->phongBan->ten_phong_ban . ' | Tháng: ' . $this->thang . '/' . $this->nam);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 12],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        return $sheet;
    }
}