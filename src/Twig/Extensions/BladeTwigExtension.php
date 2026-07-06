<?php

namespace wsydney76\craft6blade\Twig\Extensions;

use CraftCms\Cms\Support\Template;
use Illuminate\Database\Eloquent\Model;
use CraftCms\Cms\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Number;
use CraftCms\Cms\Support\Str;
use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\Markup;
use Twig\TwigFunction;

class BladeTwigExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * Register custom Twig functions.
     *
     * @return array<TwigFunction> Array of registered Twig functions
     */
    public function getFunctions(): array
    {
        // Define custom Twig functions
        // (see https://twig.symfony.com/doc/3.x/advanced.html#functions)
        return [

            new TwigFunction('c6b_livewire', function (string $component, array $data = []): Markup {
                $arrayString = var_export($data, true);
                return Template::raw(Blade::render("@livewire('$component', $arrayString)"));
            }),

            new TwigFunction('c6b_vite', function (array $resources = ['resources/css/app.css', 'resources/js/app.ts']): HtmlString {
                $resourceString = "['" . implode("', '", $resources) . "']";
                return new HtmlString(Blade::render("@vite($resourceString)"));
            }),

            new TwigFunction('c6b_livewireStyles', function (): HtmlString {
                return new HtmlString(Blade::render('@livewireStyles'));
            }),

            new TwigFunction('c6b_livewireScripts', function (): HtmlString {
                return new HtmlString(Blade::render('@livewireScripts'));
            }),

            new TwigFunction('c6b_fluxAppearance', function (): HtmlString {
                return new HtmlString(Blade::render('@fluxAppearance'));
            }),

            new TwigFunction('c6b_fluxScripts', function (): HtmlString {
                return new HtmlString(Blade::render('@fluxScripts'));
            }),

            new TwigFunction('c6b_buildQuery', function (string $modelClass) {
                if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
                    throw new InvalidArgumentException("[$modelClass] must be an Eloquent model class.");
                }

                /** @var class-string<Model> $modelClass */
                return $modelClass::query();
            }),

            new TwigFunction('c6b_route', function ($name, $parameters = [], $absolute = true) {
                return route($name, $parameters, $absolute);
            }),

            new TwigFunction('c6b_trans_choice', function ($key, $number, array $replace = [], $locale = null): string {
                return app('translator')->choice($key, $number, $replace, $locale);
            })
        ];
    }

    public function getGlobals(): array
    {
        return [
            'Str' => new Str(),
            'Arr' => new Arr(),
            'Number' => new Number()
        ];
    }
}