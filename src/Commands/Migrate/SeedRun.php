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
use Illuminate\Support\Facades\File;

class SeedRun extends AICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:db:seed {module} {--seeder=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ai db seed';

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
        $files = File::allFiles(AIAdmin::getModuleSeederPath($this->argument('module')));

        $fileNames = [];

        $seeder = $this->option('seeder');

        if ($seeder) {
            foreach ($files as $file) {
                if (pathinfo($file->getBasename(), PATHINFO_FILENAME) == $seeder) {
                    $class = require_once $file->getRealPath();

                    $seeder = new $class();

                    $seeder->run();
                }
            }
        } else {
            foreach ($files as $file) {
                if (File::exists($file->getRealPath())) {
                    $class = require_once $file->getRealPath();
                    $class = new $class();
                        $class->run();
                }
            }
        }
    }
}
