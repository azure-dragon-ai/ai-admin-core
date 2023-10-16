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
use Illuminate\Support\Str;

class MigrateRun extends AICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:migrate {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate ai module';

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

        if (File::isDirectory(AIAdmin::getModuleMigrationPath($module))) {
            foreach (File::files(AIAdmin::getModuleMigrationPath($module)) as $file) {
                $path = Str::of(AIAdmin::getModuleRelativePath(AIAdmin::getModuleMigrationPath($module)))

                    ->remove('.')->append($file->getFilename());

                Artisan::call('migrate', [
                    '--path' => $path,

                    '--force' => $this->option('force')
                ]);
            }

            $this->info("Module [$module] migrate success");
        } else {
            $this->error('No migration files in module');
        }
    }
}
