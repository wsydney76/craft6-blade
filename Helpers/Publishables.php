<?php


use ___PHPSTORM_HELPERS\object;
use CommerceGuys\Addressing\Formatter\FormatterInterface;
use CraftCms\Aliases\Aliases;
use CraftCms\Cms\Address\Addresses;
use CraftCms\Cms\Address\Elements\Address;
use CraftCms\Cms\Asset\Elements\Asset;
use CraftCms\Cms\Element\Contracts\ElementInterface;
use CraftCms\Cms\Element\Element;
use CraftCms\Cms\Element\ElementCollection;
use CraftCms\Cms\Element\Queries\AddressQuery;
use CraftCms\Cms\Element\Queries\AssetQuery;
use CraftCms\Cms\Element\Queries\ContentBlockQuery;
use CraftCms\Cms\Element\Queries\ElementQuery;
use CraftCms\Cms\Element\Queries\EntryQuery;
use CraftCms\Cms\Element\Queries\UserQuery;
use CraftCms\Cms\Entry\Data\EntryType;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\FieldLayout\Contracts\FieldLayoutProviderInterface;
use CraftCms\Cms\Plugin\Contracts\PluginInterface;
use CraftCms\Cms\Plugin\Plugins;
use CraftCms\Cms\Support\Arr;
use CraftCms\Cms\Support\DateTimeHelper;
use CraftCms\Cms\Support\Env;
use CraftCms\Cms\Support\Facades\HtmlSanitizers;
use CraftCms\Cms\Support\Facades\I18N;
use CraftCms\Cms\Support\Facades\Sites;
use CraftCms\Cms\Support\Html;
use CraftCms\Cms\Support\Json;
use CraftCms\Cms\Support\Money as MoneyHelper;
use CraftCms\Cms\Support\Query;
use CraftCms\Cms\Support\Sequence;
use CraftCms\Cms\Support\Str;
use CraftCms\Cms\Support\Template as TemplateHelper;
use CraftCms\Cms\Support\Typecast;
use CraftCms\Cms\Support\Url;
use CraftCms\Cms\Translation\Locale;
use CraftCms\Cms\User\Elements\User;
use CraftCms\Cms\View\InputNamespace;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\HtmlString;
use Money\Money;
use function CraftCms\Cms\craftAsset;
use function CraftCms\Cms\currentUser;
use function CraftCms\Cms\renderObjectTemplate;

// ---------------------------------------------------------------------------
// Common Helpers
// ---------------------------------------------------------------------------


function c6b_sanitize(HtmlString|string $html): HtmlString
{
    return new HtmlString(HtmlSanitizers::sanitize((string)$html));
}

function c6b_md(string $text, ?string $flavor = null): HtmlString
{
    return new HtmlString(\CraftCms\Cms\Support\Facades\Markdown::parse($text, $flavor));
}

function c6b_tag(string $type, array|string $attributes = '')
{
    return new HtmlString(c6b_bladeHtmlExtension()->tagFunction($type, $attributes));
}

/**
 * Truncate a string.
 */
function c6b_truncate(string $string, int $length, string $suffix = '…', bool $splitSingleWord = true): string
{
    return c6b_bladeTextExtension()->truncateFilter($string, $length, $suffix, $splitSingleWord);
}

/**
 * Format a date
 */
function c6b_asDate($date, $format = 'short'): string
{
    return I18N::getFormatter()->asDate($date, $format);
}

/**
 * Format a date and time
 */
function c6b_asDateTime($date, $format = 'short'): string
{
    return I18N::getFormatter()->asDateTime($date, $format);
}

/**
 * Format a date and time
 */
function c6b_asRelativeTime(mixed $value): string
{
    return I18N::getFormatter()->asRelativeTime($value);
}

function c6b_t(
    string $text,
    array $parameters = [],
    ?string $category = 'site',
    ?string $locale = null,
): string {
    return CraftCms\Cms\t($text, $parameters, $category, $locale);
}

/**
 * Generate a URL.
 */
function c6b_url(string $path = '', array|string $params = [], ?string $scheme = null): string
{
    return Url::url($path, $params, $scheme);
}

function c6b_single(string $section): ?Entry
{
    return Entry::find()->section($section)->one();
}

function c6b_getMatchedElement(): ?ElementInterface
{
    return  Elements::getElementByUri(
        Sites::getRequestPath(request()),
        Sites::getCurrentSite()->id,
        true,
    );
}


// ---------------------------------------------------------------------------
// Filters
// ---------------------------------------------------------------------------

/**
 * Pluck a column of values from an array.
 */
function c6b_column(iterable $array, string|int|null $key, string|int|null $indexBy = null): array
{
    return Arr::pluck($array, $key, $indexBy);
}

/**
 * Check whether an array contains a given value.
 */
function c6b_contains(iterable $array, mixed $value): bool
{
    return Arr::contains($array, $value);
}

/**
 * Return values in the first array not present in any subsequent arrays.
 */
function c6b_diff(array $array, array ...$arrays): array
{
    return array_diff($array, ...$arrays);
}

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

/**
 * Return the first element matching the given key/value pair.
 */
function c6b_firstWhere(iterable $array, callable|string $key, mixed $value = true, bool $strict = false): mixed
{
    return collect($array)->firstWhere($key, $strict ? '===' : '==', $value);
}

/**
 * Flatten a multi-dimensional array.
 */
function c6b_flatten(iterable $array, float|int $depth = INF): array
{
    return Arr::flatten($array, $depth);
}

/**
 * Group an array by a field name or callback.
 */
function c6b_group(iterable $arr, callable|string $arrow): array
{
    $groups = [];

    if (is_string($arrow)) {
        $template = '{'.$arrow.'}';
        foreach ($arr as $item) {
            $groupKey = renderObjectTemplate($template, $item);
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

/**
 * Return the index of the first occurrence of a value in a string or array.
 */
function c6b_indexOf(mixed $haystack, mixed $needle, ?int $default = -1): ?int
{
    return c6b_bladeArrayExtension()->indexOfFilter($haystack, $needle, $default);
}

/**
 * Return values present in all given arrays.
 */
function c6b_intersect(array $array, array ...$arrays): array
{
    return array_intersect($array, ...$arrays);
}

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

/**
 * Merge two arrays together.
 */
function c6b_merge(iterable $arr1, iterable $arr2, bool $recursive = false): array
{
    return c6b_bladeArrayExtension()->mergeFilter($arr1, $arr2, $recursive);
}

/**
 * Sort an array by one or more keys.
 */
function c6b_multisort(mixed $array, mixed $key, int|array $direction = SORT_ASC, int|array $sortFlag = SORT_REGULAR): array
{
    return c6b_bladeArrayExtension()->multisortFilter($array, $key, $direction, $sortFlag);
}

/**
 * Append one or more values to an array.
 */
function c6b_push(array $array, mixed ...$values): array
{
    array_push($array, ...$values);
    return $array;
}

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

/**
 * Remove duplicate values from an array.
 */
function c6b_unique(array $array, int $flags = SORT_STRING): array
{
    return array_unique($array, $flags);
}

/**
 * Prepend one or more values to the beginning of an array.
 */
function c6b_unshift(array $array, mixed ...$values): array
{
    array_unshift($array, ...$values);
    return $array;
}

/**
 * Return the values of an array (re-indexed).
 */
function c6b_values(array $array): array
{
    return array_values($array);
}

/**
 * Filter an array by a key/value condition.
 */
function c6b_where(iterable $array, callable|string $key, mixed $value = null): array
{
    return Arr::where($array, $key, $value);
}

/**
 * Return an array without the specified value(s).
 */
function c6b_without(mixed $arr, mixed $exclude, bool $strict = false): array
{
    return Collection::make($arr)
        ->reject(fn ($value) => in_array($value, Arr::wrap($exclude), $strict))
        ->all();
}

/**
 * Return an array without the specified key(s).
 */
function c6b_withoutKey(mixed $arr, array|string $key): array
{
    $arr = (array) $arr;

    foreach (Arr::wrap($key) as $k) {
        Arr::forget($arr, $k);
    }

    return $arr;
}

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

/**
 * Create a Collection (or ElementCollection) from a value.
 */
function c6b_collect(mixed $var): Collection
{
    $collection = Collection::make($var);

    if ($collection->isNotEmpty() && $collection->doesntContain(fn ($item) => ! $item instanceof ElementInterface)) {
        return ElementCollection::make($collection);
    }

    return $collection;
}

/**
 * Create an array by combining keys and values.
 */
function c6b_combine(array $keys, array $values): array
{
    return array_combine($keys, $values);
}

/**
 * Shuffle an array and return the result.
 */
function c6b_shuffle(iterable $arr): array
{
    return c6b_bladeArrayExtension()->shuffleFunction($arr);
}


// ---------------------------------------------------------------------------
// Filters
// ---------------------------------------------------------------------------

/**
 * Format an address element.
 */
function c6b_address(?Address $address, array $options = [], ?FormatterInterface $formatter = null): string
{
    if ($address === null) {
        return '';
    }
    return app(Addresses::class)->formatAddress($address, $options, $formatter);
}

/**
 * Format a currency value.
 */
function c6b_currency(mixed $value, ?string $currency = null, array $options = [], array $textOptions = [], bool $stripZeros = false): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return I18N::getFormatter()->asCurrency($value, $currency, $stripZeros);
    } catch (Throwable) {
        return $value;
    }
}

/**
 * Returns the value if not empty, otherwise the default.
 *
function c6b_default(mixed $value, mixed $default = ''): mixed
{
return CoreTwigExtension::defaultFilter($value, $default);
}

/**
 * Format a filesize value.
 */
function c6b_filesize(mixed $value, ?int $decimals = null): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return I18N::getFormatter()->asShortSize($value, $decimals);
    } catch (Throwable) {
        return $value;
    }
}

/**
 * JSON-encode a value with safe defaults.
 */
function c6b_jsonEncode(mixed $value, ?int $options = null, int $depth = 512): string|false
{
    return c6b_bladeCoreExtension()->jsonEncodeFilter($value, $options, $depth);
}

/**
 * JSON-decode a string.
 */
function c6b_jsonDecode(string $value, bool $asArray = true): mixed
{
    return Json::decode($value, $asArray);
}

/**
 * Escape a query param value so it is treated as a literal string.
 */
function c6b_literal(mixed $value): string
{
    return Query::escapeParam((string) $value);
}

/**
 * Format a Money object as a string.
 */
function c6b_money(?Money $money, ?string $locale = null): ?string
{
    if ($money === null) {
        return null;
    }
    return MoneyHelper::toString($money, $locale);
}

/**
 * Format a number.
 */
function c6b_number(mixed $value, ?int $decimals = null, array $options = [], array $textOptions = [], ?string $locale = null): string
{
    if ($value === null || $value === '') {
        return '';
    }
    $formatter = $locale
        ? I18N::getLocaleById($locale)->getFormatter()
        : I18N::getFormatter();
    try {
        if (! $formatter->willBeMisrepresented($value)) {
            return $formatter->asDecimal($value, $decimals, $options);
        }
    } catch (Throwable) {
    }
    return $value;
}

/**
 * Format a value as a percentage.
 */
function c6b_percentage(mixed $value, ?int $decimals = null): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return I18N::getFormatter()->asPercent($value, $decimals);
    } catch (Throwable) {
        return $value;
    }
}

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

/**
 * Generate an action URL.
 */
function c6b_actionUrl(string $path = '', array|string $params = [], ?string $scheme = null): string
{
    return Url::actionUrl($path, $params, $scheme);
}

/**
 * Resolve an alias.
 */
function c6b_alias(string $alias): string
{
    return Aliases::get($alias);
}

/**
 * Generate a public asset URL (Laravel helper).
 */
function c6b_asset(string $path, mixed $secure = null): string
{
    return asset($path, $secure);
}

/**
 * Clone a value.
 */
function c6b_clone(mixed $var): mixed
{
    return clone $var;
}

/**
 * Configure an object with the given attributes.
 */
function c6b_configure(object $object, array $attributes): object
{
    return Typecast::configure($object, $attributes);
}

/**
 * Generate a control panel URL.
 */
function c6b_cpUrl(string $path = '', array|string $params = [], ?string $scheme = null): string
{
    return Url::cpUrl($path, $params, $scheme);
}

/**
 * Generate a Craft asset URL.
 */
function c6b_craftAsset(string $path): string
{
    return craftAsset($path);
}

/**
 * Instantiate an object by class name or config array.
 */
function c6b_create(string|array $type, array $params = []): object
{
    return c6b_bladeCoreExtension()->createFunction($type, $params);
}

/**
 * Dump variables for debugging (HTML output).
 */
function c6b_dump(mixed ...$vars): HtmlString
{
    $output = '';
    foreach ($vars as $var) {
        ob_start();
        dump($var);
        $output .= str_replace('<code>', '<code style="display:block;">', ob_get_clean());
    }
    return new HtmlString($output);
}

/**
 * Dump and die.
 */
function c6b_dd(mixed ...$vars): never
{
    dd(...$vars);
}

/**
 * Encode a URL.
 */
function c6b_encodeUrl(string $url): string
{
    return Url::encodeUrl($url);
}

/**
 * Get an entry type by handle.
 */
function c6b_entryType(string $handle): EntryType
{
    return c6b_bladeCoreExtension()->entryTypeFunction($handle);
}

/**
 * Wrap a raw SQL expression.
 */
function c6b_expression(mixed $expression): Expression
{
    return new Expression($expression);
}

/**
 * Get the SQL expression for a field value.
 */
function c6b_fieldValueSql(FieldLayoutProviderInterface $provider, string $fieldHandle, ?string $key = null): ?string
{
    return c6b_bladeCoreExtension()->fieldValueSqlFunction($provider, $fieldHandle, $key);
}

/**
 * Get an environment variable value.
 */
function c6b_getenv(string $name, mixed $default = null): mixed
{
    return Env::get($name, $default);
}

/**
 * Execute a GraphQL query.
 */
function c6b_gql(string $query, ?array $variables = null, ?string $operationName = null): array
{
    return c6b_bladeCoreExtension()->gqlFunction($query, $variables, $operationName);
}

/**
 * Parse an environment variable string.
 */
function c6b_parseEnv(string $str): ?string
{
    return Env::parse($str);
}

/**
 * Parse a boolean environment variable.
 */
function c6b_parseBooleanEnv(string $str): ?bool
{
    return Env::parseBoolean($str);
}

/**
 * Get a plugin instance by handle.
 */
function c6b_plugin(string $handle): ?PluginInterface
{
    return app(Plugins::class)->getPlugin($handle);
}

/**
 * Mark a string as raw/safe HTML.
 */
function c6b_raw(mixed $value): HtmlString
{
    return new HtmlString(TemplateHelper::raw($value));
}

/**
 * Render an object template string.
 */
function c6b_renderObjectTemplate(string $template, mixed $object): string
{
    return renderObjectTemplate($template, $object);
}

/**
 * Get the next (or current) value of a named sequence.
 */
function c6b_seq(string $name, ?int $length = null, bool $next = true): int|string
{
    if ($next) {
        return Sequence::next($name, $length);
    }
    return Sequence::current($name, $length);
}

/**
 * Generate a site URL.
 */
function c6b_siteUrl(string $path = '', array|string $params = [], ?string $scheme = null, int|string|null $siteId = null): string
{
    return Url::siteUrl($path, $params, $scheme, $siteId);
}



// ---------------------------------------------------------------------------
// Element query factories
// ---------------------------------------------------------------------------

function c6b_addresses(array $config = []): AddressQuery
{
    return new AddressQuery($config);
}

function c6b_assets(array $config = []): AssetQuery
{
    return new AssetQuery($config);
}

function c6b_contentBlocks(array $config = []): ContentBlockQuery
{
    return new ContentBlockQuery($config);
}

function c6b_elements(string $elementType = Element::class, array $config = []): ElementQuery
{
    return new ElementQuery($elementType, $config);
}

function c6b_entries(array $config = []): EntryQuery
{
    return new EntryQuery($config);
}

function c6b_users(array $config = []): UserQuery
{
    return new UserQuery($config);
}

// ---------------------------------------------------------------------------
// Permission checks
// ---------------------------------------------------------------------------

function c6b_canCreateDrafts(ElementInterface $element, ?User $user = null): ?bool
{
    return ($user ?? currentUser())?->can('createDrafts', $element);
}

function c6b_canDelete(ElementInterface $element, ?User $user = null): ?bool
{
    return ($user ?? currentUser())?->can('delete', $element);
}

function c6b_canDeleteForSite(ElementInterface $element, ?User $user = null): ?bool
{
    return ($user ?? currentUser())?->can('deleteForSite', $element);
}

function c6b_canDuplicate(ElementInterface $element, ?User $user = null): ?bool
{
    return ($user ?? currentUser())?->can('duplicate', $element);
}

function c6b_canSave(ElementInterface $element, ?User $user = null): ?bool
{
    return ($user ?? currentUser())?->can('save', $element);
}

function c6b_canView(ElementInterface $element, ?User $user = null): ?bool
{
    return ($user ?? currentUser())?->can('view', $element);
}

// ---------------------------------------------------------------------------
// Page lifecycle
// ---------------------------------------------------------------------------

/**
 * Render registered head tags.
 */
function c6b_head(): HtmlString
{
    return new HtmlString(app(PageLifecycle::class)->head());
}

/**
 * Render registered begin-body tags.
 */
function c6b_beginBody(): HtmlString
{
    return new HtmlString(app(PageLifecycle::class)->beginBody());
}

/**
 * Render registered end-body tags.
 */
function c6b_endBody(): HtmlString
{
    return new HtmlString(app(PageLifecycle::class)->endBody());
}


// ---------------------------------------------------------------------------
// Filters
// ---------------------------------------------------------------------------

/**
 * Format a date as an Atom timestamp.
 */
function c6b_atom(mixed $date, mixed $timezone = null): string
{
    return c6b_dateConvert($date, $timezone)->format(DateTimeInterface::ATOM);
}

/**
 * Format a date as an RSS timestamp.
 */
function c6b_rss(mixed $date, mixed $timezone = null): string
{
    return c6b_dateConvert($date, $timezone)->format(DateTimeInterface::RSS);
}

/**
 * Format a date as an HTTP date (RFC 7231).
 */
function c6b_httpdate(mixed $date, mixed $timezone = null): string
{
    return c6b_dateConvert($date, $timezone)->format(DateTimeInterface::RFC7231);
}

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
    $formatter = $locale ? I18N::getLocaleById($locale)->getFormatter() : I18N::getFormatter();
    $originalTimeZone = $formatter->timeZone;
    $formatter->timeZone = $timezone !== null ? $carbon->getTimezone()->getName() : $formatter->timeZone;
    $result = $formatter->asDate(Date::instance($carbon), $format);
    $formatter->timeZone = $originalTimeZone;

    return $result;
}

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
    $formatter = $locale ? I18N::getLocaleById($locale)->getFormatter() : I18N::getFormatter();
    $originalTimeZone = $formatter->timeZone;
    $formatter->timeZone = $timezone !== null ? $carbon->getTimezone()->getName() : $formatter->timeZone;
    $result = $formatter->asTime(Date::instance($carbon), $format, withTimeZone: $withTimeZone);
    $formatter->timeZone = $originalTimeZone;

    return $result;
}

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
    $formatter = $locale ? I18N::getLocaleById($locale)->getFormatter() : I18N::getFormatter();
    $originalTimeZone = $formatter->timeZone;
    $formatter->timeZone = $timezone !== null ? $carbon->getTimezone()->getName() : $formatter->timeZone;
    $result = $formatter->asDatetime(Date::instance($carbon), $format, withTimeZone: $withTimeZone);
    $formatter->timeZone = $originalTimeZone;

    return $result;
}

/**
 * Format a duration value as a human-readable string.
 */
function c6b_duration(mixed $value): string
{
    return DateTimeHelper::humanDuration($value);
}

/**
 * Format a date as a relative timestamp.
 */
function c6b_timestamp(mixed $value, ?string $format = null, bool $withPreposition = false): string
{
    if ($value === null || $value === '') {
        $value = now();
    }

    try {
        return I18N::getFormatter()->asTimestamp($value, $format, $withPreposition);
    } catch (Throwable) {
        return $value;
    }
}

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

/**
 * Create a DateTimeInterface from a date value.
 */
function c6b_dateCreate(mixed $date = null, mixed $timezone = null): DateTimeInterface
{
    if (is_array($date)) {
        $date = DateTimeHelper::toDateTime($date, false, false);
        if ($date === false) {
            throw new InvalidArgumentException('Invalid date passed to c6b_dateCreate()');
        }
    }

    return c6b_dateConvert($date, $timezone);
}

// ---------------------------------------------------------------------------
// Internal helpers
// ---------------------------------------------------------------------------

/**
 * Normalize a date format string (icu:/php: prefix handling).
 *
 * @internal
 */
function c6b_normalizeDateFormat(?string $format): ?string
{
    if ($format === null || in_array($format, [Locale::LENGTH_SHORT, Locale::LENGTH_MEDIUM, Locale::LENGTH_LONG, Locale::LENGTH_FULL], true)) {
        return $format;
    }

    if (str_starts_with($format, 'icu:')) {
        return substr($format, 4);
    }

    return Str::start($format, 'php:');
}

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
        $carbon = Date::parse($date);
    }

    if ($timezone !== null) {
        $carbon = $carbon->copy()->setTimezone($timezone);
    }

    return $carbon;
}


// ---------------------------------------------------------------------------
// Filters
// ---------------------------------------------------------------------------

/**
 * Append HTML inside a tag.
 */
function c6b_append(string $tag, string $html, ?string $ifExists = null): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->appendFilter($tag, $html, $ifExists));
}

/**
 * Modify attributes on a tag.
 */
function c6b_modifyAttr(string $tag, array $attributes): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->attrFilter($tag, $attributes));
}

/**
 * Explode a class attribute string into an array.
 */
function c6b_explodeClass(array|string $class): array
{
    return Html::explodeClass($class);
}

/**
 * Explode a style attribute string into an array.
 */
function c6b_explodeStyle(array|string $style): array
{
    return Html::explodeStyle($style);
}

/**
 * Normalize an element ID.
 */
function c6b_htmlId(string $id, bool $checkUniqueness = true): string
{
    return Html::id($id, $checkUniqueness);
}

/**
 * Namespace HTML inputs.
 */
function c6b_namespaceInputs(string $html, ?string $namespace = null): HtmlString
{
    return new HtmlString(app(InputNamespace::class)->namespaceInputs($html, $namespace));
}

/**
 * Namespace HTML inputs (alias for c6b_namespaceInputs).
 */
function c6b_ns(string $html, ?string $namespace = null): HtmlString
{
    return c6b_namespaceInputs($html, $namespace);
}

/**
 * Namespace HTML attributes.
 */
function c6b_namespaceAttributes(string $html, string $namespace, bool $withClasses = false): HtmlString
{
    return new HtmlString(Html::namespaceAttributes($html, $namespace, $withClasses));
}

/**
 * Namespace an input name.
 */
function c6b_namespaceInputName(string $name, ?string $namespace = null): string
{
    return app(InputNamespace::class)->namespaceInputName($name, $namespace);
}

/**
 * Namespace an element ID.
 */
function c6b_namespaceId(string $id, ?string $namespace = null): string
{
    return app(InputNamespace::class)->namespaceId($id, $namespace);
}

/**
 * Parse a tag's attributes into an array.
 */
function c6b_parseAttr(string $tag): array
{
    return c6b_bladeHtmlExtension()->parseAttrFilter($tag);
}

/**
 * Parse element references in a string.
 */
function c6b_parseRefs(mixed $str, ?int $siteId = null): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->parseRefsFilter($str, $siteId));
}

/**
 * Prepend HTML inside a tag.
 */
function c6b_prepend(string $tag, string $html, ?string $ifExists = null): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->prependFilter($tag, $html, $ifExists));
}

/**
 * Remove a CSS class from a tag.
 */
function c6b_removeClass(string $tag, array|string $class): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->removeClassFilter($tag, $class));
}

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

/**
 * Render a hidden action input.
 */
function c6b_actionInput(string $action, array $options = []): HtmlString
{
    return new HtmlString(Html::actionInput($action, $options));
}

/**
 * Render tag attributes as an HTML string.
 */
function c6b_attr(array $attributes): HtmlString
{
    return new HtmlString(Html::renderTagAttributes($attributes));
}

/**
 * Render a CSRF input.
 */
function c6b_csrfInput(array $options = []): HtmlString
{
    return new HtmlString(Html::csrfInput($options));
}

/**
 * Generate a data URL for a file or asset.
 */
function c6b_dataUrl(Asset|string $file, ?string $mimeType = null): string
{
    return c6b_bladeHtmlExtension()->dataUrlFunction($file, $mimeType);
}

/**
 * Render a hidden fail-message input.
 */
function c6b_failMessageInput(string $message, array $options = []): HtmlString
{
    return new HtmlString(Html::failMessageInput($message, $options));
}

/**
 * Render a hidden input.
 */
function c6b_hiddenInput(string $name, ?string $value = null, array $options = []): HtmlString
{
    return new HtmlString(Html::hiddenInput($name, $value, $options));
}

/**
 * Render an input element.
 */
function c6b_input(string $type, ?string $name = null, mixed $value = null, array $options = []): HtmlString
{
    return new HtmlString(Html::input($type, $name, $value, $options));
}

/**
 * Render an ordered list.
 */
function c6b_ol(array $items, array $options = []): HtmlString
{
    return new HtmlString(Html::ol($items, $options));
}

/**
 * Render a hidden redirect input.
 */
function c6b_redirectInput(string $url, array $options = []): HtmlString
{
    return new HtmlString(Html::redirectInput($url, $options));
}

/**
 * Render a hidden success-message input.
 */
function c6b_successMessageInput(string $message, array $options = []): HtmlString
{
    return new HtmlString(Html::successMessageInput($message, $options));
}

/**
 * Render an inline SVG.
 */
function c6b_svg(Asset|string $svg, ?bool $sanitize = null, ?bool $namespace = null, ?string $class = null): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->svgFunction($svg, $sanitize, $namespace, $class));
}

/**
 * Render an unordered list.
 */
function c6b_ul(array $items, array $options = []): HtmlString
{
    return new HtmlString(Html::ul($items, $options));
}

/**
 * Render a heading tag (h1–h6).
 */
function c6b_h(int $level, array|string $attributes = ''): HtmlString
{
    return new HtmlString(c6b_bladeHtmlExtension()->headingFunction($level, $attributes));
}

/**
 * Alias for c6b_h().
 */
function c6b_heading(int $level, array|string $attributes = ''): HtmlString
{
    return c6b_h($level, $attributes);
}

function c6b_h1(array|string $attributes = ''): HtmlString
{
    return c6b_h(1, $attributes);
}

function c6b_h2(array|string $attributes = ''): HtmlString
{
    return c6b_h(2, $attributes);
}

function c6b_h3(array|string $attributes = ''): HtmlString
{
    return c6b_h(3, $attributes);
}

function c6b_h4(array|string $attributes = ''): HtmlString
{
    return c6b_h(4, $attributes);
}

function c6b_h5(array|string $attributes = ''): HtmlString
{
    return c6b_h(5, $attributes);
}

function c6b_h6(array|string $attributes = ''): HtmlString
{
    return c6b_h(6, $attributes);
}



// ---------------------------------------------------------------------------
// Filters
// ---------------------------------------------------------------------------

/**
 * Convert a string to ASCII.
 */
function c6b_ascii(string $value, string $language = 'en'): string
{
    return Str::ascii($value, $language);
}

/**
 * Convert a string to camelCase.
 */
function c6b_camel(mixed $string): string
{
    return Str::camel((string) $string);
}

/**
 * Encrypt and encode a string (enc-enc).
 */
function c6b_encenc(mixed $str): string
{
    return Str::encenc((string) $str);
}

/**
 * Hash or encrypt a string.
 */
function c6b_hash(string $data, ?string $algo = null): string
{
    if ($algo === null) {
        return Crypt::encrypt($data);
    }
    return hash($algo, $data);
}

/**
 * Convert a string to kebab-case.
 */
function c6b_kebab(mixed $string, string $glue = '-', bool $lower = true, bool $removePunctuation = true): string
{
    return c6b_bladeTextExtension()->kebabFilter($string, $glue, $lower, $removePunctuation);
}

/**
 * Lowercase the first character of a string.
 */
function c6b_lcfirst(mixed $string): string
{
    return mb_lcfirst((string) $string);
}

/**
 * Convert a string to PascalCase.
 */
function c6b_pascal(mixed $string): string
{
    return Str::pascal((string) $string);
}

/**
 * Replace occurrences in a string, supporting regex patterns.
 */
function c6b_replace(mixed $str, mixed $search, mixed $replace = null, ?bool $regex = null): mixed
{
    return c6b_bladeTextExtension()->replaceFilter($str, $search, $replace, $regex);
}

/**
 * Convert a string to snake_case.
 */
function c6b_snake(mixed $string): string
{
    return Str::snake((string) $string);
}

/**
 * Prevent widows in a string by replacing the last space with a non-breaking space.
 */
function c6b_widont(string $string): HtmlString
{
    return new HtmlString(Html::widont($string));
}

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

/**
 * Generate a random string.
 */
function c6b_randomString(int $length = 36): string
{
    return Str::random($length);
}

/**
 * Generate a UUID v4.
 */
function c6b_uuid(): string
{
    return (string) Str::uuid();
}

/**
 * Generate a UUID v7.
 */
function c6b_uuid7(): string
{
    return (string) Str::uuid7();
}

