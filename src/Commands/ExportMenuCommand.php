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
use AI\Support\Tree;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Facades\DB;

class ExportMenuCommand extends AICommand
{
    protected $signature = 'ai:export:menu {module} {table?} {--p}';

    protected $description = 'ai export table data';


    public function handle(): void
    {
        $module = $this->argument('module');

        $table = $this->argument('table') ? : 'permissions';

        $p = $this->option('p');

        if ($module) {
            $data = DB::table($table)->where('deleted_at', 0)
                ->where('module', $module)
                ->get();
        } else {
            $data = DB::table($table)->where('deleted_at', 0)->get();
        }

        $data = json_decode($data->toJson(), true);

        if ($p) {
            $data = Tree::done($data);
        }

        if ($module) {
            $data = 'return ' . var_export($data, true) . ';';
            $this->exportSeed($data, $module);
        } else {
          file_put_contents(base_path() . DIRECTORY_SEPARATOR . $table . '.php', "<?php\r\n return " . var_export($data, true) . ';');
        }

        $this->info('Export Successful');
    }

    protected function exportSeed($data, $module)
    {

        $stub = File::get(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'menuSeeder.stub');

        $class = ucfirst($module) . 'MenusSeeder';

        $stub = str_replace('{CLASS}', $class, $stub);

        File::put(AIAdmin::getModuleSeederPath($module) . $class .'.php', str_replace('{menus}', $data, $stub));
    }
}
