<?php

namespace Stats4sd\LaravelShinyLoader;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelShinyLoaderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-shiny-loader')
            ->hasViews()
            ->hasConfigFile()
            ->hasRoute('shiny-loader');
    }

    public function packageBooted(): void
    {
        Blade::componentNamespace('Stats4sd\\LaravelShinyLoader\\View\\Components', 'shiny-loader');
    }
}
