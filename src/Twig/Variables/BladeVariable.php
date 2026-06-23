<?php

namespace wsydney76\craft6blade\Twig\Variables;

use CraftCms\Cms\Support\Template;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Throwable;
use Twig\Markup;
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


    public function livewire(string $component, array $data = []): Markup
    {
        $arrayString = var_export($data, true);
        return Template::raw(Blade::render("@livewire('$component', $arrayString)"));
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