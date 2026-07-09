# Publishing Helper Functions

The `c6b:publish` Artisan command lets you copy individual helper functions from the plugin's
`Helpers/Publishables.php` library into your application at `app/c6b/functions.php`.
Once published, a function lives in your own codebase and can be freely customised.

---

## Basic usage

```bash
php artisan c6b:publish {function}
```

Replace `{function}` with the exact name of the function you want to publish.
The `c6b_` prefix is optional — `c6b:publish t` and `c6b:publish c6b_t` are equivalent.

### Example

```bash
php artisan c6b:publish c6b_t
```

On the first publish the target file `app/c6b/functions.php` is created automatically
(including the standard `<?php` header). Subsequent publishes append each new function block
to the same file.

---

## Publishing without the `c6b_` prefix

Pass the `--noPrefix` flag to publish a function without its `c6b_` prefix. Both the
function name and its wrapping `// === START` / `// === END` marker comments are stripped
of the prefix in the target file.

```bash
php artisan c6b:publish c6b_t --noPrefix
```

This publishes the function as `t()` (wrapped in `// === START t` / `// === END t` markers)
instead of `c6b_t()`. Internal calls to other `c6b_` helpers inside the function body are
left untouched.

> **Note:** When removing a function that was published with `--noPrefix`, pass the flag
> again so the un-prefixed markers can be matched:
>
> ```bash
> php artisan c6b:publish c6b_t --remove --noPrefix
> ```

---

## Removing a published function

Pass the `--remove` flag to delete a previously published function from `app/c6b/functions.php`.

```bash
php artisan c6b:publish c6b_t --remove
```

---

## Available functions

All functions below can be published individually.

| Function | Description | Replacement (PHP/Blade/Craft API) |
|---|---|---|
| `c6b_actionInput` | Render a hidden action input | `Html::actionInput()` |
| `c6b_actionUrl` | Generate an action URL | `Url::actionUrl()` |
| `c6b_address` | Format an Address element | |
| `c6b_addresses` | Create an AddressQuery | `Address::find()` |
| `c6b_alias` | Resolve an alias | `Aliases::get()` |
| `c6b_append` | Append HTML inside a tag | |
| `c6b_asDateTime` | Format a value as a short date+time | |
| `c6b_asDate` | Format a value as a short date | |
| `c6b_asRelativeTime` | Format a value as a relative time string | |
| `c6b_ascii` | Convert a string to ASCII | `Str::ascii(...)` |
| `c6b_asset` | Generate a public asset URL (Laravel helper) | `{{ asset(...) }}` |
| `c6b_assets` | Create an AssetQuery | `Asset::find()` |
| `c6b_atom` | Format a date as an Atom timestamp | |
| `c6b_attr` | Render tag attributes as an HTML string | `Html::renderTagAttributes()` |
| `c6b_beginBody` | Render registered begin-body tags | `@craftBeginBody` |
| `c6b_camel` | Convert a string to camelCase | `Str::camel(...)` |
| `c6b_canCreateDrafts` | Check whether a user can create drafts for an element | |
| `c6b_canDeleteForSite` | Check whether a user can delete an element for a site | |
| `c6b_canDelete` | Check whether a user can delete an element | |
| `c6b_canDuplicate` | Check whether a user can duplicate an element | |
| `c6b_canSave` | Check whether a user can save an element | |
| `c6b_canView` | Check whether a user can view an element | |
| `c6b_clone` | Clone a value | `clone $var` |
| `c6b_collect` | Create a Collection (or ElementCollection) from a value | |
| `c6b_column` | Pluck a column of values from an array | `Arr::pluck(...)` |
| `c6b_combine` | Create an array by combining keys and values | `array_combine(...)` |
| `c6b_configure` | Configure an object with given attributes | `Typecast::configure()` |
| `c6b_contains` | Check whether an array contains a given value | `Arr::contains(...)` |
| `c6b_contentBlocks` | Create a ContentBlockQuery | `ContentBlock::find()` |
| `c6b_cpUrl` | Generate a control panel URL | `Url::cpUrl()` |
| `c6b_craftAsset` | Generate a Craft asset URL | `craftAsset()` |
| `c6b_create` | Instantiate an object by class name or config array | |
| `c6b_csrfInput` | Render a CSRF input | `@csrf` |
| `c6b_currency` | Format a currency value | |
| `c6b_dataUrl` | Generate a data URL for a file or asset | |
| `c6b_dateCreate` | Create a DateTimeInterface from a date value | |
| `c6b_date` | Format a date value | |
| `c6b_datetime` | Format a date+time value | |
| `c6b_dd` | Dump and die | `@dd(...)` |
| `c6b_diff` | Return values in the first array not present in subsequent arrays | `array_diff(...)` |
| `c6b_dump` | Dump variables for debugging (HTML output) | `@dump(...)` |
| `c6b_duration` | Format a duration as a human-readable string | `DateTimeHelper::humanDuration()` |
| `c6b_elements` | Create an ElementQuery | |
| `c6b_encenc` | Encrypt and encode a string | `Str::encenc(...)` |
| `c6b_encodeUrl` | Encode a URL | `Url::encodeUrl($url)` |
| `c6b_endBody` | Render registered end-body tags | `@craftEndBody` |
| `c6b_entries` | Create an EntryQuery | `Entry::find()` |
| `c6b_entryType` | Get an entry type by handle | |
| `c6b_explodeClass` | Explode a class attribute string into an array | `Html::explodeClass()` |
| `c6b_explodeStyle` | Explode a style attribute string into an array | `Html::explodeStyle()` |
| `c6b_expression` | Wrap a raw SQL expression | `new Expression()` |
| `c6b_failMessageInput` | Render a hidden fail-message input | `Html::failMessageInput()` |
| `c6b_fieldValueSql` | Get the SQL expression for a field value | |
| `c6b_filesize` | Format a file size | |
| `c6b_filter` | Filter an array, optionally with a callback | `array_filter(...)` |
| `c6b_firstWhere` | Return the first element matching a key/value pair | `collect(...)->firstWhere(...)` |
| `c6b_flatten` | Flatten a multi-dimensional array | `Arr::flatten(...)` |
| `c6b_getMatchedElement` | Get the element matched to the current request URI | |
| `c6b_getenv` | Get an environment variable value | `Env::get()` |
| `c6b_gql` | Execute a GraphQL query | |
| `c6b_group` | Group an array by a field name or callback | |
| `c6b_h1` … `c6b_h6` | Shorthand heading helpers | |
| `c6b_h` | Render a heading tag (h1–h6) | |
| `c6b_hash` | Hash or encrypt a string | `Crypt::encrypt(...)` / `hash(...)` |
| `c6b_head` | Render registered head tags | |
| `c6b_heading` | Alias for `c6b_h` | |
| `c6b_hiddenInput` | Render a hidden input | `Html::hiddenInput()` |
| `c6b_htmlId` | Normalize an element ID | `Html::id()` |
| `c6b_httpdate` | Format a date as an HTTP date (RFC 7231) | |
| `c6b_indexOf` | Return the index of the first occurrence of a value | |
| `c6b_input` | Render an input element | `Html::input()` |
| `c6b_intersect` | Return values present in all given arrays | `array_intersect(...)` |
| `c6b_jsonDecode` | JSON-decode a string | `Json::decode()` |
| `c6b_jsonEncode` | JSON-encode a value with safe defaults | `@json(...)` |
| `c6b_kebab` | Convert a string to kebab-case | |
| `c6b_lcfirst` | Lowercase the first character of a string | `mb_lcfirst(...)` |
| `c6b_literal` | Escape a query param value as a literal string | `Query::escapeParam()` |
| `c6b_map` | Apply a callback to every element of an array | `array_map(...)` |
| `c6b_md` | Parse Markdown to HTML | |
| `c6b_merge` | Merge two arrays together | |
| `c6b_modifyAttr` | Modify attributes on a tag | |
| `c6b_money` | Format a Money object as a string | |
| `c6b_multisort` | Sort an array by one or more keys | |
| `c6b_namespaceAttributes` | Namespace HTML attributes | `new HtmlString(Html::namespaceAttributes(...))` |
| `c6b_namespaceId` | Namespace an element ID | `app(InputNamespace::class)->namespaceId(...)` |
| `c6b_namespaceInputName` | Namespace an input name | `app(InputNamespace::class)->namespaceInputName(...)` |
| `c6b_namespaceInputs` | Namespace HTML inputs | `new HtmlString(app(InputNamespace::class)->namespaceInputs(...))` |
| `c6b_ns` | Alias for `c6b_namespaceInputs` | `c6b_namespaceInputs($html, $namespace)` |
| `c6b_number` | Format a number | |
| `c6b_ol` | Render an ordered list | `new HtmlString(Html::ol($items, $options))` |
| `c6b_parseAttr` | Parse a tag's attributes into an array | |
| `c6b_parseBooleanEnv` | Parse a boolean environment variable | `Env::parseBoolean($str)` |
| `c6b_parseEnv` | Parse an environment variable string | `Env::parse($str)` |
| `c6b_parseRefs` | Parse element references in a string | |
| `c6b_pascal` | Convert a string to PascalCase | `Str::pascal(...)` |
| `c6b_percentage` | Format a value as a percentage | |
| `c6b_plugin` | Get a plugin instance by handle | `app(Plugins::class)->getPlugin($handle)` |
| `c6b_prepend` | Prepend HTML inside a tag | |
| `c6b_push` | Append one or more values to an array | |
| `c6b_randomString` | Generate a random string | `Str::random($length)` |
| `c6b_raw` | Mark a string as raw/safe HTML | `new HtmlString(TemplateHelper::raw($value))` |
| `c6b_redirectInput` | Render a hidden redirect input | `new HtmlString(Html::redirectInput($url, $options))` |
| `c6b_reduce` | Iteratively reduce an array to a single value | |
| `c6b_removeClass` | Remove a CSS class from a tag | |
| `c6b_renderObjectTemplate` | Render an object template string | `renderObjectTemplate($template, $object)` |
| `c6b_replace` | Replace occurrences in a string (supports regex) | |
| `c6b_rss` | Format a date as an RSS timestamp | `c6b_dateConvert($date, $timezone)->format(DateTimeInterface::RSS)` |
| `c6b_sanitize` | Sanitize an HTML string | |
| `c6b_seq` | Get the next (or current) value of a named sequence | |
| `c6b_shuffle` | Shuffle an array and return the result | |
| `c6b_single` | Fetch a single-entry section | |
| `c6b_siteUrl` | Generate a site URL | `Url::siteUrl(...)` |
| `c6b_snake` | Convert a string to snake_case | `Str::snake(...)` |
| `c6b_sort` | Sort an array, optionally with a callback | |
| `c6b_successMessageInput` | Render a hidden success-message input | `new HtmlString(Html::successMessageInput(...))` |
| `c6b_svg` | Render an inline SVG | |
| `c6b_t` | Translate a string | |
| `c6b_tag` | Build an HTML tag | |
| `c6b_time` | Format a time value | |
| `c6b_timestamp` | Format a date as a relative timestamp | |
| `c6b_truncate` | Truncate a string | |
| `c6b_ul` | Render an unordered list | `new HtmlString(Html::ul($items, $options))` |
| `c6b_unique` | Remove duplicate values from an array | `array_unique($array, $flags)` |
| `c6b_unshift` | Prepend one or more values to an array | |
| `c6b_url` | Generate a URL | `{{ url(...) }}` |
| `c6b_users` | Create a UserQuery | `new UserQuery($config)` |
| `c6b_uuid7` | Generate a UUID v7 | `(string) Str::uuid7()` |
| `c6b_uuid` | Generate a UUID v4 | `(string) Str::uuid()` |
| `c6b_values` | Return the values of an array (re-indexed) | `array_values($array)` |
| `c6b_where` | Filter an array by a key/value condition | `Arr::where($array, $key, $value)` |
| `c6b_widont` | Replace the last space with a non-breaking space | `new HtmlString(Html::widont($string))` |
| `c6b_withoutKey` | Return an array without the specified key(s) | |
| `c6b_without` | Return an array without the specified value(s) | `Collection::make($arr)->reject(...)->all()` |

---

## How it works

The command searches `Helpers/Publishables.php` for a block delimited by:

```
// === START {function}
...function body...
// === END {function}
```

The entire block (including the marker comments) is appended verbatim to
`app/c6b/functions.php`.  Because every function uses fully-qualified class names
rather than `use` imports, the published code is self-contained and works without
any additional namespace setup in the target file.

---

## Workflow example

```bash
# Publish a translation helper and a URL helper
php artisan c6b:publish c6b_t
php artisan c6b:publish c6b_url

# Publish a helper without the c6b_ prefix (available as h1() instead of c6b_h1())
php artisan c6b:publish c6b_h1 --noPrefix

# Later, remove one you no longer need
php artisan c6b:publish c6b_url --remove
```

The resulting `app/c6b/functions.php` will contain only the functions you have
explicitly published, making it easy to keep the file lean and auditable.

