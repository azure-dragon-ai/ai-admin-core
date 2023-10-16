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

/**
 * base operate
 */
trait WithSearch
{
    /**
     * @var array $searchable
     */
    public array $searchable = [];

    /**
     *
     * @param array $searchable
     * @return $this
     */
    public function setSearchable(array $searchable): static
    {
        $this->searchable = array_merge($this->searchable,$searchable);

        return $this;
    }
}
