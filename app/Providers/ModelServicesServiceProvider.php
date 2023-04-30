<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class ModelServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $files = File::files(app_path('Services'));

        $services = array_filter(array_map(static function (SplFileInfo $file) {
            if ($file->getFilename() !== 'ModelService.php') {
                $className = Str::of($file->getFilename())->before('.php')->toString();
                $serviceName = Str::of($className)
                    ->remove('Service')
                    ->append('Repository')
                    ->singular()
                    ->toString();

                return [
                    'class' => 'App\\Services\\'.$className,
                    'repository' => 'App\\Repositories\\'.$serviceName,
                ];
            }
        }, $files));

        foreach ($services as $service) {
            $this->app->singleton($service['class'], function (Application $app) use ($service) {
                return new $service['class'](repository: $app->make($service['repository']));
            });
        }
    }
}
