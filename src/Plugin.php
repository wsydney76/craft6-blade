<?php

namespace wsydney76\craft6blade;

use Carbon\Carbon;
use CraftCms\Cms\Element\Events\SetRoute;
use CraftCms\Cms\Plugin\Plugin as BasePlugin;
use CraftCms\Cms\Support\Facades\Twig;
use CraftCms\Cms\View\Events\SiteTemplateRootsResolving;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Override;
use wsydney76\craft6blade\Listeners\HandleSetRoute;
use wsydney76\craft6blade\Support\BladeDirectives;
use wsydney76\craft6blade\Support\BladeIfs;
use wsydney76\craft6blade\Support\CraftMacros;
use wsydney76\craft6blade\Twig\Extensions\BladeTwigExtension;
use function app;
use function dd;
use function public_path;
use function request;


class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';

    public bool $hasCpSettings = false;

    public bool $config = false;

    #[Override]
    public function bootPlugin(): void
    {

        Carbon::setLocale(app()->getLocale());

        View::addNamespace('craft6blade', __DIR__ . '/../resources/views');

        BladeDirectives::register();

        // Note: We purposely don't rely on Composer autoload for these files.
        // They define global functions (helpers/filters) which may depend on Craft being initialized.

        require_once 'Support/Helpers.php';

        Event::listen(SetRoute::class, HandleSetRoute::class);

        Twig::registerExtension(new BladeTwigExtension());
    }
}
