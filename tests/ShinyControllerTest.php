<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->rootPath = sys_get_temp_dir().'/shiny-loader-tests-'.uniqid();
    File::ensureDirectoryExists("{$this->rootPath}/.sessions");

    config()->set('shiny-loader.root_path', $this->rootPath);
    config()->set('shiny-loader.root_url', 'http://localhost:3838');
    config()->set('shiny-loader.auth_key', 'test-key');

    Route::shiny();
});

afterEach(function () {
    File::deleteDirectory($this->rootPath);
});

it('posts the auth key and post data to the callback url from the session file', function () {
    Http::fake(['http://localhost:3838/*' => Http::response('ok')]);
    File::put("{$this->rootPath}/.sessions/abc123", "http://localhost:3838/monitor/session/abc123/dataobj/auth?w=&nonce=xyz\n");

    $response = $this->postJson(route('laravel-shiny.shiny-auth'), [
        'session' => 'abc123',
        'post_data' => ['project_id' => 42],
    ]);

    $response->assertOk()->assertJson(['success' => 'Shiny session authenticated']);

    Http::assertSent(fn (ClientRequest $sentRequest): bool => $sentRequest->url() === 'http://localhost:3838/monitor/session/abc123/dataobj/auth?w=&nonce=xyz'
        && $sentRequest['auth_key'] === 'test-key'
        && $sentRequest['project_id'] === 42);
});

it('returns 404 when no session file exists', function () {
    Http::fake();

    $response = $this->postJson(route('laravel-shiny.shiny-auth'), ['session' => 'missing1']);

    $response->assertNotFound();
    Http::assertNothingSent();
});

it('rejects a callback url served from a different shiny server', function () {
    Http::fake();
    File::put("{$this->rootPath}/.sessions/abc123", "http://evil.example.com/monitor/session/abc123/dataobj/auth\n");

    $response = $this->postJson(route('laravel-shiny.shiny-auth'), ['session' => 'abc123']);

    $response->assertStatus(422);
    Http::assertNothingSent();
});

it('rejects a session id that is not alphanumeric', function () {
    Http::fake();

    $response = $this->postJson(route('laravel-shiny.shiny-auth'), ['session' => '../../../etc/passwd']);

    $response->assertStatus(422);
    Http::assertNothingSent();
});

it('returns 419 when the shiny app rejects the callback post', function () {
    Http::fake(['http://localhost:3838/*' => Http::response('nope', 500)]);
    File::put("{$this->rootPath}/.sessions/abc123", "http://localhost:3838/monitor/session/abc123/dataobj/auth\n");

    $response = $this->postJson(route('laravel-shiny.shiny-auth'), ['session' => 'abc123']);

    $response->assertStatus(419);
});
