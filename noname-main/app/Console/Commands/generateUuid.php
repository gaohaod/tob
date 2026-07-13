<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class generateUuid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a UUID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(Str::uuid());
    }
}
