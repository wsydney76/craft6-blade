<?php

namespace wsydney76\craft6blade;

use CraftCms\Cms\Element\Events\SetRoute;
use CraftCms\Cms\Plugin\Concerns\HasConfig;
use CraftCms\Cms\Plugin\Plugin as BasePlugin;
use CraftCms\Cms\Support\Facades\Twig;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Override;
use wsydney76\craft6blade\Commands\PublishCommand;
use wsydney76\craft6blade\Listeners\HandleSetRoute;
use wsydney76\craft6blade\Twig\Extensions\BladeTwigExtension;
use function app_path;
use function config;
use function dd;


class Plugin extends BasePlugin
{
    use HasConfig;

    public string $schemaVersion = '1.0.0';

    public bool $hasCpSettings = false;

    public array $commands = [
        PublishCommand::class,
    ];

    #[Override]
    public function bootPlugin(): void
    {


        $path = app_path('c6b/functions.php');
        if (file_exists($path)) {
            require_once $path;
        }


        if (config('craft._craft6blade.setRoute.apply', 'settings') !== 'never') {
            Event::listen(SetRoute::class, HandleSetRoute::class);
        }

        foreach (config('craft._craft6blade.anonymousComponentPaths', []) as $path) {
            Blade::anonymousComponentPath($path);
        }

        foreach (config('craft._craft6blade.bladeShared', []) as $key => $value) {
            View::share($key, $value);
        }

        foreach (config('craft._craft6blade.bladeDirectives', []) as $name => $callback) {
            Blade::directive($name, $callback);
        }

        foreach (config('craft._craft6blade.bladeStringables', []) as $name => $callback) {
            Blade::stringable($name, $callback);
        }

        foreach (config('craft._craft6blade.bladeIfs', []) as $name => $callback) {
            Blade::if($name, $callback);
        }

        foreach (config('craft._craft6blade.bladeViewComposers', []) as $view => $callback) {
            View::composer($view, $callback);
        }

        foreach (config('craft._craft6blade.bladeComponents', []) as $name => $class) {
            Blade::component($name, $class);
        }

        foreach (config('craft._craft6blade.bladeFunctions', []) as $file) {
            if(file_exists($file)) {
                require_once $file;
            }
        }

        Twig::registerExtension(new BladeTwigExtension());


    }
}
