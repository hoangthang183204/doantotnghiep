<?php

// app/Helpers/helpers.php

if (!function_exists('getTrangThaiUngVien')) {
    function getTrangThaiUngVien($status)
    {
        $statuses = [
            'moi_nop' => 'Mới nộp',
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'hen_phong_van' => 'Hẹn phỏng vấn',
            'cho_phong_van' => 'Chờ phỏng vấn',
            'da_phong_van' => 'Đã phỏng vấn',
            'dat' => 'Trúng tuyển',
            'khong_dat' => 'Không đạt',
            'da_huy' => 'Đã hủy',
            'tam_dung' => 'Tạm dừng',
        ];

        return $statuses[$status] ?? $status;
    }
}

if (!function_exists('getTrangThaiColorUngVien')) {
    function getTrangThaiColorUngVien($status)
    {
        $colors = [
            'moi_nop' => 'blue',
            'cho_duyet' => 'yellow',
            'da_duyet' => 'green',
            'hen_phong_van' => 'purple',
            'cho_phong_van' => 'indigo',
            'da_phong_van' => 'cyan',
            'dat' => 'green',
            'khong_dat' => 'red',
            'da_huy' => 'gray',
            'tam_dung' => 'orange',
        ];

        return $colors[$status] ?? 'gray';
    }
}

if (!function_exists('getTrangThaiBadgeUngVien')) {
    function getTrangThaiBadgeUngVien($status)
    {
        $text = getTrangThaiUngVien($status);
        $color = getTrangThaiColorUngVien($status);
        
        $colorClasses = [
            'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'yellow' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'green' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'red' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            'cyan' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
            'gray' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
            'orange' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
        ];

        $class = $colorClasses[$color] ?? $colorClasses['gray'];

        return '<span class="px-3 py-1 rounded-full text-xs font-semibold ' . $class . '">' . $text . '</span>';
    }
}