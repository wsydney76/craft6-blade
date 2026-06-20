# Craft 6 Blade

Helper plugin for Craft 6, using Blade templates for rendering.

Coming from Laravel, you may prefer using Blade templates for rendering your views. This plugin aims to bring some missing things from Twig to Blade.

* Familiar syntax for Laravel developers.
* Avoid the mental load of switching between Twig and Blade syntax.
* Use Laravel's component system for reusable UI elements.
* Leverage Laravel's advanced packages like Livewire and Flux.

## Disclaimer

Craft 6 itself is in alpha, this plugin not even this, so expect a lot of breaking changes, bugs and missing features.

## Installation

Currently, you need to clone this repository into a local directory.

TODO: Add installation instructions for installing from GitHub.

Example: `composer.json'

```json
{
  "minimum-stability": "dev",
  "prefer-stable": true,
  "require": {
    "wsydney76/craft6blade": "dev-main"
  },
  "repositories": [
    {
      "type": "path",
      "url": "/var/www/plugins/craft6-blade"
    }
  ]
}
```

Run `composer update` to composer-install the plugin and craft-install it via `artisan craft:plugin/install _craft6blade`,

## Template variables

Craft will inject the same variables into Blade views as it does for Twig views, e.g.

- `$currentSite`
- `$currentUser`
- `$now`

See `CraftCms\Cms\View\TemplateGlobals::resolve()` for the full list of variables.

## Routing for Craft Entries

### Blade view:

This is supported by Craft 6 natively.

In the sections site settings, enter a template path to the Blade view as you would for Twig, but with the `.blade.php` extension. For example: `.../entry.blade.php`.

Craft will render the view with an `$entry` variable available, which is the current entry being viewed.

> Bug or feature: Craft obviously ignores a `.blade.php` or `.twig` extension when resolving templates. So you cannot have both `entry.twig` and `entry.blade.php` in the same directory.

### Controller action:

Enter class name and method in the sections site settings, for example: `route:App\Http\Controllers\DemoController:show`.

In the controller action, you can retrieve the current entry using `MatchedElement::get()` and return a Blade view with any variables, for example:

```php
<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\MatchedElement;

class DemoController
{
    public function show()
    {
        return view('_entries.demo.from-controller', [
            'entry' => MatchedElement::get(),
            'settings' => Entry::find()->section('settings')->first(),
        ]);
    }
}

```

> Craft will only call the controller action if a matching live entry is found, so you don't need to worry about 404 handling in your controller.

### Route in `routes/web.php`

You can also define your own routes in `routes/web.php` and handle everything from there, for example:

This way you can have more control over the route, for example by adding middleware or defining a route name.

```php
Route::get('/actions/demo/show', [DemoController::class, 'show'])
    ->middleware(['auth'])
    ->name('demo.show');
```

The `actions` prefix must match the `actionTrigger()` setting in your Craft general configuration.

Enter the route in the sections site settings, for example: `route:demo/show`.

### Manual route

You can bypass Craft's element routing entirely and directly define a route for the entry URI, but this would mean losing the benefits of Craft's element routing, such as automatic 404 handling, site-specific URI's and the ability to access the matched element in the controller via `MatchedElement::get()`.

## Retrieving additional data in the view

Besides the variables injected by Craft or your controller, you can also use additional data in your Blade views.

### Retrieve data in your Blade template

```php
@php($entries = \CraftCms\Cms\Entry\Elements\Entry::find()->section('news')->get())
```
However, this is not recommended as it mixes data retrieval with presentation logic.

### View composers

A better approach is to use view composers, which allow you to bind data to views globally or for specific views.

In a service provider:

```php
use CraftCms\Cms\Entry\Elements\Entry;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

ViewFacade::composer('*', function (View $view) {
    $view->with('settings', Entry::find()->section('settings')->one());
});
```

## Porting Twig functions and filters to Blade

For now this plugin does not aim to port all Twig functions and filters to Blade, but here are the ones we came across and how to handle them in Blade (in random order):

### redirect

Don't use this in your Blade views, instead return a redirect response from your controller action:

### User and Permission checks

> Looks like User management is work in progress in Craft 6, so this may change a lot. Holding back on implementing this until it's more stable.

Again, don't use these in your Blade views, instead use middleware or controller logic to check for permissions and return an error response if the user doesn't have access.

But just in case, implemented directives:

* `@requireAdmin`
* `@requireLogin`
* `@requireGuest`
* `@requirePermission('permissionName')`

### Pagination

Laravel's pagination can also be used for Craft's element queries, so no need to port the `paginate` Twig tag.

```php
$entries = Entry::find()->section('news')->paginate(10);
```

Built-in pagination links can be rendered in the view with:

```blade
{{ $entries->links() }}
```

Or build your own pagination links with the paginator's methods:

```blade
@if ($entries->hasPages())
    // do stuff
@endif    
```

Some convenience methods from Craft's pagination may be missing, but could be added as a macro if required.

### Template caching

This is a (mostly AI generated) implementation from [Craft 5](https://github.com/wsydney76/craft5-blade#template-fragment-caching-cache--endcache), based on analyzing compiled templates. 

TODO: For Craft 6, just replaced the underlying service class. Review and refactor based on compiled views.

Experimental.

The `@cache` directive pair mirrors Craft’s Twig `{% cache %}` tag behavior.

See general config, e.g.: `->enableTemplateCaching(!app()->hasDebugModeEnabled())`

Basic usage (no options):

```blade
@cache
    ... the content to cache ...
@endcache
```

With options (all keys optional):

```blade
@cache([
    'key' => 'thekey',
    'global' => true,
    'duration' => '1 hour',
    'expiration' => 1735689600,
])
    Hallo
@endcache
```

Conditional caching (Craft Twig `{% cache if ... %}` / `{% cache unless ... %}` equivalents):

```blade
@cache(['if' => craft()->app->request->isMobileBrowser()])
    This is only cached for mobile browsers.
@endcache

@cache(['unless' => $currentUser])
    This is cached unless a user is logged in.
@endcache
```

Options:

- `key` (string): Cache key override. If omitted, a deterministic key is generated.
- `global` (bool): Whether the cache is global. Default: `false`.
- `duration` (?string): Cache duration (e.g. `'1 hour'`). Default: `null`.
- `expiration` (mixed): Explicit expiration value (timestamp/DateTime/etc.). Default: `null`.
- `if` (mixed): Only use the cache when this is truthy.
- `unless` (mixed): Only use the cache when this is falsey.

Note: Uses Craft's TemplateCaches service under the hood, so (in theory) should behave the same, including cache invalidation.

Cache fragments created via Twig and via Blade are interoperable: if both use the same cache key and are 'global', they refer to the same underlying Craft template cache entry and can be reused interchangeably.

## Helper functions

Most Twig functions and filters can be replaced with PHP/Blade equivalents.

See [Helpers](https://laravel.com/docs/13.x/helpers#main-content) and [String Helpers](https://laravel.com/docs/13.x/strings).

Some additional helper functions are provided by this plugin, see below.

TODO: For now, this uses the global namespace, but could be moved to a custom namespace if needed.

TODO: Check examples if output is correctly escaped.

### Quick Reference

- `sanitize(HtmlString|string $html): HtmlString`
- `md(string $text, ?string $flavor = null): HtmlString`
- `tag(string $type, array|string $attributes = ''): HtmlString`
- `truncate(string $string, int $length, string $suffix = '…', bool $splitSingleWord = true): string`
- `asDate($date, $format = 'short'): string`
- `asDateTime($date, $format = 'short'): string`
- `asDateTimeLong($date): string`
- `t(string $text, array $parameters = [], ?string $category = 'site', ?string $locale = null): string`
- `renderTwig($template, array $data = [], ?TemplateMode $templateMode = null): HtmlString`
- `single(string $section): ?Entry`

## Usage in Blade

### `sanitize()`

Sanitize HTML and return safe markup the Craft way.

```blade
{{ sanitize($entry->body) }}
```

### `md()`

Parse Markdown to HTML.

```blade
{{ md($entry->summary) }}
{{ md($entry->content, 'gfm') }}
```

You may need to sanitize the output if the Markdown content is user-generated:

```blade
{{ sanitize(md($entry->content)) }}

{{ $text |> md(...) |> sanitize(...) }}
```

### `tag()`

Create an HTML tag string.

```blade
{{ tag('h2', 'The Heading') }}
```

### `truncate()`

Trim text to a maximum length.

```blade
{{ truncate($entry->excerpt, 140) }}
{{ truncate($entry->excerpt, 140, '...', false) }}
```

> There is a corresponding `limit()` helper in Laravel, which has subtle differences in behavior, so we implemented our own `truncate()` helper to match Twig's `truncate` filter behavior more closely.

### `asDate()` / `asDateTime()`

Format a date or date-time value.

Brings Craft's date formatting capabilities to Blade, using format shortcuts like `short`, `medium`, `long`, and respecting site locale and timezone settings.

Uses `CraftCms\Cms\Translation\Formatter` under the hood.

```blade
{{ asDate($entry->postDate) }}
{{ asDate($entry->postDate, 'long') }}

{{ asDateTime($entry->postDate) }}
{{ asDateTime($entry->postDate, 'long') }}
```

### `t()`

Translate a string, using Craft's translation system.

Uses `CraftCms\Cms\t` function under the hood, just without a namespace.

```blade
{{ t('Read more') }}
{{ t('Welcome, {name}', ['name' => $currentUser->friendlyName]) }}
{{ t('My message', [], 'site', 'de-DE') }}
```

### `renderTwig()`

Render an existing Twig template and include the result.

```blade
{!! renderTwig('_partials/card.twig', ['entry' => $entry]) !!}
```

### `single()`

Fetch the first entry in a section.

Single entries are not prefetched by Craft, so this helper provides a convenient way to retrieve them without needing to set up a full element query.

```blade
@if($home = single('home'))
    <h1>{{ $home->title }}</h1>
@endif
```

> You can also pass single entries into your views via view composers or controller logic.

## Handling Matrix fields in Blade

You can handle Matrix fields with different entry types by using Blade's dynamic components.

The entry template:

```blade
<x-blocks :blocks="$entry->contentBuilder->collect()" />
```

The blocks component:

```blade
@props([
    'blocks',
])

<div {{ $attributes->class(['prose mt-4']) }}>
    @foreach ($blocks as $block)
        <x-dynamic-component :component="'blocks.' . $block->type->handle" :block="$block" />
    @endforeach
</div>
```

Block template example `blocks/text.blade.php`:

```blade
<div>
    {!! $block->text
        |> (fn($text) => md($text, 'gfm'))
        |> sanitize(...) !!}
</div>
```

## Using Blade functionality from Twig

Bringing some Blade functionality to Twig.

### Use Laravel's named routes in Twig

```twig
<a href="{{ route('demo.show') }}">Demo</a>
```

### Rendering Blade views from Twig

In Twig Templates, you can render Blade views via the `renderBlade` function, which accepts the same parameters as the `renderTwig` function described above.

```twig
{{ renderBlade('_partials/card.blade.php', { entry: entry }) }}
```

### Using Livewire components in Twig

Experimental.

This plugin provides a `laracraft` Twig variable with some helper methods.

TODO: Naming. We are not the first with this semi-funny idea...

```twig
{{ laracraft.livewire({
    component: 'components::demos.search-twig'
}) }}
```

Some helper functions to load required assets, if not already bundled in your main layout:

```twig
{{ laracraft.livewireStyles() }}
{{ laracraft.livewireScripts() }}
{{ laracraft.fluxAppearance() }}
{{ laracraft.fluxScripts() }}
```

### Laravel Helpers in Twig

The plugin exposes Laravel helper classes:

```twig
{{ Str.of('This is my name').after('This is') }}
{{ Arr.crossJoin([1, 2], ['a', 'b']) }}
{{ Number.forHumans(489939) }}
```

### Querying Eloquent models in Twig

```twig
{% set query = laracraft.buildQuery('App\\Modules\\Main\\Models\\Subscription') %}
{% set subscriptions = query.latest().take(3).get() %}
```