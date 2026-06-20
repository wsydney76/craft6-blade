<?php

namespace wsydney76\craft6blade\Twig\Extensions;

use CraftCms\Cms\Support\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use wsydney76\craft6blade\Twig\Variables\BladeVariable;

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
            // Returns a Twig Markup/"raw" value so the Blade output isn't double-escaped by Twig.
            // Treat the Blade output as trusted HTML, similar to Twig's `|raw`.
            new TwigFunction('renderBlade', function(string $view, array $data = []) {
                return Template::raw(Blade::render($view, $data));
            }),

            new TwigFunction('route', function($name, $parameters = [], $absolute = true) {
                return route($name, $parameters , $absolute);
            }),

            new TwigFunction('str', function() {
                return new class
                {
                    public function __call($method, $parameters)
                    {
                        return Str::$method(...$parameters);
                    }

                    public function __toString()
                    {
                        return '';
                    }
                };
            }),

            new TwigFunction('trans_choice', function($key, $number, array $replace = [], $locale = null): string {
                return app('translator')->choice($key, $number, $replace, $locale);
            })
        ];
    }

    public function getGlobals(): array
    {
        return [
            'laracraft' => new BladeVariable(),
            'Str' => new Str(),
            'Arr' => new Arr(),
            'Number' => new Number()
        ];
    }
}