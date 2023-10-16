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

declare(strict_types=1);

namespace AI\Commands\Create;

use AI\AIAdmin;
use AI\Commands\AICommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

;

class Controller extends AICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:make:controller {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create ai controller';


    public function handle()
    {
        $controllerPath = AIAdmin::getModuleControllerPath($this->argument('module'));

        $file = $controllerPath.$this->getControllerFile();

        if (File::exists($file)) {
            $answer = $this->ask($file.' already exists, Did you want replace it?', 'Y');

            if (! Str::of($answer)->lower()->exactly('y')) {
                exit;
            }
        }

        File::put($file, Str::of($this->getStubContent())->replace([
            '{namespace}', '{controller}'
        ], [trim(AIAdmin::getModuleControllerNamespace($this->argument('module')), '\\'), $this->getControllerName()])->toString());

        if (File::exists($file)) {
            $this->info($file.' has been created');
        } else {
            $this->error($file.' create failed');
        }
    }

    /**
     *
     *
     * @return string
     */
    protected function getControllerFile(): string
    {
        return $this->getControllerName().'.php';
    }

    /**
     *
     *
     * @return string
     */
    protected function getControllerName(): string
    {
        return  Str::of($this->argument('name'))
                    ->whenContains('Controller', function ($str) {
                        return $str;
                    }, function ($str) {
                        return $str->append('Controller');
                    })->ucfirst()->toString();
    }

    /**
     * get stub content
     *
     * @return string
     */
    protected function getStubContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'controller.stub');
    }
}
