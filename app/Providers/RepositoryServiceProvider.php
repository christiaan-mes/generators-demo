<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class RepositoryServiceProvider extends ServiceProvider
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
        $files = File::files(app_path('Repositories'));

        $repositories = array_filter(array_map(static function (SplFileInfo $file) {
            if ($file->getFilename() !== 'Repository.php') {
                $className = Str::of($file->getFilename())->before('.php')->toString();

                return [
                    'class' => 'App\\Repositories\\'.$className,
                    'model' => 'App\\Models\\'.Str::of($className)->remove('Repository')->singular()->toString(),
                ];
            }
        }, $files));

        foreach ($repositories as $repository) {
            $this->app->singleton($repository['class'], function (Application $app) use ($repository) {
                return new $repository['class'](model: $app->make($repository['model']));
            });
        }
    }
}
