<?php

namespace  Stats4sd\LaravelShinyLoader\Commands;

use Illuminate\Console\Command;

class LaravelShinyLoaderCommand extends Command
{
    public $signature = 'laravel-shiny-loader';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
