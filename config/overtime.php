<?php
// config/overtime.php

return [
    /*
    |--------------------------------------------------------------------------
    | Cấu hình lương tăng ca theo Bộ Luật Lao Động
    |--------------------------------------------------------------------------
    */
    
    // Hệ số lương tăng ca
    'rates' => [
        'ngay_thuong' => 1.5,   // 150% - Ngày thường
        'ngay_nghi'   => 2.0,   // 200% - Ngày nghỉ hàng tuần
    ],
    
    // Giới hạn giờ làm thêm (Theo Bộ Luật Lao Động)
    'limits' => [
        'max_hours_per_day' => 4,        // Không quá 50% số giờ làm việc bình thường trong ngày (8h * 50% = 4h)
        'max_hours_per_month' => 40,      // Không quá 40 giờ/tháng
        'max_hours_per_year' => 200,      // Không quá 200 giờ/năm
        'max_total_hours_per_day' => 12,  // Tổng giờ làm việc trong ngày không quá 12 giờ
    ],
    
    // Giờ làm việc tiêu chuẩn
    'standard_hours' => [
        'per_day' => 8,      // 8 giờ/ngày
        'per_week' => 48,    // 48 giờ/tuần
        'per_month' => 26,   // 26 ngày/tháng
    ],
];