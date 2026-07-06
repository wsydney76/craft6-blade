# Craft 6 Blade

Helper plugin for Craft 6, using Blade templates for rendering.

Coming from Laravel, you may prefer using Blade templates for rendering your views and controllers for preparing your data. 

* Familiar syntax for Laravel developers.
* Avoid the mental load of switching between Twig and PHP syntax.
* Use Laravel's component system structuring.
* Leverage Laravel's advanced packages like Livewire and Flux.
* Reuse existing custom component libraries.

This plugin aims to bring some missing things from Twig to Blade.

This README also includes some general tips and examples for using Blade in Craft.

## Disclaimer

Craft 6 itself is in alpha, this plugin not even this, so expect a lot of breaking changes, bugs and missing features.

## AI note

The code is handcrafted.

This README is mostly written by hand, but accepting some AI completions here and there.

Experimental: Optional [helper functions](#experiment-ai-generated-helper-functions) are AI generated, based on analyzing Craft's Twig extensions.

## Installation

Requires Craft 6 alpha 10.

Currently, you need to clone this repository into a local directory.

TODO: Add installation instructions for installing from GitHub.

Example: `composer.json`

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

## Configuration

You can configure the plugin in `config/craft/_craft6blade.php`. Copy the plugins example config file from `<vendorPath>/config/_craft6blade.php` to `config/craft/_craft6blade.php` and adjust it to your needs.

## Official Blade Support

See [docs](https://github.com/craftcms/cms/blob/6.x/docs/blade.md)

Some notes:

### Template variables

Craft will inject the same variables into Blade views as it does for Twig views, e.g.

- `$currentSite`
- `$currentUser`
- `$now`

See [Template Globals](https://github.com/craftcms/cms/blob/6.x/docs/blade.md#template-globals) for the full list of variables.

### Private/Public Templates

Blade templates follow the same rules as Twig templates, so by default, they are publicly accessible via their path, but not if they are in a subdirectory starting with an underscore `_` (or what is configured in the `privateTemplateTrigger` general config setting).

This is probably not what you want, at least not for anonymous components.

* Recommended: use the general config `->privateTemplateTrigger('')` to stop that behavior.
* Alternative: use `_` for private directory/filenames.
* The `components` directory is hardcoded, but you can configure an additional private directory and place your components there, e.g. `resources/views/_components`.

```php
// in the `config/craft/_craft6blade.php` config file:

return [
     'anonymousComponentPaths' => [resource_path('views/_components')],
];  
```

### Rendering Twig templates from Blade

You can render Twig templates from Blade views using the engine agnostic `template()` helper function.

```blade
@php
    use function CraftCms\Cms\template;
@endphp

{{ template('_partials/card.twig', ['entry' => $entry]) }}
```

### Directives

Craft itself registers a number of Blade directives, see [docs](https://github.com/craftcms/cms/blob/6.x/docs/blade.md#page-lifecycle) ff.

This plugin does not add any directives.

## Routing for Craft Entries

Besides calling a template directly, you can also route to a controller action when an entry is requested.

You define how a request for an entry should be handled in the sections site settings, the plugin handles special `route:` and `action` prefixes.

* Template directly: No special prefix, just the template path.
* Controller action: `route:ClassName:methodName` or shortcut (see below).

Currently only implemented for top-level entries.

> The controller can of course also render a Twig or Inertia view if you prefer.

### Blade view:

This is supported by Craft 6 natively.

In the sections site settings, enter a template path to the Blade view as you would for Twig, but with the `.blade.php` extension. For example: `.../entry.blade.php`.

Craft will render the view with an `$entry` variable available.

> Craft ignores a `.blade.php` or `.twig` extension when resolving templates. So you cannot have both `entry.twig` and `entry.blade.php` in the same directory. Default precedence is .twig over .blade.php, but you can change that in the general config with `->defaultTemplateExtensions(['blade.php', 'twig'])`.

### Controller action:

Enter class name and method in the sections site settings, for example: `route:App\Http\Controllers\DemoController:show`.

The plugin intercepts the request and routes the request to the specified controller action.

In the action, you can retrieve the current entry using a request param and return a Blade view with any variables, for example:

```php
<?php

namespace App\Http\Controllers;

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Route\MatchedElement;

class DemoController
{
    public function show(Entry $entry)
    {
        return view('_entries.demo.from-controller', [
            'entry' => $entry,
            'settings' => Entry::find()->section('settings')->first(),
        ]);
    }
}

```

> Craft will only call the controller action if a matching live entry is found, so you don't need to worry about 404 handling in your controller.

### Shortcut for controller action:

Instead of writing the full `route:ClassName:methodName` string, you can use a shorter `<handle>::<method>` convention in the sections site settings. The plugin recognizes any template value containing `::` as a shortcut.

The shortcut expands to a controller action like this:

* **Controller:** `App\Http\Controllers\` + `ucfirst(<handle>)` + `Controller`
* **Method:** `<method>`

Both parts are optional:

* If `<handle>` is omitted, it falls back to the entry's **section handle**.
* If `<method>` is omitted, it defaults to `show`.

Examples:

| Section Handle | Shortcut      | Controller                              | Method  |
|----------------|---------------|-----------------------------------------|---------|
| news           | `::`          | `App\Http\Controllers\NewsController`   | `show`  |
| news           | `news::show`  | `App\Http\Controllers\NewsController`   | `show`  |
| newsIndex      | `news::index` | `App\Http\Controllers\NewsController`   | `index` |

### Configuring `setRoute` behavior

The plugin listens for Craft's `SetRoute` event and decides how to route an entry request based on the `setRoute` settings in your `config/craft/_craft6blade.php` config file.

```php
<?php

return [
    // ...
    'setRoute' => [
        'apply' => 'settings', // 'settings', 'never', or 'force'
        'extra' => [],         // 'queryParam' => defaultValue
    ],
];
```

#### `setRoute.apply`

Controls whether and how the plugin resolves entry requests to controller actions.

| Value        | Behavior                                                                                                                                                                             |
|--------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `'settings'` | **Default.** Uses the template value from the section's site settings. |
| `'force'`    | Ignores the section's template value and always resolves to the `::` shortcut, i.e. `App\Http\Controllers\<Ucfirst(sectionHandle)>Controller@show`.                                  |
| `'never'`    | Disables the listener entirely. The plugin never intercepts the `SetRoute` event, so Craft's default template routing applies. (Registered in `Plugin.php`: the listener is only bound when `apply` is not `'never'`.)                    |

You should use `'never'` if you want to handle routing yourself, e.g. with a custom route in `routes/web.php` or a custom event listener. This way you can control the behavior in greater detail, e.g. for multi-level entries or other project-specific routing logic.

Your own event listener can co-exist, e.g. if you want to handle other element types or nested entries in a matrix field.

> Routing is currently only applied to top-level entries.

#### `setRoute.extra`

An array of extra parameters passed to the resulting `ControllerRoute`. Each entry is defined as `'queryParam' => defaultValue`. The value can be overridden per-request via the query string, otherwise the default is used.

```php
'setRoute' => [
    'apply' => 'settings',
    'extra' => [
        'format' => 'html',      // ?format=json overrides the default of 'html'
    ],
],
```

The resolved parameters become available to your controller action as method arguments.

```php
public function show(Entry $entry, string $format) 
{
   // ...

   return match ($format) {
        'html' => view('_entries.demo.show', $this->getData($entry)),
    
        'json' => response()->json($entry->detailsToArray(), options: JSON_PRETTY_PRINT),
    
        'pdf' => Pdf::loadView('_entries.demo.pdf', ['entry' => $entry])->stream('demo.pdf'),
    
        default => response()->json(['error' => 'Invalid format'], 400),
    };
}
```

### Manual route


You can bypass Craft's element routing entirely and directly define a route for the entry URI.

A route in `routes/web.php` 'wins' over Craft's element routing.

routes/web.php:

```php
use CraftCms\Cms\Http\Middleware\ResolveSite;

Route::get('{site}/demo/{slug}', [DemoController::class, 'showByManualRoute'])
    ->middleware([ResolveSite::class]);
``` 

Controller action:

```php
public function showByManualRoute(string $site, string $slug)
{
    // or handle the site and slug yourself, e.g. with a query to find the entry
    $entry = c6b_getMatchedElement();
    if (! $entry) {     
        abort(404);
    }
    
    // ...
}
```

Experimental

For convenience, the plugin includes a variant of Craft's `HandleMatchedElementRoute` middleware, that just lets the request flow to your custom controller.

It throws a `404` exception, if no matching element was found, and allows to use `MatchedElement::get()` in your controller.

```php
Route::get('{site}/demo/{slug}', [DemoController::class, 'showByManualRoute'])->middleware([
    ResolveSite::class,
    CustomGetMatchedElement::class,
]);
```

### Custom Route for non-GET requests

For non-GET requests, you have to use a custom route and controller action.

Example for a POST request to submit a form, updating an entry from the frontend:

Route:

```php
Route::post('en/article/{slug}', [ArticleController::class, 'update'])
    ->middleware('auth', ResolveSite::class, CustomGetMatchedElement::class)
    ->name('article.update');
```

Form in the Blade view for the article entry:

```blade
@can('save', $entry)
    <x-partials.flash />

    <form method="post" class="mb-8 flex gap-4">
        @csrf
        <input class="w-full" name="teaser" value="{{ $entry->teaser }}" />
        <button type="submit">Submit</button>
    </form>
@endcan
```

Controller action:

```php
class ArticleController

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Http\RespondsWithFlash;
use CraftCms\Cms\Route\MatchedElement;
use CraftCms\Cms\Support\Facades\Elements;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function CraftCms\Cms\currentUserElement;
use function CraftCms\Cms\t;

{
    use RespondsWithFlash;
    // ...
    public function update(string $slug)
    {   
        $entry = MatchedElement::get();
    
        if (!($entry instanceof Entry)) {
            throw new NotFoundHttpException();
        }
    
        if (!$entry->canSave(currentUserElement())) {
            throw new AccessDeniedHttpException(
                t('You are not authorized to update this entry.', [], 'site'),
            );
        }
    
        // Update the entry
        // $entry->title = ''; // trigger error
        $entry->teaser = request('teaser');
    
        // Save the entry
        if (!Elements::saveElement($entry)) {
            return $this->asFailure(
                t('Failed to update entry', [], 'site') .': ' .implode(', ', $entry->getFirstErrors()),
            );
        }
    
        return $this->asSuccess(t('Entry updated successfully', [], 'site'));
    }
}
```

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

### Redirect

Don't use this in your Blade views, instead return a redirect response from your controller action:

### User and Permission checks

See Craft's directives: [Auth and Edition Requirements](https://github.com/craftcms/cms/blob/6.x/docs/blade.md#auth-and-edition-requirements)

Again, don't use these in your Blade views, instead use middleware or controller logic to check for permissions and return an error response if the user doesn't have access.


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

> All of this is also available in Twig.

Also see [Pagination directive](https://github.com/craftcms/cms/blob/6.x/docs/blade.md#pagination)

### Template caching

See Craft's [Template Cache Directives](https://github.com/craftcms/cms/blob/6.x/docs/blade.md#template-cache-directives)

### Localization

This is an open issue, we still have to figure out the best way to organize translation files so that they can be used without duplication everywhere.

Both Craft and Laravel provide translation/localization methods, maybe we have to make a choice and use only one consistently.

For now there are some helper functions below.

## More Helper functions

Most Twig functions and filters can be replaced with PHP/Laravel equivalents or Craft's API.

See [Helpers](https://laravel.com/docs/13.x/helpers#main-content) and [String Helpers](https://laravel.com/docs/13.x/strings).

Some additional helper functions are provided by this plugin, see below.

> Sometimes, there are subtle differences in behavior between Twig and Laravel functions, so we implemented some custom helper functions to match Twig's behavior more closely. Especially for functions/filters that generate human-readable HTML output, so that the user experience is consistent, regardless of whether the content is rendered in Twig or Blade.

These helpers are registered as global functions for ergonomic template usage, but now use a `c6b_` prefix (`c6b_sanitize()`, `c6b_t()`, etc.) as we ran into collisions with other packages. Decided to use a prefix because that seems easer for developers, avoiding the need to import a namespace in templates. The `c6b_` prefix makes clear where these functions come from, make it easy to discover via global search/replace, and is suppored by IDE syntax completion.

> Make sure output is correctly escaped or sanitized, that maybe skipped in examples for simplicity.

## Usage in Blade

### `c6b_asDate()` / `c6b_asDateTime()`

Signature: `c6b_asDate($date, $format = 'short'): string`, `c6b_asDateTime($date, $format = 'short'): string`

Format a date or date-time value.

Brings Craft's date formatting capabilities to Blade, using format shortcuts like `short`, `medium`, `long`, and respecting site locale and timezone settings.

Uses `CraftCms\Cms\Translation\Formatter` under the hood.

```blade
{{ c6b_asDate($entry->postDate) }}
{{ c6b_asDate($entry->postDate, 'long') }}

{{ c6b_asDateTime($entry->postDate) }}
{{ c6b_asDateTime($entry->postDate, 'long') }}
```

### `c6b_asRelativeTime()`

Signature: `c6b_asRelativeTime(mixed $value): string`

Convert a date/time value to a human-readable relative time string (e.g., "5 minutes ago", "9 hours from now").

```blade
{{ c6b_asRelativeTime($entry->postDate) }}
{{ c6b_asRelativeTime('2026-05-01') }}
```

### `c6b_getMatchedElement()`

Signature: `c6b_getMatchedElement(): ?ElementInterface`

Get the currently matched element, or null.

On multi-site setups, requires the `ResolveSite` middleware to be applied to the route, or call `Sites::setCurrentSite($site)` manually.

```php
$entry = c6b_getMatchedElement();
```

### `c6b_md()`

Signature: `c6b_md(string $text, ?string $flavor = null): HtmlString`

Parse Markdown to HTML.

```blade
{{ c6b_md($entry->summary) }}
{{ c6b_md($entry->content, 'gfm') }}
```

You may need to sanitize the output if the Markdown content is user-generated:

```blade
{{ c6b_sanitize(c6b_md($entry->content)) }}

{{ $text |> c6b_md(...) |> c6b_sanitize(...) }}
```

### `c6b_sanitize()`

Signature: `c6b_sanitize(HtmlString|string $html): HtmlString`

Sanitize HTML and return safe markup the Craft way.

```blade
{{ c6b_sanitize($entry->body) }}
```

### `c6b_single()`

Signature: `c6b_single(string $section): ?Entry`

Fetch the first entry in a section.

Single entries are not prefetched by Craft, so this helper provides a convenient way to retrieve them without needing to set up a full element query.

```blade
@if($home = c6b_single('home'))
    <h1>{{ $home->title }}</h1>
@endif
```

> You can also pass single entries into your views via view composers or controller logic.

### `c6b_t()`

Signature: `c6b_t(string $text, array $parameters = [], ?string $category = 'site', ?string $locale = null): string`

Translate a string, using Craft's translation system.

Uses `CraftCms\Cms\t` function under the hood, with `site` as the default category, which is better suited for frontend templates.

```blade
{{ c6b_t('Read more') }}
{{ c6b_t('Welcome, {name}', ['name' => $currentUser->friendlyName]) }}
{{ c6b_t('My message', [], 'site', 'de-DE') }}
```

> Craft falls back to Laravel's translation system if it does not find a matching translation.

### `c6b_tag()`

Signature: `c6b_tag(string $type, array|string $attributes = ''): HtmlString`

Create an HTML tag string.

```blade
{{ c6b_tag('h2', 'The Heading') }}
```

### `c6b_truncate()`

Signature: `c6b_truncate(string $string, int $length, string $suffix = '…', bool $splitSingleWord = true): string`

Trim text to a maximum length.

```blade
{{ c6b_truncate($entry->excerpt, 140) }}
{{ c6b_truncate($entry->excerpt, 140, '...', false) }}
```

> There is a corresponding `limit()` helper in Laravel, which has subtle differences in behavior, so we implemented our own `c6b_truncate()` helper to match Twig's `truncate` filter behavior more closely.


### `c6b_url()`

Signature: `c6b_url(string $path = '', array|string $params = [], ?string $scheme = null): string`

Generate a URL.

```blade
{{ c6b_url($entry->url, ['page' => $newEntries->currentPage() + 1]) }}
```

Matches Twig's `url()` function.

## Handling unported Twig functionality

We do not want to port every single, probably rarely used, Twig function and filter to Blade, you will have to implement some functionality yourself.

See Craft's implementation in `CraftCms\Cms\Twig\Extensions` for reference.

For convenience, we recommend to wrap complex php logic like namespaces etc. in a custom Blade component, so you can keep your main Blade views clean.

> Make sure to sanitize any untrusted input.

Example: Render an address (the Twig `address` filter):

```blade
<x-address :address="$currentUser->addresses->first()" />
```

The component:

```blade
@use(CraftCms\Cms\Address\Addresses)

@props([
    'address' => null,
])

@if ($address)
    {!! app(Addresses::class)->formatAddress($address) !!}
@endif
```

You can extend this approach to implement more project-specific functionality, like enforcing project standards and formatting, or wrapping complex logic in a Blade component.

Example: Render SVG icons (the Twig `svg` function):

```blade
@php($asset = Asset::find()->filename('theater.svg')->first())
<x-svg :svg="$asset" size="lg" class="fill-red-800" />
<x-svg svg="cog" size="lg" :sanitize="true" />
```

The component:

```blade
@php
    use CraftCms\Cms\Support\Html;
@endphp

@props([
    'svg' => null,
    'class' => null,
    'size' => null,
    'sanitize' => null,
])

@php
    $sizesLUT = [
        'xs' => 'size-4',
        'sm' => 'size-6',
        'md' => 'size-8',
        'lg' => 'size-10',
        'xl' => 'size-12',
    ];

    if (is_string($svg)) {
        $svg = resource_path("/icons/{$svg}.svg");
    }

    $svg = Html::svg($svg, sanitize: $sanitize);

    if ($class !== null) {
        $svg = Html::modifyTagAttributes($svg, [
            'class' => $class,
        ]);
    }

    if ($size !== null && isset($sizesLUT[$size])) {
        $svg = Html::modifyTagAttributes($svg, [
            'class' => $sizesLUT[$size],
        ]);
    }
@endphp

{!! $svg !!}

```

> You could also render Twig templates, using the `template()` helper, if you prefer to keep that logic in Twig.

## Experiment: AI generated helper functions

OK, we just said we don't want to port every single Craft-Twig function and filter to Blade, but if you insist:

The plugin ships with a ton of experimental helper functions, generated by AI based on analyzing Craft's Twig extensions for functions/filters.

Add a config file in `config/craft/_craft6blade.php` to enable them:

```php
<?php

return [
    'experiments' => [
        'loadAllHelpers' => true,
    ],
];
```

Untested, untouched, unreviewed, sometimes unnecessary. No human reasoning involved. We (or you for your project) may cherry-pick some of them.

Just the prompt `based on the pattern in Helpers.php c6b_tag: implement the HtmlHelpers.php file that exposes all functions in HtmlTwigExtensions as public functions for Blade, prefixed with c6b_. Repeat for all Twig extensions.`

See [AI-generated helpers](docs/ExperimentalHelpers.md) for the list of helpers.

For reference, see also the [TwigFunctions.md](docs/TwigFunctions.md) for a comparison of Twig's native functions/filters and their Blade/PHP equivalents.

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
        |> (fn($text) => c6b_md($text, 'gfm'))
        |> c6b_sanitize(...) !!}
</div>
```

> Craft's rendering of matrix fields via twig templates also works: {{ $entry->contentBuilder->render() }}.

## Using Blade functionality from Twig

Bringing some Blade functionality to Twig.

### Use Laravel's named routes in Twig

```twig
<a href="{{ c6b_route('demo.show') }}">Demo</a>
```

### Rendering Blade views from Twig

In Twig Templates, you can render Blade views via the `blade` function.

```twig
{{ blade('_partials/card.blade.php', { entry: entry }) }}
```

### Using Livewire components in Twig

Experimental.

```twig
{{ c6b_livewire('components::demos.search-twig') }}
{{ c6b_livewire('components::demos.search-twig', {query: 'article'}) }}
```

Some helper functions to load required assets, if not already bundled:

```twig
{{ c6b_vite() }}
{{ c6b_vite(['resources/css/app.css', 'resources/js/app.ts']) }}
{{ c6b_livewireStyles() }}
{{ c6b_livewireScripts() }}
{{ c6b_fluxAppearance() }}
{{ c6b_fluxScripts() }}
```

### Laravel Helpers in Twig

The plugin exposes Laravel helper classes:

```twig
{{ Str.of('This is my name').after('This is') }}
@dump(Arr::crossJoin([1, 2], ['a', 'b']))
{{ Number.forHumans(489939) }}
```

### Querying Eloquent models in Twig

```twig
{% set query = c6b_buildQuery('App\\Modules\\Main\\Models\\Subscription') %}
{% set subscriptions = query.latest().take(3).get() %}
```

## Config-driven Customization

Experimental

For convenience, you can define Blade customizations in the `config/craft/_craft6blade.php` config file.

```php
return [
    // Shared data for all views
    // Usage in Blade: {{ $copyright }} or {{ $t('Search') }}
    // Registering a function in the shared data array just for demonstration,
    // prefer declaring a 'real' function in a helper file and include it in the 'bladeFunctions' array below.
    'bladeShared' => [
        'copyright' => '© ' . date('Y'),
        't' => fn(string $text, array $params = []): string => CraftCms\Cms\t(
            $text,
            $params,
            'site',
        ),
    ],

    // Blade directives
    // run artisan view:clear after editing directives
    // Usage in Blade: @datetime($now)
    'bladeDirectives' => [
        'datetime' => function ($expression) {
            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
        },
    ],

    // Blade echo handlers (stringables)
    // Usage in Blade: {{ $entry->price }} // Craft Money field
    'bladeStringables' => [
        Money\Money::class => function ($money) {
            if ($money === null) {
                return null;
            }
            return CraftCms\Cms\Support\Money::toString($money);
        },
    ],

    // Blade conditionals
    // Usage in Blade: @itsFriday ... @else ... @enditsFriday
    'bladeIfs' => [
        'itsFriday' => function (): bool {
            return date('N') === '5';
        },
    ],

    // Blade View Composers
    // Usage in Blade (tests/extensions.blade.php): {{ $sayHello }}
    'bladeViewComposers' => [
        'tests.extensions' => function ($view) {
            $view->with('sayHello', 'Hello World!');
        },
    ],

    // Blade Class based Components
    // Usage in Blade: <x-recent-articles count="5" />
    'bladeComponents' => [
        'recent-articles' => App\Modules\Main\Views\RecentArticles::class,
    ],

    // Custom PHP files that contain functions to be registered as global Blade functions
    // It's recommended to use a prefix for your functions to avoid collisions with other packages.
    // In app/Helpers/Functions.php:
    // function my_double(int $number): int
    // {
    //   return $number * 2;
    //  }
    // Usage in Blade: {{ my_double(2) }}
    'bladeFunctions' => [app_path('Helpers/Functions.php')],
];
```

Note: while this seems convenient, you lose control over when exactly customizations are registered. So this may have a negative impact on performance, e.g. when unnecessary queries are executed.

You could also register your customizations in a service provider, or place custom functions in a dedicated helper file autoloaded by composer.

## Plugin Integration

Craft plugins that work with Twig should also work with Blade

* if they support Blade natively
* if they expose functionality via the Craft variable (e.g. `craft.thePlugin.doSomething` => `$craft->thePlugin->doSomething()` in Blade)
* if they expose functionality via plain PHP classes/services

Plugins that expose functionality via Twig extensions (functions, filters, tags) will not work out of the box. Some kind of adapter is required, e.g. a Blade directive or a helper function.

## Inertia

Not the focus of this plugin, but you may find some helper functions useful for passing data to Inertia views.

Craft's `HandleInertiaRequests` middleware is CP specific and hardcodes the Inertia root view, so we've added a simple custom middleware that allows you to use Inertia in the frontend, with a custom root view. Feel free to copy to your app and adjust to your needs.