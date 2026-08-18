<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Recovery command: repopulates all business tables from the CSV exports and the
 * seeder's confirmed real user passwords, after an accidental table wipe caused by
 * a test run's RefreshDatabase trait hitting the real MySQL connection instead of
 * the isolated sqlite testing connection.
 */
class RestoreSeedData extends Command
{
    protected $signature = 'app:restore-data';

    protected $description = 'Re-run the CSV/user seeder to restore business data after an accidental wipe';

    public function handle(): int
    {
        $this->call('db:seed', ['--force' => true]);

        return self::SUCCESS;
    }
}
