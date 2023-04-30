<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceResourceController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service-resource {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service resource Controller.';

    protected $type = 'Controller';

    protected function getStub()
    {
        return base_path('stubs/controller.service.resource.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    protected function buildClass($name)
    {
        $controller = class_basename($name);

        $resourceName = Str::of($controller)->remove('Controller')->toString();

        $singular = Str::singular($resourceName);
        $plural = Str::plural($resourceName);

        $serviceName = $resourceName.'Service';
        $repositoryName = $resourceName.'Repository';

        $repositoryExists = File::exists(app_path('Repositories/'.$repositoryName));

        if (! $repositoryExists) {
            Artisan::call('make:repository '.$repositoryName);
        }

        $serviceExists = File::exists(app_path('Services/'.$repositoryName));

        if (! $serviceExists) {
            Artisan::call('make:model-service '.$serviceName);
        }

        $replace = [
            '{{ repositoryClassName }}' => $repositoryName,
            '{{ serviceClassName }}' => $serviceName,
            '{{ repositoryVariable }}' => Str::camel($repositoryName),
            '{{ serviceVariable }}' => Str::camel($serviceName),
            '{{ resourcePlural }}' => Str::lower($plural),
            '{{ resourceSingular }}' => Str::lower($singular),
            '{{ resourceSingularVariable }}' => Str::camel($singular),
            '{{ resourcePluralVariable }}' => Str::camel($plural),
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }
}
