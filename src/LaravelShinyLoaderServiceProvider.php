<?php

namespace Stats4sd\LaravelShinyLoader;

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
            ->hasViewComponent('shiny-iframe', View\Components\ShinyIframe::class)
            ->hasConfigFile()
            ->hasRoute('shiny-loader');
    }
}
