# Changelog

All notable changes to `laravel-shiny-loader` will be documented in this file.

## v1.2.1: Streamline Config Requirements - 2026-07-24

### What's Changed

* Single shiny server by @dave-mills in https://github.com/stats4sd/laravel-shiny-loader/pull/10
* Update orchestra/testbench requirement from ^10.0.0||^9.0.0 to ^11.1.0 by @dependabot[bot] in https://github.com/stats4sd/laravel-shiny-loader/pull/9
* Bump actions/checkout from 6 to 7 by @dependabot[bot] in https://github.com/stats4sd/laravel-shiny-loader/pull/8

**Full Changelog**: https://github.com/stats4sd/laravel-shiny-loader/compare/v1.1.2...v1.2.1

## 2.0 - Single shiny server - 2026-07-22

Breaking changes:

- All embedded shiny apps must now be served from a single shiny server instance. New config: `root_url` (env `SHINY_ROOT_URL`) and a committed `apps` array of app names; the per-app `*-url` config entries and `SHINY_APP_URL_*` env vars are removed.
- Config keys renamed to snake_case: `app-path` → `root_path` (env `SHINY_APP_PATH` → `SHINY_ROOT_PATH`), `auth-key` → `auth_key` (env `SHINY_AUTH_KEY` unchanged).
- `<x-shiny-loader::shiny-iframe>` now takes `app` (the app name, which must be registered in `shiny-loader.apps`) instead of `shiny-app-url`.
- The auth controller validates that the session callback url starts with `root_url` (422 otherwise), rejects non-alphanumeric session ids, returns 404 for missing session files, and no longer reads the leftover `services.shiny.rdmt-demo-url` config key.
- The iframe view now reports authentication failures instead of silently treating HTTP error responses as success.
- The [x-shiny-loader::shiny-iframe](x-shiny-loader::shiny-iframe) tag now resolves to the ShinyIframe class component (previously it silently fell through to Blade's anonymous-component fallback, so the component class never ran).

## Work with Laravel 13 - 2026-06-23

Minor addition to composer.json to work with Laravel 13

## 1.1 - Streamlined Update - 2025-12-11

Bug fixes:

- fixes issue with formatting the URL to POST back to Shiny
- fixes an issue with formatting of the $postData

Improvements:

- Adds the `SHINY_AUTH_KEY` env variable as an additional layer of security to let the Shiny app confirm the final POST request comes from your Laravel app

## v1.0 - 2025-09-11

Initial Release:
