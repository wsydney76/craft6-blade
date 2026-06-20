<?php

namespace wsydney76\craft6blade\Listeners;

use App\Modules\Main\Controllers\ArticleController;
use CraftCms\Cms\Config\GeneralConfig;
use CraftCms\Cms\Element\Events\SetRoute;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Config;
use Illuminate\Support\Facades\Route;
use function dd;

class HandleSetRoute
{
    public function handle(SetRoute $event): void
    {
        $element = $event->element;

        // Provisionally only top-level entries
        if (!($element instanceof Entry) || !$element->section) {
            return;
        }

        $template = $element->section->getSiteSettings()[$element->siteId]->template ?? null;

        if (!$template) {
            return;
        }

        // "blade:_entries.article.index" => render Blade view "_entries.article.index.blade.php"
        /*if (str_starts_with($template, 'blade:')) {
            $bladeView = substr($template, strlen('blade:'));

            $event->handled = true;
            $event->route = [
                'templates/render',
                [
                    'template' => $bladeView,
                    'variables' => [
                        'entry' => $element,
                    ],
                ],
            ];

            return;
        }*/

        // "_entries/article/index.blade.php" => render Blade view "_entries.article.index.blade.php"
        /*if (str_ends_with($template, '.blade.php')) {
            $bladeView = str_replace('/', '.', substr($template, 0, -strlen('.blade.php')));

            $event->handled = true;
            $event->route = [
                'templates/render',
                [
                    'template' => $bladeView,
                    'variables' => [
                        'entry' => $element,
                    ],
                ],
            ];

            return;
        }*/

        // "action:article/show" => dispatch to a Laravel controller via Craft's action routing.
        // The matched entry is available in the controller via MatchedElement::get().
        // Section site template setting example: action:article/show
        // Requires route in web.php, e.g. Route::get('/actions/article/show', [ArticleController::class, 'show']);
        // where the 'actions' prefix is the general config actionTrigger setting (defaults to 'actions')
        if (str_starts_with($template, 'action:')) {
            $action = substr($template, strlen('action:'));

            if ($action === '') {
                return;
            }

            $event->handled = true;
            $event->route = [$action, []];
        }

        if (str_starts_with($template, 'route:')) {
            $parts = explode(':', $template);
            $event->handled = true;
            $event->route = ['/tmproute', []];

           // dd([$parts[1], $parts[2]]);

            $actionTrigger = app(GeneralConfig::class)->actionTrigger;
            Route::get("/{$actionTrigger}/tmproute", [$parts[1], $parts[2]]);


        }
    }
}
