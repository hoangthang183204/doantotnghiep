<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Chốt lương tự động + tự động gửi phiếu lương qua email
// lúc 02:00 ngày 1 hàng tháng (chốt cho tháng trước)
Schedule::command('luong:chot --gui-email')
    ->monthlyOn(1, '02:00')
    ->timezone('Asia/Ho_Chi_Minh')
    ->withoutOverlapping();
Schedule::command('leave:reset-annual')->yearly();

