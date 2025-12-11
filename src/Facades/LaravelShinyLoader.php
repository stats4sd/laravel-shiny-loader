<?php

namespace Stats4sd\LaravelShinyLoader\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ Stats4sd\LaravelShinyLoader\LaravelShinyLoader
 */
class LaravelShinyLoader extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Stats4sd\LaravelShinyLoader\LaravelShinyLoader::class;
    }
}
