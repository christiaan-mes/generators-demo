<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeModelService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model Service.';

    protected $type = 'Service';

    protected function getStub()
    {
        return base_path('stubs/service.model.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }

    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        // Do string replacement
        return str_replace('{{service_name}}', $class, $stub);
    }
}
