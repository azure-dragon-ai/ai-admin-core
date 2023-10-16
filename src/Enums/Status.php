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

namespace AI\Enums;

enum Status: int implements Enum
{
    case Enable = 1;

    case Disable = 2;

    /**
     * @desc name
     *
     */
    public function name(): string
    {
        return match ($this) {
            Status::Enable => '启用',

            Status::Disable => '禁用'
        };
    }

    /**
     * get value
     *
     * @return int
     */
    public function value(): int
    {
        return match ($this) {
            Status::Enable => 1,

            Status::Disable => 2,
        };
    }
}
