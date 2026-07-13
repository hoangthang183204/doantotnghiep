<?php
// app/Exports/TruongPhong/OverviewReportExport.php

namespace App\Exports\TruongPhong;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OverviewReportExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('truong-phong.bao-cao.export-overview', [
            'data' => $this->data,
        ]);
    }
}