<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{

    protected $signature = 'shop:refresh';

    protected $description = 'Migration Refreshing.';

    public function handle()
    {
        if (app()->isProduction()) {
            return self::FAILURE;
        }

        Storage::deleteDirectory('images');
        $this->call('migrate:refresh', [
            '--seed' => true,
        ]);

        return self::SUCCESS;
    }
}
