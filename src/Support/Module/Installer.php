<?php

namespace AI\Support\Module;

use AI\Contracts\ModuleRepositoryInterface;
use AI\Support\Composer;
use Illuminate\Support\Facades\Artisan;

/**
 * installer
 */
abstract class Installer
{
    /**
     * construct
     *
     * @param ModuleRepositoryInterface $moduleRepository
     */
    public function __construct(protected ModuleRepositoryInterface $moduleRepository)
    {
    }

    /**
     * module info
     *
     * @return array
     */
    abstract protected function info(): array;

    /**
     * migrate
     */
    protected function migrate(): void
    {
        Artisan::call('ai:migrate', [
            'module' => $this->info()['name']
        ]);
    }

    /**
     * seed
     */
    protected function seed():void
    {
        Artisan::call('ai:db:seed', [
            'module' => $this->info()['name']
        ]);
    }

    /**
     * require packages
     *
     * @return void
     */
    abstract protected function requirePackages(): void;


    /**
     * remove packages
     *
     * @return void
     */
    abstract protected function removePackages(): void;

    /**
     * uninstall
     *
     * @return void
     */
    public function uninstall(): void
    {
        $this->moduleRepository->delete($this->info()['name']);

        $this->removePackages();
    }

    /**
     * invoke
     *
     * @return void
     */
    public function install(): void
    {
        // TODO: Implement __invoke() method.
        $this->moduleRepository->create($this->info());

        $this->migrate();

        $this->seed();

        $this->requirePackages();
    }

    /**
     * composer installer
     *
     * @return Composer
     */
    protected function composer(): Composer
    {
        return app(Composer::class);
    }
}
