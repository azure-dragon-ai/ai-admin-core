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
use Illuminate\Support\Str;
use PhpParser\Node\Name;

class SeederMake extends AICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:make:seeder {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make module seeder';


    /**
     *
     * @return void
     * @throws \Exception
     * @author AIAdmin
     * @time 2021年08月01日
     */
    public function handle(): void
    {
        $seederPath = AIAdmin::getModuleSeederPath($this->argument('module'));

        $file = $seederPath.$this->getSeederName().'.php';

        if (File::exists($file)) {
            $answer = $this->ask($file.' already exists, Did you want replace it?', 'Y');

            if (! Str::of($answer)->lower()->exactly('y')) {
                exit;
            }
        }

        File::put($file, $this->getSeederContent());

        if (File::exists($file)) {
            $this->info($file.' has been created');
        } else {
            $this->error($file.' create failed');
        }
    }

    /**
     * seeder content
     *
     * @return string
     * @throws \Exception
     */
    protected function getSeederContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'seeder.stub');
    }

    /**
     * seeder name
     *
     * @return string
     */
    protected function getSeederName(): string
    {
        return Str::of($this->argument('name'))->ucfirst()->toString();
    }
}
