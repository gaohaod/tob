<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\RenderController;

class RunRenderForAllUsers extends Command
{
    protected $signature = 'render:all-users';
    protected $description = 'Run RenderController::full() for every user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            RenderController::full($user->id);
        }

        $this->info('re-rendered everyones thumbnails');
    }
}
