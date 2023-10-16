<?php

// +----------------------------------------------------------------------
// | AIAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014~2023 https://luomor.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/azure-dragon-ai/azure-dragon-ai/blob/main/LICENSE )
// +----------------------------------------------------------------------
// | Author: PeterZhang [ zhangchunsheng423@gmail.com ]
// +----------------------------------------------------------------------

namespace AI\Commands\Migrate;

use AI\AIAdmin;
use AI\Commands\AICommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MigrationRollback extends AICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:migrate:rollback {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rollback module tables';

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
            Artisan::call('migration:rollback', [
                '--path' => AIAdmin::getModuleRelativePath(AIAdmin::getModuleMigrationPath($module)),

                '--force' => $this->option('force')
            ]);
        } else {
            $this->error('No migration files in module');
        }
    }
}
