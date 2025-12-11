<?php

namespace Stats4sd\LaravelShinyLoader\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShinyController
{
    public function authenticateShiny(Request $request): JsonResponse
    {
        $session = $request->get('session');
        $postData = $request->get('post_data');

        if (! $session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Look for the file with the same session name.
        $file = fopen(config('shiny-loader.app-path').'/.sessions/'.$session, 'r');

        // Get the POST url from that file
        $url = [];

        while (! feof($file)) {
            $url[] = fgets($file);
        }

        fclose($file);

        // format the URL correctly
        $finalUrl = str_replace("\n", '', $url[0]);
        $finalUrl = config('services.shiny.rdmt-demo-url').$finalUrl;
        $finalUrl = str_replace("\n", '', $finalUrl);

        // Append the auth_key to the post data
        $postData['auth_key'] = config('shiny-loader.auth-key');

        Http::post(str_replace("\n", '', $url[0]), $postData)
            ->throw()
            ->json();

        return response()->json([
            'success' => 'Shiny session authenticated',
        ]);
    }
}
