<?php

namespace wsydney76\craft6blade\Support;

use Illuminate\Support\Facades\Blade;

class BladeDirectives
{
    public static function register(): void
    {

        Blade::directive('requireAdmin', function(string $expression): string {
            return "<?php if (!\Illuminate\Support\Facades\Auth::user()?->admin) { abort(403, 'Admin access required.'); }  ?>";
        });

        Blade::directive('requireLogin', function(string $expression): string {
            return "<?php if (! Auth('craft')->check()) { abort(403, 'Login required.'); } ?>";
        });

        Blade::directive('requireGuest', function(string $expression): string {
            return "<?php if (Auth('craft')->check()) { abort(403, 'Guest access required.'); } ?>";
        });

        Blade::directive('requirePermission', function(string $expression): string {
            return "<?php if (!Auth('craft')->user()?->can($expression)) { abort(403, 'Insufficient permissions.'); } ?>";
        });

        // Craft template fragment cache (mirrors Twig's `{% cache %}...{% endcache %}` behavior).
        // Usage:
        //   @cache
        //      ...
        //   @endcache
        //
        // Note: For now we support the no-args form (same as `{% cache %}` with no options).
        Blade::directive('cache', function(string $expression): string {
            return static::compileCacheStart($expression);
        });

        Blade::directive('endcache', function(): string {
            return static::compileCacheEnd();
        });
    }

    /**
     * Compiler for the cache directive (start).
     *
     * Generates PHP equivalent to Craft's compiled Twig `{% cache %}` tag.
     *
     * Current support:
     * - No-args usage only.
     *
     * Notes:
     * - Uses a static counter so nested blocks get unique variable names.
     * - Uses a deterministic cache key based on file + line (when available) + expression.
     *
     * TODO: This is copied 1:1 from Craft 5, just replacing service classes
     */
    private static function compileCacheStart(string $expression): string
    {
        $template = <<<'PHP'
<?php
    $__bladeCacheService = app(CraftCms\Cms\View\TemplateCaches::class);
    $__bladeCacheRequest = request();

    // Use a global counter to avoid "Duplicate declaration of static variable" when multiple @cache blocks
    // exist in the same compiled template.
    $__bladeCacheCounterKey = '__wsydney76_blade_cache_counter';
    if (!isset($GLOBALS[$__bladeCacheCounterKey]) || !is_int($GLOBALS[$__bladeCacheCounterKey])) {
        $GLOBALS[$__bladeCacheCounterKey] = 0;
    }
    $GLOBALS[$__bladeCacheCounterKey]++;

    $__bladeCacheI = $GLOBALS[$__bladeCacheCounterKey];

    // Options (all optional): ['key' => string, 'global' => bool, 'duration' => ?string, 'expiration' => mixed]
    $__bladeCacheOptions = %s;

    if (!is_array($__bladeCacheOptions)) {
        $__bladeCacheOptions = (array)$__bladeCacheOptions;
    }

    $__bladeCacheGlobal = (bool)($__bladeCacheOptions['global'] ?? false);
    $__bladeCacheDuration = $__bladeCacheOptions['duration'] ?? null;
    $__bladeCacheExpiration = $__bladeCacheOptions['expiration'] ?? null;
    $__bladeCacheIf = $__bladeCacheOptions['if'] ?? true;
    $__bladeCacheUnless = $__bladeCacheOptions['unless'] ?? false;

    // Match Craft's ignore conditions (live preview or tokenized preview requests)
    ${"__bladeIgnoreCache{$__bladeCacheI}"} = ($__bladeCacheRequest->isPreview() || $__bladeCacheRequest->getToken()) || (!($__bladeCacheIf) || ($__bladeCacheUnless));

    if (!${"__bladeIgnoreCache{$__bladeCacheI}"}) {
        // Cache key: allow explicit override, otherwise use deterministic key
        $__bladeCacheKeyValue = $__bladeCacheOptions['key'] ?? null;
        if (!$__bladeCacheKeyValue) {
            $__bladeCacheKeyValue = hash('sha256', (__FILE__ ?? '') . '|' . (__LINE__ ?? '') . '|' . json_encode($__bladeCacheOptions));
        }
        ${"__bladeCacheKey{$__bladeCacheI}"} = (string)$__bladeCacheKeyValue;

        ${"__bladeCacheBody{$__bladeCacheI}"} = $__bladeCacheService->getTemplateCache(${"__bladeCacheKey{$__bladeCacheI}"}, $__bladeCacheGlobal, true);
    } else {
        ${"__bladeCacheBody{$__bladeCacheI}"} = null;
    }

    if (${"__bladeCacheBody{$__bladeCacheI}"} === null) {
        if (!${"__bladeIgnoreCache{$__bladeCacheI}"}) {
            // Keep original defaults (withResources=true, global=false) unless overridden
            $__bladeCacheService->startTemplateCache(true, $__bladeCacheGlobal);
        }
        ob_start();
?>
PHP;

        // Blade passes an empty string when directive has no parentheses.
        // Treat missing expression as an empty options array.
        $expr = trim($expression);
        if ($expr === '') {
            $expr = '[]';
        }

        return \sprintf($template, $expr);
    }

    /**
     * Compiler for the cache directive (end).
     */
    private static function compileCacheEnd(): string
    {
        $template = <<<'PHP'
<?php
        ${"__bladeCacheBody{$__bladeCacheI}"} = ob_get_clean();
        if (!${"__bladeIgnoreCache{$__bladeCacheI}"}) {
            $__bladeCacheService->endTemplateCache(
                ${"__bladeCacheKey{$__bladeCacheI}"},
                $__bladeCacheGlobal,
                $__bladeCacheDuration,
                $__bladeCacheExpiration,
                ${"__bladeCacheBody{$__bladeCacheI}"},
                true
            );
        }
    }

    echo ${"__bladeCacheBody{$__bladeCacheI}"};

    unset(
        $__bladeCacheService,
        $__bladeCacheRequest,
        $__bladeCacheI,
        $__bladeCacheOptions,
        $__bladeCacheGlobal,
        $__bladeCacheDuration,
        $__bladeCacheExpiration,
        $__bladeCacheKeyValue,
        $__bladeCacheIf,
        $__bladeCacheUnless
    );
?>
PHP;

        return $template;
    }

}

