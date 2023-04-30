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
    protected $signature = 'make:service-resource {name} {--force}';

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

        $resourceName = Str::of($controller)->remove('Controller')->singular()->toString();

        $plural = Str::plural($resourceName);

        $serviceName = $resourceName.'Service';
        $repositoryName = $resourceName.'Repository';
        $factoryName = $resourceName . 'Factory';
        $seederName = $resourceName . 'Seeder';

        if (! File::exists(app_path('Models/'.$resourceName))) {
            Artisan::call('make:model --factory --seed '.$resourceName);
        }

        $migrationName = 'create_'.Str::lower($plural).'_table';

        if (! File::exists(database_path('migrations/' . $migrationName))) {
            Artisan::call('make:migration '.$migrationName);

            $this->components->info(sprintf('Migration [%s] created successfully', 'database/migrations/' . $seederName));
        }

        if (!File::exists(database_path('factories/'.$factoryName))) {
            Artisan::call('make:factory '.$factoryName);

            $this->components->info(sprintf('Factory [%s] created successfully', 'database/factories/' . $seederName));
        }

        if (! File::exists(database_path('seeders/'.$seederName))) {
            Artisan::call('make:seeder '.$seederName);

            $this->components->info(sprintf('Seeder [%s] created successfully', 'database/seeders/' . $seederName));
        }

        if (! File::exists(app_path('Repositories/'.$repositoryName))) {
            Artisan::call('make:repository '.$repositoryName);

            $this->components->info(sprintf('Service [%s] created successfully', 'app/Repositories/' . $serviceName));
        }

        $serviceExists = File::exists(app_path('Services/'.$repositoryName));

        if (! $serviceExists) {
            Artisan::call('make:model-service '.$serviceName);

            $this->components->info(sprintf('Service [%s] created successfully', 'app/Services/' . $serviceName));
        }

        $replace = [
            '{{ repositoryClassName }}' => $repositoryName,
            '{{ serviceClassName }}' => $serviceName,
            '{{ repositoryVariable }}' => Str::camel($repositoryName),
            '{{ serviceVariable }}' => Str::camel($serviceName),
            '{{ resourcePlural }}' => Str::lower($plural),
            '{{ resourceSingular }}' => Str::lower($resourceName),
            '{{ resourceSingularVariable }}' => Str::camel($resourceName),
            '{{ resourcePluralVariable }}' => Str::camel($plural),
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }
}
