<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class LogOutAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log-out-all-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log out all users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $filesystem = new Filesystem();
        foreach ($filesystem->allFiles('storage/framework/sessions') as $file) {
            if ($file->getFilename() === '.gitignore') {
                continue;
            }

            $filesystem->delete($file);
        }

        $this->info('✅ All users have been logged out of the application.');
    }
}
