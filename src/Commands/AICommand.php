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

declare(strict_types=1);

namespace AI\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AI\Facade\Module;

use function Termwind\ask;
use function Termwind\render;

abstract class AICommand extends Command
{
    /**
     * @var string
     */
    protected $name;


    public function __construct()
    {
        parent::__construct();

        if (! property_exists($this, 'signature')
            && property_exists($this, 'name')
            && $this->name
        ) {
            $this->signature = $this->name.' {module}';
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if ($input->hasArgument('module')
            && ! Module::getEnabled()->pluck('name')->merge(Collection::make(config('ai.module.default')))->contains(lcfirst($input->getArgument('module')))
        ) {
            $this->error(sprintf('Module [%s] Not Found', $input->getArgument('module')));
            exit;
        }
    }


    /**
     *
     * @param string $question
     * @param null $default
     * @param bool $isChoice
     * @return string|int|null
     */
    public function askFor(string $question, $default = null, bool $isChoice = false): string|null|int
    {
        $_default = $default ? "<em class='pl-1 text-rose-600'>[$default]</em>" : '';

        $choice = $isChoice ? '<em class="bg-indigo-600 w-5 pl-1 ml-1 mr-1">Yes</em>OR<em class="bg-rose-600 w-4 pl-1 ml-1">No</em>' : '';

        $answer = ask(
            <<<HTML
            <div>
                <div class="px-1 bg-indigo-700">AIAdmin</div>
                <em class="ml-1">
                    <em class="text-green-700">$question</em>
                    $_default
                    $choice
                    <em class="ml-1">:</em><em class="ml-1"></em>
                </em>
            </div>
HTML
        );

        $this->newLine();

        if ($default && ! $answer) {
            return $default;
        }

        return $answer;
    }


    /**
     * info
     *
     * @param $string
     * @param null $verbosity
     * @return void
     */
    public function info($string, $verbosity = null): void
    {
        render(
            <<<HTML
            <div>
                <div class="px-1 bg-indigo-700">AIAdmin</div>
                <em class="ml-1">
                    <em class="text-green-700">$string</em>
                </em>
            </div>
HTML
        );
    }

    /**
     * error
     *
     * @param $string
     * @param null $verbosity
     * @return void
     */
    public function error($string, $verbosity = null): void
    {
        render(
            <<<HTML
            <div>
                <div class="px-1 bg-indigo-700">AIAdmin</div>
                <em class="ml-1">
                    <em class="text-rose-700">$string</em>
                </em>
            </div>
HTML
        );
    }
}
