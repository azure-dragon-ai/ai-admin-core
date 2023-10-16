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

use AI\Contracts\ModuleRepositoryInterface;
use AI\Events\Module\Created;
use AI\Events\Module\Creating;
use AI\Events\Module\Deleted;
use AI\Events\Module\Updated;
use AI\Events\Module\Updating;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

/**
 * FileDriver
 */
class ModuleRepository
{
    protected ModuleRepositoryInterface $moduleRepository;

    /**
     * construct
     */
    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * all
     *
     * @param array $search
     * @return Collection
     */
    public function all(array $search): Collection
    {
        return $this->moduleRepository->all($search);
    }

    /**
     * create module json
     *
     * @param array $module
     * @return bool
     */
    public function create(array $module): bool
    {
        $module['name'] = lcfirst($module['path']);

        Event::dispatch(new Creating($module));

        $this->moduleRepository->create($module);

        Event::dispatch(new Created($module));

        return true;
    }

    /**
     * module info
     *
     * @param string $name
     * @return Collection
     * @throws Exception
     */
    public function show(string $name): Collection
    {
        try {
            return $this->moduleRepository->show($name);
        } catch (Exception $e) {
            throw new $e();
        }
    }

    /**
     * update module json
     *
     * @param string $name
     * @param array $module
     * @return bool
     */
    public function update(string $name, array $module): bool
    {
        $module['name'] = lcfirst($module['path']);

        Event::dispatch(new Updating($name, $module));

        $this->moduleRepository->update($name, $module);

        Event::dispatch(new Updated($name, $module));

        return true;
    }

    /**
     * delete module json
     *
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function delete(string $name): bool
    {
        $module = $this->show($name);

        $this->moduleRepository->delete($name);

        Event::dispatch(new Deleted($module));

        return true;
    }

    /**
     * disable or enable
     *
     * @param string $name
     * @return bool|int
     */
    public function disOrEnable(string $name): bool|int
    {
        return $this->moduleRepository->disOrEnable($name);
    }

    /**
     * get enabled
     *
     * @return Collection
     */
    public function getEnabled(): Collection
    {
        // TODO: Implement getEnabled() method.
        return $this->moduleRepository->getEnabled();
    }

    /**
     * enabled
     *
     * @param string $moduleName
     * @return bool
     */
    public function enabled(string $moduleName): bool
    {
        return $this->moduleRepository->enabled($moduleName);
    }
}
