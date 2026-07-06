<?php

use CommerceGuys\Addressing\Formatter\FormatterInterface;
use CraftCms\Aliases\Aliases;
use CraftCms\Cms\Address\Addresses;
use CraftCms\Cms\Address\Elements\Address;
use CraftCms\Cms\Element\Contracts\ElementInterface;
use CraftCms\Cms\Element\Element;
use CraftCms\Cms\Element\Queries\AddressQuery;
use CraftCms\Cms\Element\Queries\AssetQuery;
use CraftCms\Cms\Element\Queries\ContentBlockQuery;
use CraftCms\Cms\Element\Queries\ElementQuery;
use CraftCms\Cms\Element\Queries\EntryQuery;
use CraftCms\Cms\Element\Queries\UserQuery;
use CraftCms\Cms\Entry\Data\EntryType;
use CraftCms\Cms\Field\Elements\ContentBlock;
use CraftCms\Cms\FieldLayout\Contracts\FieldLayoutProviderInterface;
use CraftCms\Cms\Plugin\Contracts\PluginInterface;
use CraftCms\Cms\Plugin\Plugins;
use CraftCms\Cms\Support\Env;
use CraftCms\Cms\Support\Facades\I18N;
use CraftCms\Cms\Support\Json;
use CraftCms\Cms\Support\Money as MoneyHelper;
use CraftCms\Cms\Support\Query;
use CraftCms\Cms\Support\Sequence;
use CraftCms\Cms\Support\Template as TemplateHelper;
use CraftCms\Cms\Support\Typecast;
use CraftCms\Cms\Support\Url;
use CraftCms\Cms\Twig\Extensions\CoreTwigExtension;
use CraftCms\Cms\Twig\PageLifecycle;
use CraftCms\Cms\User\Elements\User;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\HtmlString;
use Money\Money;
use function CraftCms\Cms\craftAsset;
use function CraftCms\Cms\currentUser;
use function CraftCms\Cms\renderObjectTemplate;

function c6b_bladeCoreExtension(): CoreTwigExtension
{
    static $coreExtension = null;
    return $coreExtension ??= app(CoreTwigExtension::class);
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

