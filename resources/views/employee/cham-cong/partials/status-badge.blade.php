{{-- resources/views/employee/cham-cong/partials/status-badge.blade.php --}}
@php
    $statusMap = [
        'den_som' => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', 'Đến sớm', 'fas fa-arrow-up'],
        'dung_gio' => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300', 'Đúng giờ', 'fas fa-check-circle'],
        'di_muon' => ['bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300', 'Đi muộn', 'fas fa-exclamation-triangle'],
        've_som' => ['bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300', 'Về sớm', 'fas fa-exclamation-triangle'],
        'tang_ca' => ['bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300', 'Tăng ca', 'fas fa-clock'],
        'khong_cham_cong' => ['bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300', 'Chưa chấm công', 'fas fa-minus-circle'],
        'nghi_phep' => ['bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300', 'Nghỉ phép', 'fas fa-calendar-check'],
        'vang_mat' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300', 'Vắng mặt', 'fas fa-times-circle'],
    ];
    $status = $statusMap[$status] ?? ['bg-gray-100 text-gray-700', $status, 'fas fa-question-circle'];
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status[0] }}">
    <i class="{{ $status[2] }} mr-1"></i> {{ $status[1] }}
</span>