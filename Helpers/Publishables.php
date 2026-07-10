<?php


// === START c6b_actionInput
/**
 * Render a hidden action input.
 */
function c6b_actionInput(string $action, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::actionInput($action, $options));
}
// === END c6b_actionInput

// === START c6b_actionUrl
/**
 * Generate an action URL.
 */
function c6b_actionUrl(string $path = '', array|string $params = [], ?string $scheme = null): string
{
    return \CraftCms\Cms\Support\Url::actionUrl($path, $params, $scheme);
}
// === END c6b_actionUrl

// === START c6b_address
/**
 * Format an address element.
 */
function c6b_address(?\CraftCms\Cms\Address\Elements\Address $address, array $options = [], ?\CommerceGuys\Addressing\Formatter\FormatterInterface $formatter = null): string
{
    if ($address === null) {
        return '';
    }
    return app(\CraftCms\Cms\Address\Addresses::class)->formatAddress($address, $options, $formatter);
}
// === END c6b_address

// === START c6b_addresses
/**
 * Create an address element query.
 */
function c6b_addresses(array $config = []): \CraftCms\Cms\Element\Queries\AddressQuery
{
    return new \CraftCms\Cms\Element\Queries\AddressQuery($config);
}
// === END c6b_addresses

// === START c6b_alias
/**
 * Resolve an alias.
 */
function c6b_alias(string $alias): string
{
    return \CraftCms\Aliases\Aliases::get($alias);
}
// === END c6b_alias

// === START c6b_append
/**
 * Append HTML inside a tag.
 */
function c6b_append(string $tag, string $html, ?string $ifExists = null): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->appendFilter($tag, $html, $ifExists));
}
// === END c6b_append

// === START c6b_ascii
/**
 * Convert a string to ASCII.
 */
function c6b_ascii(string $value, string $language = 'en'): string
{
    return \CraftCms\Cms\Support\Str::ascii($value, $language);
}
// === END c6b_ascii

// === START c6b_asDate
/**
 * Format a date.
 */
function c6b_asDate($date, $format = 'short'): string
{
    return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asDate($date, $format);
}
// === END c6b_asDate

// === START c6b_asDateTime
/**
 * Format a date and time.
 */
function c6b_asDateTime($date, $format = 'short'): string
{
    return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asDateTime($date, $format);
}
// === END c6b_asDateTime

// === START c6b_asRelativeTime
/**
 * Format a date as a relative time string.
 */
function c6b_asRelativeTime(mixed $value): string
{
    return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asRelativeTime($value);
}
// === END c6b_asRelativeTime

// === START c6b_asset
/**
 * Generate a public asset URL (Laravel helper).
 */
function c6b_asset(string $path, mixed $secure = null): string
{
    return asset($path, $secure);
}
// === END c6b_asset

// === START c6b_assets
/**
 * Create an asset element query.
 */
function c6b_assets(array $config = []): \CraftCms\Cms\Element\Queries\AssetQuery
{
    return new \CraftCms\Cms\Element\Queries\AssetQuery($config);
}
// === END c6b_assets

// === START c6b_atom
/**
 * Format a date as an Atom timestamp.
 */
function c6b_atom(mixed $date, mixed $timezone = null): string
{
    return c6b_dateConvert($date, $timezone)->format(DateTimeInterface::ATOM);
}
// === END c6b_atom

// === START c6b_attr
/**
 * Render tag attributes as an HTML string.
 */
function c6b_attr(array $attributes): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::renderTagAttributes($attributes));
}
// === END c6b_attr

// === START c6b_beginBody
/**
 * Render registered begin-body tags.
 */
function c6b_beginBody(): void
{
    app(\CraftCms\Cms\View\PageLifecycle::class)->beginBody();
}
// === END c6b_beginBody

// === START c6b_camel
/**
 * Convert a string to camelCase.
 */
function c6b_camel(mixed $string): string
{
    return \CraftCms\Cms\Support\Str::camel((string) $string);
}
// === END c6b_camel

// === START c6b_canCreateDrafts
/**
 * Check whether a user can create drafts for an element.
 */
function c6b_canCreateDrafts(\CraftCms\Cms\Element\Contracts\ElementInterface $element, ?\CraftCms\Cms\User\Elements\User $user = null): ?bool
{
    return ($user ?? \CraftCms\Cms\currentUser())?->can('createDrafts', $element);
}
// === END c6b_canCreateDrafts

// === START c6b_canDelete
/**
 * Check whether a user can delete an element.
 */
function c6b_canDelete(\CraftCms\Cms\Element\Contracts\ElementInterface $element, ?\CraftCms\Cms\User\Elements\User $user = null): ?bool
{
    return ($user ?? \CraftCms\Cms\currentUser())?->can('delete', $element);
}
// === END c6b_canDelete

// === START c6b_canDeleteForSite
/**
 * Check whether a user can delete an element for a site.
 */
function c6b_canDeleteForSite(\CraftCms\Cms\Element\Contracts\ElementInterface $element, ?\CraftCms\Cms\User\Elements\User $user = null): ?bool
{
    return ($user ?? \CraftCms\Cms\currentUser())?->can('deleteForSite', $element);
}
// === END c6b_canDeleteForSite

// === START c6b_canDuplicate
/**
 * Check whether a user can duplicate an element.
 */
function c6b_canDuplicate(\CraftCms\Cms\Element\Contracts\ElementInterface $element, ?\CraftCms\Cms\User\Elements\User $user = null): ?bool
{
    return ($user ?? \CraftCms\Cms\currentUser())?->can('duplicate', $element);
}
// === END c6b_canDuplicate

// === START c6b_canSave
/**
 * Check whether a user can save an element.
 */
function c6b_canSave(\CraftCms\Cms\Element\Contracts\ElementInterface $element, ?\CraftCms\Cms\User\Elements\User $user = null): ?bool
{
    return ($user ?? \CraftCms\Cms\currentUser())?->can('save', $element);
}
// === END c6b_canSave

// === START c6b_canView
/**
 * Check whether a user can view an element.
 */
function c6b_canView(\CraftCms\Cms\Element\Contracts\ElementInterface $element, ?\CraftCms\Cms\User\Elements\User $user = null): ?bool
{
    return ($user ?? \CraftCms\Cms\currentUser())?->can('view', $element);
}
// === END c6b_canView

// === START c6b_clone
/**
 * Clone a value.
 */
function c6b_clone(mixed $var): mixed
{
    return clone $var;
}
// === END c6b_clone

// === START c6b_collect
/**
 * Create a Collection (or ElementCollection) from a value.
 */
function c6b_collect(mixed $var): \Illuminate\Support\Collection
{
    $collection = \Illuminate\Support\Collection::make($var);

    if ($collection->isNotEmpty() && $collection->doesntContain(fn ($item) => ! $item instanceof \CraftCms\Cms\Element\Contracts\ElementInterface)) {
        return \CraftCms\Cms\Element\ElementCollection::make($collection);
    }

    return $collection;
}
// === END c6b_collect

// === START c6b_column
/**
 * Pluck a column of values from an array.
 */
function c6b_column(iterable $array, string|int|null $key, string|int|null $indexBy = null): array
{
    return \CraftCms\Cms\Support\Arr::pluck($array, $key, $indexBy);
}
// === END c6b_column

// === START c6b_combine
/**
 * Create an array by combining keys and values.
 */
function c6b_combine(array $keys, array $values): array
{
    return array_combine($keys, $values);
}
// === END c6b_combine

// === START c6b_configure
/**
 * Configure an object with the given attributes.
 */
function c6b_configure(object $object, array $attributes): object
{
    return \CraftCms\Cms\Support\Typecast::configure($object, $attributes);
}
// === END c6b_configure

// === START c6b_contains
/**
 * Check whether an array contains a given value.
 */
function c6b_contains(iterable $array, mixed $value): bool
{
    return \CraftCms\Cms\Support\Arr::contains($array, $value);
}
// === END c6b_contains

// === START c6b_contentBlocks
/**
 * Create a content block element query.
 */
function c6b_contentBlocks(array $config = []): \CraftCms\Cms\Element\Queries\ContentBlockQuery
{
    return new \CraftCms\Cms\Element\Queries\ContentBlockQuery($config);
}
// === END c6b_contentBlocks

// === START c6b_cpUrl
/**
 * Generate a control panel URL.
 */
function c6b_cpUrl(string $path = '', array|string $params = [], ?string $scheme = null): string
{
    return \CraftCms\Cms\Support\Url::cpUrl($path, $params, $scheme);
}
// === END c6b_cpUrl

// === START c6b_craftAsset
/**
 * Generate a Craft asset URL.
 */
function c6b_craftAsset(string $path): string
{
    return \CraftCms\Cms\craftAsset($path);
}
// === END c6b_craftAsset

// === START c6b_create
/**
 * Instantiate an object by class name or config array.
 */
function c6b_create(string|array $type, array $params = []): object
{
    return c6b_bladeCoreExtension()->createFunction($type, $params);
}
// === END c6b_create

// === START c6b_csrfInput
/**
 * Render a CSRF input.
 */
function c6b_csrfInput(array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::csrfInput($options));
}
// === END c6b_csrfInput

// === START c6b_currency
/**
 * Format a currency value.
 */
function c6b_currency(mixed $value, ?string $currency = null, array $options = [], array $textOptions = [], bool $stripZeros = false): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asCurrency($value, $currency, $stripZeros);
    } catch (Throwable) {
        return $value;
    }
}
// === END c6b_currency

// === START c6b_dataUrl
/**
 * Generate a data URL for a file or asset.
 */
function c6b_dataUrl(\CraftCms\Cms\Asset\Elements\Asset|string $file, ?string $mimeType = null): string
{
    return c6b_bladeHtmlExtension()->dataUrlFunction($file, $mimeType);
}
// === END c6b_dataUrl

// === START c6b_date
/**
 * Format a date value using the I18N formatter.
 */
function c6b_date(mixed $date, ?string $format = null, mixed $timezone = null, ?string $locale = null): string
{
    if ($date instanceof DateInterval) {
        return $date->format($format ?? '%R%y years, %m months, %d days');
    }

    $format = c6b_normalizeDateFormat($format);
    $carbon = c6b_dateConvert($date, $timezone);
    $formatter = $locale ? \CraftCms\Cms\Support\Facades\I18N::getLocaleById($locale)->getFormatter() : \CraftCms\Cms\Support\Facades\I18N::getFormatter();
    $originalTimeZone = $formatter->timeZone;
    $formatter->timeZone = $timezone !== null ? $carbon->getTimezone()->getName() : $formatter->timeZone;
    $result = $formatter->asDate(\Illuminate\Support\Facades\Date::instance($carbon), $format);
    $formatter->timeZone = $originalTimeZone;

    return $result;
}
// === END c6b_date

// === START c6b_dateConvert
/**
 * Convert a date value to a Carbon instance, optionally applying a timezone.
 *
 * @internal
 */
function c6b_dateConvert(mixed $date, mixed $timezone = null): \Illuminate\Support\Carbon
{
    if ($date === null) {
        $carbon = now();
    } elseif ($date instanceof \Illuminate\Support\Carbon) {
        $carbon = $date;
    } else {
        $carbon = \Illuminate\Support\Facades\Date::parse($date);
    }

    if ($timezone !== null) {
        $carbon = $carbon->copy()->setTimezone($timezone);
    }

    return $carbon;
}
// === END c6b_dateConvert

// === START c6b_dateCreate
/**
 * Create a DateTimeInterface from a date value.
 */
function c6b_dateCreate(mixed $date = null, mixed $timezone = null): DateTimeInterface
{
    if (is_array($date)) {
        $date = \CraftCms\Cms\Support\DateTimeHelper::toDateTime($date, false, false);
        if ($date === false) {
            throw new InvalidArgumentException('Invalid date passed to c6b_dateCreate()');
        }
    }

    return c6b_dateConvert($date, $timezone);
}
// === END c6b_dateCreate

// === START c6b_datetime
/**
 * Format a date+time value using the I18N formatter.
 */
function c6b_datetime(
    mixed $date,
    ?string $format = null,
    mixed $timezone = null,
    ?string $locale = null,
    bool $withTimeZone = false,
): string {
    $format = c6b_normalizeDateFormat($format);
    $carbon = c6b_dateConvert($date, $timezone);
    $formatter = $locale ? \CraftCms\Cms\Support\Facades\I18N::getLocaleById($locale)->getFormatter() : \CraftCms\Cms\Support\Facades\I18N::getFormatter();
    $originalTimeZone = $formatter->timeZone;
    $formatter->timeZone = $timezone !== null ? $carbon->getTimezone()->getName() : $formatter->timeZone;
    $result = $formatter->asDatetime(\Illuminate\Support\Facades\Date::instance($carbon), $format, withTimeZone: $withTimeZone);
    $formatter->timeZone = $originalTimeZone;

    return $result;
}
// === END c6b_datetime

// === START c6b_dd
/**
 * Dump and die.
 */
function c6b_dd(mixed ...$vars): never
{
    dd(...$vars);
}
// === END c6b_dd

// === START c6b_default
/**
 * Returns the value if not empty, otherwise the default.
 */
function c6b_default(mixed $value, mixed $default = ''): mixed
{
    return c6b_bladeCoreExtension()->defaultFilter($value, $default);
}
// === END c6b_default

// === START c6b_diff
/**
 * Return values in the first array not present in any subsequent arrays.
 */
function c6b_diff(array $array, array ...$arrays): array
{
    return array_diff($array, ...$arrays);
}
// === END c6b_diff

// === START c6b_dump
/**
 * Dump variables for debugging (HTML output).
 */
function c6b_dump(mixed ...$vars): \Illuminate\Support\HtmlString
{
    $output = '';
    foreach ($vars as $var) {
        ob_start();
        dump($var);
        $output .= str_replace('<code>', '<code style="display:block;">', ob_get_clean());
    }
    return new \Illuminate\Support\HtmlString($output);
}
// === END c6b_dump

// === START c6b_duration
/**
 * Format a duration value as a human-readable string.
 */
function c6b_duration(mixed $value): string
{
    return \CraftCms\Cms\Support\DateTimeHelper::humanDuration($value);
}
// === END c6b_duration

// === START c6b_elements
/**
 * Create an element query.
 */
function c6b_elements(string $elementType = \CraftCms\Cms\Element\Element::class, array $config = []): \CraftCms\Cms\Element\Queries\ElementQuery
{
    return new \CraftCms\Cms\Element\Queries\ElementQuery($elementType, $config);
}
// === END c6b_elements

// === START c6b_encenc
/**
 * Encrypt and encode a string (enc-enc).
 */
function c6b_encenc(mixed $str): string
{
    return \CraftCms\Cms\Support\Str::encenc((string) $str);
}
// === END c6b_encenc

// === START c6b_encodeUrl
/**
 * Encode a URL.
 */
function c6b_encodeUrl(string $url): string
{
    return \CraftCms\Cms\Support\Url::encodeUrl($url);
}
// === END c6b_encodeUrl

// === START c6b_endBody
/**
 * Render registered end-body tags.
 */
function c6b_endBody(): void
{
    app(\CraftCms\Cms\View\PageLifecycle::class)->endBody();
}
// === END c6b_endBody

// === START c6b_entries
/**
 * Create an entry element query.
 */
function c6b_entries(array $config = []): \CraftCms\Cms\Element\Queries\EntryQuery
{
    return new \CraftCms\Cms\Element\Queries\EntryQuery($config);
}
// === END c6b_entries

// === START c6b_entryType
/**
 * Get an entry type by handle.
 */
function c6b_entryType(string $handle): \CraftCms\Cms\Entry\Data\EntryType
{
    return c6b_bladeCoreExtension()->entryTypeFunction($handle);
}
// === END c6b_entryType

// === START c6b_expression
/**
 * Wrap a raw SQL expression.
 */
function c6b_expression(mixed $expression): \Illuminate\Database\Query\Expression
{
    return new \Illuminate\Database\Query\Expression($expression);
}
// === END c6b_expression

// === START c6b_explodeClass
/**
 * Explode a class attribute string into an array.
 */
function c6b_explodeClass(array|string $class): array
{
    return \CraftCms\Cms\Support\Html::explodeClass($class);
}
// === END c6b_explodeClass

// === START c6b_explodeStyle
/**
 * Explode a style attribute string into an array.
 */
function c6b_explodeStyle(array|string $style): array
{
    return \CraftCms\Cms\Support\Html::explodeStyle($style);
}
// === END c6b_explodeStyle

// === START c6b_failMessageInput
/**
 * Render a hidden fail-message input.
 */
function c6b_failMessageInput(string $message, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::failMessageInput($message, $options));
}
// === END c6b_failMessageInput

// === START c6b_fieldValueSql
/**
 * Get the SQL expression for a field value.
 */
function c6b_fieldValueSql(\CraftCms\Cms\FieldLayout\Contracts\FieldLayoutProviderInterface $provider, string $fieldHandle, ?string $key = null): ?string
{
    return c6b_bladeCoreExtension()->fieldValueSqlFunction($provider, $fieldHandle, $key);
}
// === END c6b_fieldValueSql

// === START c6b_filesize
/**
 * Format a filesize value.
 */
function c6b_filesize(mixed $value, ?int $decimals = null): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asShortSize($value, $decimals);
    } catch (Throwable) {
        return $value;
    }
}
// === END c6b_filesize

// === START c6b_filter
/**
 * Filter an array, optionally using a callback.
 */
function c6b_filter(iterable $arr, ?callable $arrow = null): array
{
    if ($arr instanceof Traversable) {
        $arr = iterator_to_array($arr);
    }

    if ($arrow === null) {
        return array_filter($arr);
    }

    return array_filter($arr, $arrow);
}
// === END c6b_filter

// === START c6b_firstWhere
/**
 * Return the first element matching the given key/value pair.
 */
function c6b_firstWhere(iterable $array, callable|string $key, mixed $value = true, bool $strict = false): mixed
{
    return collect($array)->firstWhere($key, $strict ? '===' : '==', $value);
}
// === END c6b_firstWhere

// === START c6b_flatten
/**
 * Flatten a multi-dimensional array.
 */
function c6b_flatten(iterable $array, float|int $depth = INF): array
{
    return \CraftCms\Cms\Support\Arr::flatten($array, $depth);
}
// === END c6b_flatten

// === START c6b_getenv
/**
 * Get an environment variable value.
 */
function c6b_getenv(string $name, mixed $default = null): mixed
{
    return \CraftCms\Cms\Support\Env::get($name, $default);
}
// === END c6b_getenv

// === START c6b_getMatchedElement
/**
 * Get the element matched to the current request URI.
 */
function c6b_getMatchedElement(): ?\CraftCms\Cms\Element\Contracts\ElementInterface
{
    return  Elements::getElementByUri(
        \CraftCms\Cms\Support\Facades\Sites::getRequestPath(request()),
        \CraftCms\Cms\Support\Facades\Sites::getCurrentSite()->id,
        true,
    );
}
// === END c6b_getMatchedElement

// === START c6b_gql
/**
 * Execute a GraphQL query.
 */
function c6b_gql(string $query, ?array $variables = null, ?string $operationName = null): array
{
    return c6b_bladeCoreExtension()->gqlFunction($query, $variables, $operationName);
}
// === END c6b_gql

// === START c6b_group
/**
 * Group an array by a field name or callback.
 */
function c6b_group(iterable $arr, callable|string $arrow): array
{
    $groups = [];

    if (is_string($arrow)) {
        $template = '{'.$arrow.'}';
        foreach ($arr as $item) {
            $groupKey = \CraftCms\Cms\renderObjectTemplate($template, $item);
            $groups[$groupKey][] = $item;
        }
    } else {
        foreach ($arr as $key => $item) {
            $groupKey = (string) $arrow($item, $key);
            $groups[$groupKey][] = $item;
        }
    }

    return $groups;
}
// === END c6b_group

// === START c6b_h
/**
 * Render a heading tag (h1–h6).
 */
function c6b_h(int $level, array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->headingFunction($level, $attributes));
}
// === END c6b_h

// === START c6b_h1
/**
 * Render an h1 heading tag.
 */
function c6b_h1(array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h(1, $attributes);
}
// === END c6b_h1

// === START c6b_h2
/**
 * Render an h2 heading tag.
 */
function c6b_h2(array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h(2, $attributes);
}
// === END c6b_h2

// === START c6b_h3
/**
 * Render an h3 heading tag.
 */
function c6b_h3(array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h(3, $attributes);
}
// === END c6b_h3

// === START c6b_h4
/**
 * Render an h4 heading tag.
 */
function c6b_h4(array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h(4, $attributes);
}
// === END c6b_h4

// === START c6b_h5
/**
 * Render an h5 heading tag.
 */
function c6b_h5(array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h(5, $attributes);
}
// === END c6b_h5

// === START c6b_h6
/**
 * Render an h6 heading tag.
 */
function c6b_h6(array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h(6, $attributes);
}
// === END c6b_h6

// === START c6b_hash
/**
 * Hash or encrypt a string.
 */
function c6b_hash(string $data, ?string $algo = null): string
{
    if ($algo === null) {
        return \Illuminate\Support\Facades\Crypt::encrypt($data);
    }
    return hash($algo, $data);
}
// === END c6b_hash

// === START c6b_head
/**
 * Render registered head tags.
 */
function c6b_head(): void
{
    app(\CraftCms\Cms\View\PageLifecycle::class)->head();
}
// === END c6b_head

// === START c6b_heading
/**
 * Alias for c6b_h().
 */
function c6b_heading(int $level, array|string $attributes = ''): \Illuminate\Support\HtmlString
{
    return c6b_h($level, $attributes);
}
// === END c6b_heading

// === START c6b_hiddenInput
/**
 * Render a hidden input.
 */
function c6b_hiddenInput(string $name, ?string $value = null, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::hiddenInput($name, $value, $options));
}
// === END c6b_hiddenInput

// === START c6b_htmlId
/**
 * Normalize an element ID.
 */
function c6b_htmlId(string $id, bool $checkUniqueness = true): string
{
    return \CraftCms\Cms\Support\Html::id($id, $checkUniqueness);
}
// === END c6b_htmlId

// === START c6b_httpdate
/**
 * Format a date as an HTTP date (RFC 7231).
 */
function c6b_httpdate(mixed $date, mixed $timezone = null): string
{
    return c6b_dateConvert($date, $timezone)->format(DateTimeInterface::RFC7231);
}
// === END c6b_httpdate

// === START c6b_indexOf
/**
 * Return the index of the first occurrence of a value in a string or array.
 */
function c6b_indexOf(mixed $haystack, mixed $needle, ?int $default = -1): ?int
{
    return c6b_bladeArrayExtension()->indexOfFilter($haystack, $needle, $default);
}
// === END c6b_indexOf

// === START c6b_input
/**
 * Render an input element.
 */
function c6b_input(string $type, ?string $name = null, mixed $value = null, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::input($type, $name, $value, $options));
}
// === END c6b_input

// === START c6b_intersect
/**
 * Return values present in all given arrays.
 */
function c6b_intersect(array $array, array ...$arrays): array
{
    return array_intersect($array, ...$arrays);
}
// === END c6b_intersect

// === START c6b_jsonDecode
/**
 * JSON-decode a string.
 */
function c6b_jsonDecode(string $value, bool $asArray = true): mixed
{
    return \CraftCms\Cms\Support\Json::decode($value, $asArray);
}
// === END c6b_jsonDecode

// === START c6b_jsonEncode
/**
 * JSON-encode a value with safe defaults.
 */
function c6b_jsonEncode(mixed $value, ?int $options = null, int $depth = 512): string|false
{
    return c6b_bladeCoreExtension()->jsonEncodeFilter($value, $options, $depth);
}
// === END c6b_jsonEncode

// === START c6b_kebab
/**
 * Convert a string to kebab-case.
 */
function c6b_kebab(mixed $string, string $glue = '-', bool $lower = true, bool $removePunctuation = true): string
{
    return c6b_bladeTextExtension()->kebabFilter($string, $glue, $lower, $removePunctuation);
}
// === END c6b_kebab

// === START c6b_lcfirst
/**
 * Lowercase the first character of a string.
 */
function c6b_lcfirst(mixed $string): string
{
    return mb_lcfirst((string) $string);
}
// === END c6b_lcfirst

// === START c6b_literal
/**
 * Escape a query param value so it is treated as a literal string.
 */
function c6b_literal(mixed $value): string
{
    return \CraftCms\Cms\Support\Query::escapeParam((string) $value);
}
// === END c6b_literal

// === START c6b_map
/**
 * Apply a callback to every element of an array.
 */
function c6b_map(iterable $array, callable $arrow): array
{
    if ($array instanceof Traversable) {
        $array = iterator_to_array($array);
    }

    return array_map($arrow, $array);
}
// === END c6b_map

// === START c6b_md
/**
 * Parse Markdown into HTML.
 */
function c6b_md(string $text, ?string $flavor = null): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Facades\Markdown::parse($text, $flavor));
}
// === END c6b_md

// === START c6b_merge
/**
 * Merge two arrays together.
 */
function c6b_merge(iterable $arr1, iterable $arr2, bool $recursive = false): array
{
    return c6b_bladeArrayExtension()->mergeFilter($arr1, $arr2, $recursive);
}
// === END c6b_merge

// === START c6b_modifyAttr
/**
 * Modify attributes on a tag.
 */
function c6b_modifyAttr(string $tag, array $attributes): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->attrFilter($tag, $attributes));
}
// === END c6b_modifyAttr

// === START c6b_money
/**
 * Format a Money object as a string.
 */
function c6b_money(?\Money\Money $money, ?string $locale = null): ?string
{
    if ($money === null) {
        return null;
    }
    return \CraftCms\Cms\Support\Money::toString($money, $locale);
}
// === END c6b_money

// === START c6b_multisort
/**
 * Sort an array by one or more keys.
 */
function c6b_multisort(mixed $array, mixed $key, int|array $direction = SORT_ASC, int|array $sortFlag = SORT_REGULAR): array
{
    return c6b_bladeArrayExtension()->multisortFilter($array, $key, $direction, $sortFlag);
}
// === END c6b_multisort

// === START c6b_namespaceAttributes
/**
 * Namespace HTML attributes.
 */
function c6b_namespaceAttributes(string $html, string $namespace, bool $withClasses = false): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::namespaceAttributes($html, $namespace, $withClasses));
}
// === END c6b_namespaceAttributes

// === START c6b_namespaceId
/**
 * Namespace an element ID.
 */
function c6b_namespaceId(string $id, ?string $namespace = null): string
{
    return app(\CraftCms\Cms\View\InputNamespace::class)->namespaceId($id, $namespace);
}
// === END c6b_namespaceId

// === START c6b_namespaceInputName
/**
 * Namespace an input name.
 */
function c6b_namespaceInputName(string $name, ?string $namespace = null): string
{
    return app(\CraftCms\Cms\View\InputNamespace::class)->namespaceInputName($name, $namespace);
}
// === END c6b_namespaceInputName

// === START c6b_namespaceInputs
/**
 * Namespace HTML inputs.
 */
function c6b_namespaceInputs(string $html, ?string $namespace = null): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(app(\CraftCms\Cms\View\InputNamespace::class)->namespaceInputs($html, $namespace));
}
// === END c6b_namespaceInputs

// === START c6b_normalizeDateFormat
/**
 * Normalize a date format string (icu:/php: prefix handling).
 *
 * @internal
 */
function c6b_normalizeDateFormat(?string $format): ?string
{
    if ($format === null || in_array($format, [\CraftCms\Cms\Translation\Locale::LENGTH_SHORT, \CraftCms\Cms\Translation\Locale::LENGTH_MEDIUM, \CraftCms\Cms\Translation\Locale::LENGTH_LONG, \CraftCms\Cms\Translation\Locale::LENGTH_FULL], true)) {
        return $format;
    }

    if (str_starts_with($format, 'icu:')) {
        return substr($format, 4);
    }

    return \CraftCms\Cms\Support\Str::start($format, 'php:');
}
// === END c6b_normalizeDateFormat

// === START c6b_ns
/**
 * Namespace HTML inputs (alias for c6b_namespaceInputs).
 */
function c6b_ns(string $html, ?string $namespace = null): \Illuminate\Support\HtmlString
{
    return c6b_namespaceInputs($html, $namespace);
}
// === END c6b_ns

// === START c6b_number
/**
 * Format a number.
 */
function c6b_number(mixed $value, ?int $decimals = null, array $options = [], array $textOptions = [], ?string $locale = null): string
{
    if ($value === null || $value === '') {
        return '';
    }
    $formatter = $locale
        ? \CraftCms\Cms\Support\Facades\I18N::getLocaleById($locale)->getFormatter()
        : \CraftCms\Cms\Support\Facades\I18N::getFormatter();
    try {
        if (! $formatter->willBeMisrepresented($value)) {
            return $formatter->asDecimal($value, $decimals, $options);
        }
    } catch (Throwable) {
    }
    return $value;
}
// === END c6b_number

// === START c6b_ol
/**
 * Render an ordered list.
 */
function c6b_ol(array $items, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::ol($items, $options));
}
// === END c6b_ol

// === START c6b_parseAttr
/**
 * Parse a tag's attributes into an array.
 */
function c6b_parseAttr(string $tag): array
{
    return c6b_bladeHtmlExtension()->parseAttrFilter($tag);
}
// === END c6b_parseAttr

// === START c6b_parseBooleanEnv
/**
 * Parse a boolean environment variable.
 */
function c6b_parseBooleanEnv(string $str): ?bool
{
    return \CraftCms\Cms\Support\Env::parseBoolean($str);
}
// === END c6b_parseBooleanEnv

// === START c6b_parseEnv
/**
 * Parse an environment variable string.
 */
function c6b_parseEnv(string $str): ?string
{
    return \CraftCms\Cms\Support\Env::parse($str);
}
// === END c6b_parseEnv

// === START c6b_parseRefs
/**
 * Parse element references in a string.
 */
function c6b_parseRefs(mixed $str, ?int $siteId = null): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->parseRefsFilter($str, $siteId));
}
// === END c6b_parseRefs

// === START c6b_pascal
/**
 * Convert a string to PascalCase.
 */
function c6b_pascal(mixed $string): string
{
    return \CraftCms\Cms\Support\Str::pascal((string) $string);
}
// === END c6b_pascal

// === START c6b_percentage
/**
 * Format a value as a percentage.
 */
function c6b_percentage(mixed $value, ?int $decimals = null): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asPercent($value, $decimals);
    } catch (Throwable) {
        return $value;
    }
}
// === END c6b_percentage

// === START c6b_plugin
/**
 * Get a plugin instance by handle.
 */
function c6b_plugin(string $handle): ?\CraftCms\Cms\Plugin\Contracts\PluginInterface
{
    return app(\CraftCms\Cms\Plugin\Plugins::class)->getPlugin($handle);
}
// === END c6b_plugin

// === START c6b_prepend
/**
 * Prepend HTML inside a tag.
 */
function c6b_prepend(string $tag, string $html, ?string $ifExists = null): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->prependFilter($tag, $html, $ifExists));
}
// === END c6b_prepend

// === START c6b_push
/**
 * Append one or more values to an array.
 */
function c6b_push(array $array, mixed ...$values): array
{
    array_push($array, ...$values);
    return $array;
}
// === END c6b_push

// === START c6b_randomString
/**
 * Generate a random string.
 */
function c6b_randomString(int $length = 36): string
{
    return \CraftCms\Cms\Support\Str::random($length);
}
// === END c6b_randomString

// === START c6b_raw
/**
 * Mark a string as raw/safe HTML.
 */
function c6b_raw(mixed $value): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Template::raw($value));
}
// === END c6b_raw

// === START c6b_redirectInput
/**
 * Render a hidden redirect input.
 */
function c6b_redirectInput(string $url, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::redirectInput($url, $options));
}
// === END c6b_redirectInput

// === START c6b_reduce
/**
 * Iteratively reduce an array to a single value using a callback.
 */
function c6b_reduce(iterable $array, callable $arrow, mixed $initial = null): mixed
{
    if ($array instanceof Traversable) {
        $array = iterator_to_array($array);
    }

    return array_reduce($array, $arrow, $initial);
}
// === END c6b_reduce

// === START c6b_removeClass
/**
 * Remove a CSS class from a tag.
 */
function c6b_removeClass(string $tag, array|string $class): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->removeClassFilter($tag, $class));
}
// === END c6b_removeClass

// === START c6b_renderObjectTemplate
/**
 * Render an object template string.
 */
function c6b_renderObjectTemplate(string $template, mixed $object): string
{
    return \CraftCms\Cms\renderObjectTemplate($template, $object);
}
// === END c6b_renderObjectTemplate

// === START c6b_replace
/**
 * Replace occurrences in a string, supporting regex patterns.
 */
function c6b_replace(mixed $str, mixed $search, mixed $replace = null, ?bool $regex = null): mixed
{
    return c6b_bladeTextExtension()->replaceFilter($str, $search, $replace, $regex);
}
// === END c6b_replace

// === START c6b_rss
/**
 * Format a date as an RSS timestamp.
 */
function c6b_rss(mixed $date, mixed $timezone = null): string
{
    return c6b_dateConvert($date, $timezone)->format(DateTimeInterface::RSS);
}
// === END c6b_rss

// === START c6b_sanitize
/**
 * Sanitize an HTML string.
 */
function c6b_sanitize(\Illuminate\Support\HtmlString|string $html): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Facades\HtmlSanitizers::sanitize((string)$html));
}
// === END c6b_sanitize

// === START c6b_seq
/**
 * Get the next (or current) value of a named sequence.
 */
function c6b_seq(string $name, ?int $length = null, bool $next = true): int|string
{
    if ($next) {
        return \CraftCms\Cms\Support\Sequence::next($name, $length);
    }
    return \CraftCms\Cms\Support\Sequence::current($name, $length);
}
// === END c6b_seq

// === START c6b_shuffle
/**
 * Shuffle an array and return the result.
 */
function c6b_shuffle(iterable $arr): array
{
    return c6b_bladeArrayExtension()->shuffleFunction($arr);
}
// === END c6b_shuffle

// === START c6b_single
/**
 * Fetch the single entry for a section.
 */
function c6b_single(string $section): ?\CraftCms\Cms\Entry\Elements\Entry
{
    return \CraftCms\Cms\Entry\Elements\Entry::find()->section($section)->one();
}
// === END c6b_single

// === START c6b_siteUrl
/**
 * Generate a site URL.
 */
function c6b_siteUrl(string $path = '', array|string $params = [], ?string $scheme = null, int|string|null $siteId = null): string
{
    return \CraftCms\Cms\Support\Url::siteUrl($path, $params, $scheme, $siteId);
}
// === END c6b_siteUrl

// === START c6b_snake
/**
 * Convert a string to snake_case.
 */
function c6b_snake(mixed $string): string
{
    return \CraftCms\Cms\Support\Str::snake((string) $string);
}
// === END c6b_snake

// === START c6b_sort
/**
 * Sort an array, optionally using a callback.
 */
function c6b_sort(iterable $array, string|callable|null $arrow = null): array
{
    if ($array instanceof Traversable) {
        $array = iterator_to_array($array);
    }

    if ($arrow === null) {
        sort($array);
        return $array;
    }

    if (is_string($arrow)) {
        usort($array, fn ($a, $b) => $a[$arrow] <=> $b[$arrow]);
        return $array;
    }

    usort($array, $arrow);
    return $array;
}
// === END c6b_sort

// === START c6b_successMessageInput
/**
 * Render a hidden success-message input.
 */
function c6b_successMessageInput(string $message, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::successMessageInput($message, $options));
}
// === END c6b_successMessageInput

// === START c6b_svg
/**
 * Render an inline SVG.
 */
function c6b_svg(\CraftCms\Cms\Asset\Elements\Asset|string $svg, ?bool $sanitize = null, ?bool $namespace = null, ?string $class = null): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->svgFunction($svg, $sanitize, $namespace, $class));
}
// === END c6b_svg

// === START c6b_t
/**
 * Translate a string.
 */
function c6b_t(
    string $text,
    array $parameters = [],
    ?string $category = 'site',
    ?string $locale = null,
): string {
    return \CraftCms\Cms\t($text, $parameters, $category, $locale);
}
// === END c6b_t

// === START c6b_tag
/**
 * Build an HTML tag.
 */
function c6b_tag(string $type, array|string $attributes = '')
{
    return new \Illuminate\Support\HtmlString(c6b_bladeHtmlExtension()->tagFunction($type, $attributes));
}
// === END c6b_tag

// === START c6b_time
/**
 * Format a time value using the I18N formatter.
 */
function c6b_time(
    mixed $date,
    ?string $format = null,
    mixed $timezone = null,
    ?string $locale = null,
    bool $withTimeZone = false,
): string {
    $format = c6b_normalizeDateFormat($format);
    $carbon = c6b_dateConvert($date, $timezone);
    $formatter = $locale ? \CraftCms\Cms\Support\Facades\I18N::getLocaleById($locale)->getFormatter() : \CraftCms\Cms\Support\Facades\I18N::getFormatter();
    $originalTimeZone = $formatter->timeZone;
    $formatter->timeZone = $timezone !== null ? $carbon->getTimezone()->getName() : $formatter->timeZone;
    $result = $formatter->asTime(\Illuminate\Support\Facades\Date::instance($carbon), $format, withTimeZone: $withTimeZone);
    $formatter->timeZone = $originalTimeZone;

    return $result;
}
// === END c6b_time

// === START c6b_timestamp
/**
 * Format a date as a relative timestamp.
 */
function c6b_timestamp(mixed $value, ?string $format = null, bool $withPreposition = false): string
{
    if ($value === null || $value === '') {
        $value = now();
    }

    try {
        return \CraftCms\Cms\Support\Facades\I18N::getFormatter()->asTimestamp($value, $format, $withPreposition);
    } catch (Throwable) {
        return $value;
    }
}
// === END c6b_timestamp

// === START c6b_truncate
/**
 * Truncate a string.
 */
function c6b_truncate(string $string, int $length, string $suffix = '…', bool $splitSingleWord = true): string
{
    return c6b_bladeTextExtension()->truncateFilter($string, $length, $suffix, $splitSingleWord);
}
// === END c6b_truncate

// === START c6b_ul
/**
 * Render an unordered list.
 */
function c6b_ul(array $items, array $options = []): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::ul($items, $options));
}
// === END c6b_ul

// === START c6b_unique
/**
 * Remove duplicate values from an array.
 */
function c6b_unique(array $array, int $flags = SORT_STRING): array
{
    return array_unique($array, $flags);
}
// === END c6b_unique

// === START c6b_unshift
/**
 * Prepend one or more values to the beginning of an array.
 */
function c6b_unshift(array $array, mixed ...$values): array
{
    array_unshift($array, ...$values);
    return $array;
}
// === END c6b_unshift

// === START c6b_url
/**
 * Generate a URL.
 */
function c6b_url(string $path = '', array|string $params = [], ?string $scheme = null): string
{
    return \CraftCms\Cms\Support\Url::url($path, $params, $scheme);
}
// === END c6b_url

// === START c6b_users
/**
 * Create a user element query.
 */
function c6b_users(array $config = []): \CraftCms\Cms\Element\Queries\UserQuery
{
    return new \CraftCms\Cms\Element\Queries\UserQuery($config);
}
// === END c6b_users

// === START c6b_uuid
/**
 * Generate a UUID v4.
 */
function c6b_uuid(): string
{
    return (string) \CraftCms\Cms\Support\Str::uuid();
}
// === END c6b_uuid

// === START c6b_uuid7
/**
 * Generate a UUID v7.
 */
function c6b_uuid7(): string
{
    return (string) \CraftCms\Cms\Support\Str::uuid7();
}
// === END c6b_uuid7

// === START c6b_values
/**
 * Return the values of an array (re-indexed).
 */
function c6b_values(array $array): array
{
    return array_values($array);
}
// === END c6b_values

// === START c6b_where
/**
 * Filter an array by a key/value condition.
 */
function c6b_where(iterable $array, callable|string $key, mixed $value = null): array
{
    return \CraftCms\Cms\Support\Arr::where($array, $key, $value);
}
// === END c6b_where

// === START c6b_widont
/**
 * Prevent widows in a string by replacing the last space with a non-breaking space.
 */
function c6b_widont(string $string): \Illuminate\Support\HtmlString
{
    return new \Illuminate\Support\HtmlString(\CraftCms\Cms\Support\Html::widont($string));
}
// === END c6b_widont

// === START c6b_without
/**
 * Return an array without the specified value(s).
 */
function c6b_without(mixed $arr, mixed $exclude, bool $strict = false): array
{
    return \Illuminate\Support\Collection::make($arr)
        ->reject(fn ($value) => in_array($value, \CraftCms\Cms\Support\Arr::wrap($exclude), $strict))
        ->all();
}
// === END c6b_without

// === START c6b_withoutKey
/**
 * Return an array without the specified key(s).
 */
function c6b_withoutKey(mixed $arr, array|string $key): array
{
    $arr = (array) $arr;

    foreach (\CraftCms\Cms\Support\Arr::wrap($key) as $k) {
        \CraftCms\Cms\Support\Arr::forget($arr, $k);
    }

    return $arr;
}
// === END c6b_withoutKey
