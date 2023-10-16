<?php

namespace AI\Commands\Migrate;

use AI\AIAdmin;
use AI\Commands\AICommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MigrateFresh extends AICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:migrate:fresh {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ai migrate fresh';

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
    public function handle(): void
    {
        $module = $this->argument('module');

        if (! File::isDirectory(AIAdmin::getModuleMigrationPath($module))) {
            Artisan::call('migration:fresh', [
                '--path' => AIAdmin::getModuleRelativePath(AIAdmin::getModuleMigrationPath($module)),

                '--force' => $this->option('force')
            ]);
        } else {
            $this->error('No migration files in module');
        }
    }
}
