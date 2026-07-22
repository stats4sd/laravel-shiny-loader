<?php

namespace Stats4sd\LaravelShinyLoader\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ShinyController
{
    public function authenticateShiny(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session' => ['required', 'string', 'alpha_num'],
            'post_data' => ['nullable', 'array'],
        ]);

        $sessionFile = config('shiny-loader.root_path')."/.sessions/{$validated['session']}";

        if (! File::exists($sessionFile)) {
            return response()->json(['error' => 'Shiny session not found'], 404);
        }

        $callbackUrl = trim((string) strtok(File::get($sessionFile), "\n"));

        $rootUrl = rtrim((string) config('shiny-loader.root_url'), '/');

        if (! str_starts_with($callbackUrl, "{$rootUrl}/")) {
            return response()->json([
                'error' => "The shiny session's callback url does not start with the configured shiny-loader.root_url ({$rootUrl}). All embedded shiny apps must be served from the same shiny server instance.",
            ], 422);
        }

        $postData = $validated['post_data'] ?? [];
        $postData['auth_key'] = config('shiny-loader.auth_key');

        try {
            Http::post($callbackUrl, $postData)->throw();
        } catch (Exception) {
            return response()->json(['error' => 'Shiny session authentication failed', 'url' => $callbackUrl], 419);
        }

        return response()->json(['success' => 'Shiny session authenticated']);
    }
}
