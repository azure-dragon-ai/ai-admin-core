<?php

// +----------------------------------------------------------------------
// | AIAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 ~ now https://luomor.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/azure-dragon-ai/azure-dragon-ai/blob/main/LICENSE )
// +----------------------------------------------------------------------
// | Author: PeterZhang [ zhangchunsheng423@gmail.com ]
// +----------------------------------------------------------------------

namespace AI\Providers;

use AI\AIAdmin;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Finder\Finder;

abstract class AIModuleServiceProvider extends ServiceProvider
{
    protected array $events = [];


    /**
     * register
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return void
     */
    public function register(): void
    {
        foreach ($this->events as $event => $listener) {
            Event::listen($event, $listener);
        }

        $this->loadMiddlewares();

        $this->loadModuleRoute();

        $this->loadConfig();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function loadMiddlewares()
    {
        if (! empty($middlewares = $this->middlewares())) {
            $route = $this->app['config']->get('ai.route', [
                'middlewares' => []
            ]);

            $route['middlewares']= array_merge($route['middlewares'], $middlewares);

            $this->app['config']->set('ai.route', $route);
        }
    }

    /**
     * load module config
     */
    protected function loadConfig()
    {
        if (! is_dir($configPath = $this->configPath())) {
            return;
        }

        $files = [];
        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $files[str_replace('.php', '', $file->getBasename())] = $file->getRealPath();
        }

        // multi config files
        foreach ($files as $name => $file) {
            $this->app->make('config')->set(sprintf('%s.%s',$this->moduleName(), $name), require $file);
        }
    }

    /**
     *
     * @return array
     */
    protected function middlewares(): array
    {
        return [];
    }

    /**
     * return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function loadModuleRoute(): void
    {
        $routes = $this->app['config']->get('ai.module.routes', []);

        $routes[] = AIAdmin::getModuleRoutePath($this->moduleName());

        $this->app['config']->set('ai.module.routes', $routes);
    }

    /**
     * route path
     *
     * @return string|array
     */
    abstract protected function moduleName(): string | array;


    /**
     * module config path
     *
     * @return string
     */
    protected function configPath(): string
    {
        return AIAdmin::getModulePath($this->moduleName()) . 'config' . DIRECTORY_SEPARATOR;
    }
}
