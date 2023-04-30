<?php

namespace App\Console;

use Illuminate\Console\GeneratorCommand;

class MakeRepository extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository.';

    protected $type = 'Repository';

    protected function getStub()
    {
        return base_path('stubs/repository.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        // Do string replacement
        return str_replace('{{repository_name}}', $class, $stub);
    }
}
