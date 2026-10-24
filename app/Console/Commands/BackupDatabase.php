<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database ke storage/backups';

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // Pastikan folder ada
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Ambil kredensial dari .env
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Jalankan mysqldump
        $command = "mysqldump -h {$host} -P {$port} -u {$username} -p{$password} {$database} > {$path} 2>&1";
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            // Hapus backup lama (lebih dari 30 hari)
            $oldFiles = glob(storage_path('app/backups/backup-*.sql'));
            foreach ($oldFiles as $file) {
                if (filemtime($file) < strtotime('-30 days')) {
                    unlink($file);
                }
            }

            $this->info("Backup berhasil: {$filename}");
        } else {
            $this->error('Backup gagal: ' . implode("\n", $output));
        }
    }
}