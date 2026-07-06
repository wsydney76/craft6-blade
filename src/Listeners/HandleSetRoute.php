<?php

namespace wsydney76\craft6blade\Listeners;

use CraftCms\Cms\Element\Contracts\ElementInterface;
use CraftCms\Cms\Element\Events\SetRoute;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\ControllerRoute;
use http\Exception\InvalidArgumentException;
use function config;

class HandleSetRoute
{
    public function handle(SetRoute $event): void
    {
        $element = $event->element;

        // Provisionally only top-level entries
        if (!($element instanceof Entry) || !$element->section) {
            return;
        }

        $template = $this->getTemplate($element);

        if (!$template) {
            return;
        }

        $action = null;
        $apply = config('craft._craft6blade.setRoute.apply', 'settings');

        // Always use setting '::'
        if ($apply === 'force') {
            $action = $this->getActionFromShortcut('::', $this->getHandle($element));
        }

        // Shortcut
        elseif (str_contains($template, '::')) {
            $action = $this->getActionFromShortcut($template, $this->getHandle($element));
        }

        // Full action with controller class and method
        elseif (str_starts_with($template, 'route:')) {

            $action = explode(':', $template);
            array_shift($action);

            if (count($action) !== 2) {
                throw new InvalidArgumentException('Template must have exactly 2 route parts after prefix, separated by colon.');
            }
        }

        if ($action) {
            // Each entry is interpreted as 'queryParam' => defaultValue,
            // allowing the value to be overridden per-request via query string.
            $extra = config('craft._craft6blade.setRoute.extra', []);

            $params = [];
            foreach ($extra as $queryParam => $default) {
                $params[$queryParam] = request()->query($queryParam, $default);
            }

            $event->route = new ControllerRoute($action, $params);

            $event->handled = true;
        }
    }

    private function getActionFromShortcut(string $template, string $handle): ?array
    {
        [$shortcutHandle, $method] = explode('::', $template, 2);

        // Fall back to the section handle if none was given in the shortcut
        $handle = $shortcutHandle ?: $handle;

        // Default to the 'show' method if none was given
        $method = $method ?: 'show';

        // Expand to full controller class name
        $controller = "App\\Http\\Controllers\\" . ucfirst($handle) . "Controller";

        return [$controller, $method];
    }

    /**
     * @param ElementInterface $element
     * @return string|null
     */
    protected function getTemplate(ElementInterface $element): ?string
    {
        return $element->section->getSiteSettings()[$element->siteId]->template ?? null;
    }

    /**
     * @param ElementInterface $element
     * @return string|null
     */
    protected function getHandle(ElementInterface $element): ?string
    {
        return $element->section->handle;
    }
}
