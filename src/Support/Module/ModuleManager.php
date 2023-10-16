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

namespace AI\Support\Module;

use AI\Support\Module\Driver\DatabaseDriver;
use AI\Support\Module\Driver\FileDriver;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Manager;

class ModuleManager extends Manager
{
    public function __construct(Container|\Closure $container)
    {
        if ($container instanceof \Closure) {
            $container = $container();
        }

        parent::__construct($container);
    }

    /**
     * @return string|null
     */
    public function getDefaultDriver(): string|null
    {
        // TODO: Implement getDefaultDriver() method.
        return $this->config->get('ai.module.driver.default', $this->defaultDriver());
    }

    /**
     * create file driver
     *
     * @return FileDriver
     */
    public function createFileDriver(): FileDriver
    {
        return new FileDriver();
    }

    /**
     * create database driver
     *
     * @return DatabaseDriver
     */
    public function createDatabaseDriver(): DatabaseDriver
    {
        return new DatabaseDriver();
    }

    /**
     * default driver
     *
     * @return string
     */
    protected function defaultDriver():string
    {
        return 'file';
    }
}
