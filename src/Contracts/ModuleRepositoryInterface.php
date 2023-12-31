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

namespace AI\Contracts;

use Illuminate\Support\Collection;

interface ModuleRepositoryInterface
{
    public function all(array $search): Collection;

    public function create(array $module): bool|int;

    public function show(string $name): Collection;

    public function update(string $name, array $module): bool|int;

    public function delete(string $name): bool|int;

    public function disOrEnable(string $name): bool|int;

    public function getEnabled(): Collection;

    public function enabled(string $moduleName): bool;
}
