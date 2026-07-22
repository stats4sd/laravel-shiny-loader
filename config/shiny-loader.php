<?php

// config for Stats4sd/LaravelShinyLoader
return [
    /*
     * All shiny apps embedded in one Laravel app MUST be served by a single
     * shiny server instance, reachable at this root url. Each app is served
     * at {root_url}/{app-name}/. Laravel must also be able to reach this url,
     * as the auth handshake POSTs back to it.
     */
    'root_url' => env('SHINY_ROOT_URL', 'http://localhost:3838'),

    /*
     * Filesystem path to the shiny server's site directory (the folder that
     * contains each app's folder). The shared `.sessions` directory used by
     * the auth handshake lives directly inside it. Must be readable by both
     * the shiny server and this Laravel app (a shared/mounted volume).
     */
    'root_path' => env('SHINY_ROOT_PATH', '/srv/shiny-server'),

    /*
     * Shared secret between this Laravel app and the shiny apps.
     */
    'auth_key' => env('SHINY_AUTH_KEY', 'change-me'),

    /*
     * The names of the shiny apps embedded in this Laravel app. Each name is
     * the app's folder name on the shiny server, so app "monitor" is served
     * at {root_url}/monitor/. Names are structural (blade views reference
     * them) and identical in every environment, so they are listed here
     * rather than in .env.
     */
    'apps' => [],
];
