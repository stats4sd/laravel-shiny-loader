<?php

namespace Stats4sd\LaravelShinyLoader\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShinyIframe extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $shinyAppUrl, public ?array $postData = null) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('shiny-loader::components.shiny-iframe');
    }
}
