<?php

// +----------------------------------------------------------------------
// | AIAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2022 https://luomor.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/azure-dragon-ai/azure-dragon-ai/blob/main/LICENSE )
// +----------------------------------------------------------------------
// | Author: PeterZhang [ zhangchunsheng423@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace AI\Base;

use AI\Support\DB\SoftDelete;
use AI\Traits\DB\BaseOperate;
use AI\Traits\DB\ScopeTrait;
use AI\Traits\DB\Trans;
use AI\Traits\DB\WithAttributes;
use AI\Traits\DB\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 *
 * @mixin Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
abstract class AIModel extends Model
{
    use BaseOperate, Trans, SoftDeletes, ScopeTrait, WithAttributes;

    /**
     * unix timestamp
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * paginate limit
     */
    protected $perPage = 10;

    /**
     * @var string[]
     */
    protected array $defaultCasts = [
        'created_at' => 'datetime:Y-m-d H:i',

        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    protected array $defaultHidden = ['deleted_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->init();
    }

    /**
     * init
     */
    protected function init()
    {
        $this->makeHidden($this->defaultHidden);

        $this->mergeCasts($this->defaultCasts);

        // auto use data range
        foreach (class_uses_recursive(static::class) as $trait) {
            if (str_contains($trait, 'DataRange')) {
                $this->setDataRange();
            }
        }
    }

    /**
     * soft delete
     *
     * @time 2021年08月09日
     * @return void
     */
    public static function bootSoftDeletes(): void
    {
        static::addGlobalScope(new SoftDelete());
    }
}
