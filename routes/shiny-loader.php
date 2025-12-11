<?php

use Illuminate\Support\Facades\Route;
use Stats4sd\LaravelShinyLoader\Http\Controllers\ShinyController;

Route::macro('shiny', function (string $baseUrl = 'shiny') {
    Route::prefix($baseUrl)->group(function () {
        Route::post('/auth', [ShinyController::class, 'authenticateShiny'])->name('laravel-shiny.shiny-auth');
    });
});
