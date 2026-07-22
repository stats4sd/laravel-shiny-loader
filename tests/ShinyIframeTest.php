<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Stats4sd\LaravelShinyLoader\View\Components\ShinyIframe;

beforeEach(function () {
    config()->set('shiny-loader.root_url', 'http://localhost:3838');
    config()->set('shiny-loader.apps', ['monitor', 'analysis']);
});

it('builds the app url from the shared root url', function () {
    $component = new ShinyIframe('monitor');

    expect($component->shinyAppUrl)->toBe('http://localhost:3838/monitor/');
});

it('strips a trailing slash from the root url', function () {
    config()->set('shiny-loader.root_url', 'http://localhost:3838/');

    $component = new ShinyIframe('analysis');

    expect($component->shinyAppUrl)->toBe('http://localhost:3838/analysis/');
});

it('rejects an app that is not registered in config', function () {
    new ShinyIframe('unknown-app');
})->throws(InvalidArgumentException::class);

it('renders the iframe pointing at the app url', function () {
    Route::shiny();

    $html = Blade::render('<x-shiny-loader::shiny-iframe app="monitor" :post-data="[\'foo\' => \'bar\']" />');

    expect($html)->toContain('src="http://localhost:3838/monitor/"');
});
