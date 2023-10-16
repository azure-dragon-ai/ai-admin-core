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
use AI\Contracts\ModuleRepositoryInterface;
use AI\Exceptions\Handler;
use AI\Support\DB\Query;
use AI\Support\Module\ModuleManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use AI\Support\Macros\MacrosRegister;
use Illuminate\Contracts\Debug\ExceptionHandler;

/**
 * AIAmin Service Provider
 */
class AIAdminServiceProvider extends ServiceProvider
{
    /**
     * boot
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function boot(): void
    {
        $this->bootDefaultModuleProviders();
        $this->bootModuleProviders();
        $this->registerEvents();
        $this->listenDBLog();
        $this->app->make(MacrosRegister::class)->boot();
    }

    /**
     * register
     *
     * @return void
     * @throws ReflectionException
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->registerModuleRepository();
        $this->registerExceptionHandler();
        $this->publishConfig();
        $this->publishModuleMigration();
    }


    /**
     * register commands
     *
     * @return void
     * @throws ReflectionException
     */
    protected function registerCommands(): void
    {
        loadCommands(dirname(__DIR__).DIRECTORY_SEPARATOR.'Commands', 'AI\\');
    }

    /**
     * bind module repository
     *
     * @return void
     */
    protected function registerModuleRepository(): void
    {
        // register module manager
        $this->app->singleton(ModuleManager::class, function () {
            return new ModuleManager(fn () => Container::getInstance());
        });

        // register module repository
        $this->app->singleton(ModuleRepositoryInterface::class, function () {
            return $this->app->make(ModuleManager::class)->driver();
        });

        $this->app->alias(ModuleRepositoryInterface::class, 'module');
    }

    /**
     * register events
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(RequestHandled::class, config('ai.response.request_handled_listener'));
    }

    /**
     * register exception handler
     *
     * @return void
     */
    protected function registerExceptionHandler(): void
    {
        if (isRequestFromDashboard()) {
            $this->app->singleton(ExceptionHandler::class, Handler::class);
        }
    }

    /**
     * publish config
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $from = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'ai.php';

            $to = config_path('ai.php');

            $this->publishes([$from => $to], 'ai-config');
        }
    }


    /**
     * publish module migration
     *
     * @return void
     */
    protected function publishModuleMigration(): void
    {
        if ($this->app->runningInConsole()) {
            $form = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'2022_11_14_034127_module.php';

            $to = database_path('migrations').DIRECTORY_SEPARATOR.'2022_11_14_034127_module.php';

            $this->publishes([$form => $to], 'ai-module');
        }
    }

    /**
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function bootDefaultModuleProviders(): void
    {
        foreach ($this->app['config']->get('ai.module.default', []) as $module) {
            $provider = AIAdmin::getModuleServiceProvider($module);
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    /**
     * boot module
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function bootModuleProviders()
    {
        foreach ($this->app->make(ModuleRepositoryInterface::class)->getEnabled() as $module) {
            if (class_exists($module['provider'])) {
                $this->app->register($module['provider']);
            }
        }

        $this->registerModuleRoutes();
    }

    /**
     * register module routes
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function registerModuleRoutes()
    {
        if (! $this->app->routesAreCached()) {
            $route = $this->app['config']->get('ai.route');

            if (! empty($route)) {
                Route::prefix($route['prefix'])
                    ->middleware($route['middlewares'])
                    ->group($this->app['config']->get('ai.module.routes'));
            }
        }
    }

    /**
     * listen db log
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return void
     */
    protected function listenDBLog(): void
    {
        if ($this->app['config']->get('ai.listen_db_log')) {
            Query::listen();

            $this->app->terminating(function () {
                Query::log();
            });
        }
    }

    /**
     * file exist
     *
     * @return bool
     */
    protected function routesAreCached(): bool
    {
        return $this->app->routesAreCached();
    }
}
