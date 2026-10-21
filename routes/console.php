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
Schedule::command('cham-cong:nhac')->dailyAt('08:45')->timezone('Asia/Ho_Chi_Minh')->withoutOverlapping();
Schedule::command('db:backup')->dailyAt('01:30')->timezone('Asia/Ho_Chi_Minh')->withoutOverlapping();
Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=1')->everyMinute()->withoutOverlapping();

