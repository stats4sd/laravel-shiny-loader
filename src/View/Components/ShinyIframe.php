<?php

namespace Stats4sd\LaravelShinyLoader\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

class ShinyIframe extends Component
{
    public string $shinyAppUrl;

    public function __construct(public string $app, public ?array $postData = null)
    {
        if (! in_array($app, config('shiny-loader.apps', []), true)) {
            throw new InvalidArgumentException("Shiny app [{$app}] is not registered in the shiny-loader.apps config array.");
        }

        $rootUrl = rtrim((string) config('shiny-loader.root_url'), '/');

        $this->shinyAppUrl = "{$rootUrl}/{$app}/";
    }

    public function render(): View|Closure|string
    {
        return view('shiny-loader::components.shiny-iframe'); // @phpstan-ignore argument.type
    }
}
