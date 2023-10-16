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

namespace AI\Commands;

use AI\AIAdmin;
use Illuminate\Console\Command;
class VersionCommand extends Command
{
    protected $signature = 'ai:version';

    protected $description = 'show the version of aiadmin';

    public function handle(): void
    {
        $this->info(AIAdmin::VERSION);
    }
}
