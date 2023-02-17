<?php

namespace App\Console\Commands;

use App\Models\Formatter;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class MakeViewHeader extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:viewheader {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        parent::__construct();
//    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    private $repositoryClass;


    public function handle()
    {
        $this->setRepositoryClass();

        $path = base_path() . '/resources/views/administrator/'. Formatter::toUnderline($this->repositoryClass) . "s/header.blade.php";

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // create model
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($this->repositoryClass));

        $this->info($this->type.' created successfully.');

        $this->info($path);

        $this->line("<info>Created Repository :</info> $this->repositoryClass");
    }

    private function setRepositoryClass()
    {
        $name = ($this->argument('name'));
        $this->model = $name;
        $modelClass = $name;
        $this->repositoryClass = $modelClass;
        return $this;
    }

    public function getStub()
    {
//        return app_path() . '/Models/Repository.stub';
        return base_path(). '/stubs/make_view_header.stub';
    }

    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\app\Models';
    }

    public function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $stub = str_replace("middle_path",$this->toUnderline(strtolower($this->repositoryClass)),$stub);

        return str_replace("Repository", $this->argument('name'), $stub);
    }

    public function toUnderline($input){
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
