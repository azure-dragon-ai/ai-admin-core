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
use AI\Facade\Module;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleInstallCommand extends AICommand
{
    protected $signature = 'ai:module:install {module} {--f}';

    protected $description = 'install ai module';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if (! $this->option('f')) {
            if ($input->hasArgument('module')
                && Module::getEnabled()->pluck('name')->merge(Collection::make(config('ai.module.default')))->contains(lcfirst($input->getArgument('module')))
            ) {
                $this->error(sprintf('Module [%s] Has installed', $input->getArgument('module')));
                exit;
            }
        }
    }

    public function handle(): void
    {
        $installer = AIAdmin::getModuleInstaller($this->argument('module'));

        $installer->install();
    }
}
