<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class DatabaseBackupController extends Controller
{
    public function download(Request $request)
    {
        $connectionName = Config::get('database.default');
        $connection = Config::get("database.connections.$connectionName");

        if (($connection['driver'] ?? null) !== 'mysql') {
            abort(500, 'Database backup is only supported for MySQL/MariaDB connections.');
        }

        $database = (string) ($connection['database'] ?? '');
        if ($database === '') {
            abort(500, 'Database name is not configured.');
        }

        $mysqldump = $this->resolveMysqldumpPath();
        if ($mysqldump === null) {
            abort(500, 'mysqldump was not found. Configure MYSQLDUMP_PATH in your .env.');
        }

        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir) && !mkdir($tmpDir, 0775, true) && !is_dir($tmpDir)) {
            abort(500, 'Failed to create temp directory for backup.');
        }

        $safeBase = preg_replace('/[^A-Za-z0-9._-]+/', '_', $database) ?: 'database';
        $downloadName = $safeBase . '.sql';

        $outFile = $tmpDir . DIRECTORY_SEPARATOR . Str::uuid()->toString() . '.sql';
        $cnfFile = $tmpDir . DIRECTORY_SEPARATOR . Str::uuid()->toString() . '.cnf';

        $cnf = $this->buildDefaultsExtraFileContents($connection);
        file_put_contents($cnfFile, $cnf);

        try {
            $args = [
                $mysqldump,
                "--defaults-extra-file=$cnfFile",
                '--single-transaction',
                '--routines',
                '--triggers',
                '--events',
                '--add-drop-table',
                '--databases',
                $database,
                "--result-file=$outFile",
            ];

            $process = new Process($args, base_path());
            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful()) {
                @unlink($outFile);
                abort(500, "Backup failed: " . trim($process->getErrorOutput() ?: $process->getOutput()));
            }

            return response()->download($outFile, $downloadName, [
                'Content-Type' => 'application/sql',
            ])->deleteFileAfterSend(true);
        } finally {
            @unlink($cnfFile);
        }
    }

    private function resolveMysqldumpPath(): ?string
    {
        $configured = env('MYSQLDUMP_PATH');
        if (is_string($configured) && $configured !== '' && file_exists($configured)) {
            return $configured;
        }

        $candidates = [
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.11\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'mysqldump',
        ];

        foreach ($candidates as $path) {
            if ($path === 'mysqldump') {
                return $path;
            }
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function buildDefaultsExtraFileContents(array $connection): string
    {
        $host = (string) ($connection['host'] ?? '127.0.0.1');
        $port = (string) ($connection['port'] ?? '3306');
        $user = (string) ($connection['username'] ?? '');
        $pass = (string) ($connection['password'] ?? '');

        $lines = [
            '[client]',
            "host=$host",
            "port=$port",
            "user=$user",
            "password=$pass",
        ];

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }
}

