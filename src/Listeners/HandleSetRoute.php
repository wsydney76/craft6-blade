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

        if (str_starts_with($template, 'action:')) {
            $action = substr($template, strlen('action:'));

            // Remove the action trigger prefix if it's included in the template, e.g. "action:actions/article/show"
            $actionTriggerPrefix = app(GeneralConfig::class)->actionTrigger . '/';
            if (str_starts_with($action, $actionTriggerPrefix)) {
                $action = substr($action, strlen($actionTriggerPrefix));
            }

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

            $actionTrigger = app(GeneralConfig::class)->actionTrigger;
            Route::get("/{$actionTrigger}/tmproute", [$parts[1], $parts[2]]);


        }
    }
}
