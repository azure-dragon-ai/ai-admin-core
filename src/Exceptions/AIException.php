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

namespace AI\Exceptions;

use AI\Enums\Enum;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AI\Enums\Code;

abstract class AIException extends HttpException
{
    protected $code = 0;

    /**
     * @param string $message
     * @param int|Code $code
     */
    public function __construct(string $message = '', int|Code $code = 0)
    {
        if ($code instanceof Enum) {
            $code = $code->value();
        }

        if ($this->code instanceof Enum && ! $code) {
            $code = $this->code->value();
        }

        parent::__construct($this->statusCode(), $message ?: $this->message, null, [], $code);
    }

    /**
     * status code
     *
     * @return int
     */
    public function statusCode(): int
    {
        return 500;
    }

    /**
     * render
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'code' => $this->code,

            'message' => $this->message
        ];
    }
}
