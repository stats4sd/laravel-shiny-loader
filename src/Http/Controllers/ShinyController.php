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

        // Check if the user is authorized to view the Shiny app.
        // This allows specific apps to add custom auth logic.
        if (! $this->authoriseShinyApp($request, $postData)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Look for the file with the same session name.
        $file = fopen(config('shiny-loader.app-path').'/.sessions/'.$session, 'r');

        // Get the POST url from that file
        $url = [];

        while (! feof($file)) {
            $url[] = fgets($file);
        }

        fclose($file);

        Http::post(str_replace("\n", '', $url[0]), $postData)
            ->throw()
            ->json();

        return response()->json([
            'success' => 'Shiny session authenticated',
        ]);
    }

    /**
     * Determine if the shiny app should be authorised for display.
     * Accepts the current request and any custom $postData passed to the ShinyIframe blade component
     */
    public function authoriseShinyApp(Request $request, ?array $postData): bool
    {
        // Implement your logic to determine if the user can view the Shiny app.
        // For example, check if the user is authenticated and has a specific role or permission.

        // Example logic:
        // Limit to admin users only
        // if ($request->user() && $request->user()->hasRole('admin')) {
        //     return true;
        // }
        // return false;

        // Limit to users who are members of the current team (assuming team_id is passed in postData)
        // if ($request->user() && isset($postData['team_id']) &&
        //     $request->user()->teams->contains($postData['team_id'])) {
        //     return true;
        // return false;

        // defaults to always true:
        return true;

    }
}
