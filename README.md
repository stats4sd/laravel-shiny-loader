# Laravel Shiny Loader

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stats4sd/laravel-shiny-loader.svg?style=flat-square)](https://packagist.org/packages/stats4sd/laravel-shiny-loader)
[![Total Downloads](https://img.shields.io/packagist/dt/stats4sd/laravel-shiny-loader.svg?style=flat-square)](https://packagist.org/packages/stats4sd/laravel-shiny-loader)

A Laravel package that lets you embed Shiny apps into your Laravel application with iFrames.

...But surely that doesn't need a package? You're right - you can just use an iFrame and point it at your Shiny app (as long as the app is publicly accessible).

This package does more than that - it provides a way for Laravel to communicate with the Shiny app to:

*   pass initialisation data into the app
*   ensure that the current user is authenticated before loading the app.

## How does it work?

TODO: Update and bring over documentation from : https://github.com/stats4sd/shiny-laravel-auth-example

## Requirements: one shiny server per Laravel app

**All shiny apps embedded in one Laravel app must be served by a single shiny server instance, from a single root url.** Each app is served at `{root_url}/{app-name}/`, and the auth handshake relies on a `.sessions` directory at the shiny server's root, shared between all apps and readable by Laravel (same machine or a shared/mounted volume).

This means the app *names* are part of your application's structure: they must match the app's folder name on the shiny server in every environment, and your blade views reference them directly. They therefore live in the committed config file, not in `.env`. Only the root url and root path vary per environment.

Running apps individually with `shiny::runApp()` on ad-hoc ports is not supported — there is no shared root url in that mode. For local development, either run a shiny server (e.g. via Docker) serving all apps, or use the R package's `OVERRIDE_LARAVEL_AUTH=true` bypass (see [stats4sd/shiny-laravel-auth](https://github.com/stats4sd/shiny-laravel-auth)).

## Installation

You can install the package via composer, then publish the config file:

```
composer require stats4sd/laravel-shiny-loader
php artisan vendor:publish --tag shiny-loader-config
```

Add the following properties to your .env file:

```
## Root url of the shiny server instance that serves ALL embedded shiny apps.
## Both the browser and the Laravel app must be able to reach this url.
SHINY_ROOT_URL="http://localhost:3838"

## Absolute path to the shiny server's site directory - the folder containing each app's folder
## and the shared `.sessions` directory used by the auth handshake.
SHINY_ROOT_PATH="/srv/shiny-server"

## A secret key that both the Laravel app and the Shiny apps know. This is used to authenticate
## requests from Laravel to the Shiny apps. It can be any string.
SHINY_AUTH_KEY="change-me"
```

Then register each embedded app's name in `config/shiny-loader.php` (names are folder names on the shiny server):

```php
'apps' => [
    'monitor',
    'analysis',
],
```

## Use

To enable authentication, register the auth route in your `routes/web.php` (wrap it in whatever auth middleware suits your app):

```php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::shiny();
});
```

Embed an app with the ShinyIframe component, passing the app's *name* (it must be listed in `shiny-loader.apps`). You may optionally add `$postData` — an array of data to pass to the Shiny app when it loads:

```blade
<x-shiny-loader::shiny-iframe
    app="monitor"
    :post-data="['foo' => 'bar']"
    />
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

*   [Dave Mills](https://github.com/dave-mills)
*   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
