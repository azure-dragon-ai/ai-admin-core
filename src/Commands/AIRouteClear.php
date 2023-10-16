<?php

declare(strict_types=1);

namespace AI\Commands;

use AI\AIAdmin;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AIRouteClear extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'ai:route:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear route cache';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Create a new route command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->files->delete($this->laravel->getCachedRoutesPath());

        $this->files->delete(AIAdmin::getRouteCachePath());
    }
}
