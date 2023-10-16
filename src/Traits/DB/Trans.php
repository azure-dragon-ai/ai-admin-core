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

namespace AI\Traits\DB;

use Illuminate\Support\Facades\DB;

/**
 * transaction
 */
trait Trans
{
    /**
     * begin transaction
     */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * commit
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * rollback
     */
    public function rollback(): void
    {
        DB::rollBack();
    }

    /**
     * transaction
     *
     * @param \Closure $closure
     */
    public function transaction(\Closure $closure): void
    {
        DB::transaction($closure);
    }
}
