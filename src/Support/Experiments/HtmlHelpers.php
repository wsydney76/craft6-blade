<?php

use CraftCms\Cms\Asset\Elements\Asset;
use CraftCms\Cms\Support\Html;
use CraftCms\Cms\View\InputNamespace;
use Illuminate\Support\HtmlString;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

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

