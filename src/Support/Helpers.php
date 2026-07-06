<?php

use CraftCms\Cms\Element\Contracts\ElementInterface;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\HtmlSanitizers;
use CraftCms\Cms\Support\Facades\Sites;
use CraftCms\Cms\Support\Url;
use CraftCms\Cms\Twig\Extensions\HtmlTwigExtension;
use CraftCms\Cms\Twig\Extensions\TextTwigExtension;
use CraftCms\Cms\View\TemplateMode;
use CraftCms\Cms\View\TemplateRenderer;
use Illuminate\Support\HtmlString;

function c6b_bladeHtmlExtension(): HtmlTwigExtension
{
    static $htmlExtension = null;
    return $htmlExtension ??= new HtmlTwigExtension();
}

function c6b_bladeTextExtension(): TextTwigExtension
{
    static $textExtension = null;
    return $textExtension ??= new TextTwigExtension();
}

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