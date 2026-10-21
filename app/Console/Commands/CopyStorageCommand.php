<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyStorageCommand extends Command
{
    protected $signature = 'storage:copy
                            {from=public : Source disk}
                            {to=supabase : Target disk}
                            {--dir= : Only copy this directory}';

    protected $description = 'Copy uploaded files from one storage disk to another, keeping their paths (skips files that already exist)';

    public function handle(): int
    {
        $from = Storage::disk($this->argument('from'));
        $to = Storage::disk($this->argument('to'));

        $files = collect($from->allFiles($this->option('dir') ?? ''))
            ->reject(fn ($path) => str_starts_with(basename($path), '.'));

        $copied = 0;
        $skipped = 0;
        foreach ($files as $path) {
            if ($to->exists($path)) {
                $skipped++;
                continue;
            }
            $to->writeStream($path, $from->readStream($path));
            $this->line("  {$path}");
            $copied++;
        }

        $this->info("Copied {$copied} file(s), skipped {$skipped} already present.");

        return self::SUCCESS;
    }
}
