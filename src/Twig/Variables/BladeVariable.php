<?php

namespace wsydney76\craft6blade\Twig\Variables;

use CraftCms\Cms\Support\Template;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Throwable;
use function app;

class BladeVariable
{
    /**
     * @throws Throwable
     */
    public function render(string $view, array $data = []): HtmlString
    {
        return new HtmlString(view($view, $data)->render());
    }

    // Render the Livewire bootstrap view (includes scripts/styles setup).
    // Twig: {{ craft.livewire({
    //        component: 'pages::search'
    //    }) }}
    public function livewire(array $data = [])
    {
        return Template::raw(view('craft6blade::livewire', $data)->render());
    }

    // Output Vite asset tags for the given entry points.
    // Twig: {{ craft.vite() }}
    // Twig: {{ craft.vite(['resources/css/app.css', 'resources/js/app.ts']) }}
    public function vite(array $resources = ['resources/css/app.css', 'resources/js/app.ts']): HtmlString
    {
        $resourceString = "['" . implode("', '", $resources) . "']";
        return new HtmlString(Blade::render("@vite($resourceString)"));
    }

    // Output Livewire styles.
    // Twig: {{ craft.livewireStyles() }}
    public function liveWireStyles(): HtmlString
    {
        return new HtmlString(Blade::render("@livewireStyles"));
    }

    // Output Livewire scripts.
    // Twig: {{ craft.livewireScripts() }}
    public function livewireScripts(): HtmlString
    {
        return new HtmlString(Blade::render("@livewireScripts"));
    }

    // Output the Flux appearance script (dark/light mode toggle support).
    // Twig: {{ craft.fluxAppearance() }}
    public function fluxAppearance(): HtmlString
    {
        return new HtmlString(Blade::render("@fluxAppearance"));
    }

    // Output Flux scripts.
    // Twig: {{ craft.fluxScripts() }}
    public function fluxScripts(): HtmlString
    {
        return new HtmlString(Blade::render("@fluxScripts"));
    }

    public function buildQuery(string $modelClass) {
        return $modelClass::query();
    }

}