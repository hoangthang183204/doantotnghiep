<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SaoLuuCoSoDuLieu extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Sao lưu MySQL hàng ngày, giữ 7 bản gần nhất';

    public function handle(): int
    {
        $thuMuc = storage_path('app/backups');
        if (!is_dir($thuMuc)) {
            mkdir($thuMuc, 0755, true);
        }

        $dump = $this->timMysqldump();
        if (!$dump) {
            $this->error('Không tìm thấy mysqldump.');
            return self::FAILURE;
        }

        $file = $thuMuc . DIRECTORY_SEPARATOR . 'hrflow_' . now()->format('Ymd_His') . '.sql';
        $cnf = $thuMuc . DIRECTORY_SEPARATOR . '.client.cnf';
        file_put_contents($cnf, implode("\n", [
            '[client]',
            'host=' . config('database.connections.mysql.host'),
            'port=' . config('database.connections.mysql.port'),
            'user=' . config('database.connections.mysql.username'),
            'password=' . (config('database.connections.mysql.password') ?? ''),
        ]));

        $process = new Process([
            $dump,
            '--defaults-extra-file=' . $cnf,
            '--single-transaction',
            '--routines',
            config('database.connections.mysql.database'),
        ]);
        $process->setTimeout(300);
        $process->run();
        @unlink($cnf);

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            return self::FAILURE;
        }

        file_put_contents($file, $process->getOutput());
        $this->donBanCu($thuMuc);
        $this->info('Đã sao lưu ' . basename($file));
        return self::SUCCESS;
    }

    private function timMysqldump(): ?string
    {
        $ungVien = [
            'D:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'D:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\mysqldump.exe',
            'mysqldump',
        ];
        foreach ($ungVien as $duong) {
            if ($duong === 'mysqldump' || is_file($duong)) {
                return $duong;
            }
        }
        return null;
    }

    private function donBanCu(string $thuMuc): void
    {
        $files = glob($thuMuc . DIRECTORY_SEPARATOR . 'hrflow_*.sql') ?: [];
        rsort($files);
        foreach (array_slice($files, 7) as $cu) {
            @unlink($cu);
        }
    }
}
