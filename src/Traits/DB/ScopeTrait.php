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

trait ScopeTrait
{
    /**
     * creator
     */
    public static function scopeCreator($query): void
    {
        $model = app(static::class);

        if (in_array($model->getCreatorIdColumn(), $model->getFillable())) {
                $userModel = app(getAuthUserModel());

            $query->addSelect([
                    'creator' => $userModel->whereColumn($userModel->getKeyName(), $model->getTable() . '.' . $model->getCreatorIdColumn())
                        ->select('username')->limit(1)
                ]);
        }
    }
}
