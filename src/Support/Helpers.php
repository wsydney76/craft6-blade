<?php

use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\HtmlSanitizers;
use CraftCms\Cms\Translation\Formatter;
use CraftCms\Cms\Twig\Extensions\HtmlTwigExtension;
use CraftCms\Cms\Twig\Extensions\TextTwigExtension;
use CraftCms\Cms\Twig\TemplateRenderer;
use CraftCms\Cms\View\TemplateMode;
use Illuminate\Support\HtmlString;

if (!function_exists('bladeHtmlExtension')) {
    function bladeHtmlExtension(): HtmlTwigExtension
    {
        static $htmlExtension = null;
        return $htmlExtension ??= new HtmlTwigExtension();
    }
}

if (!function_exists('bladeTextExtension')) {
    function bladeTextExtension(): TextTwigExtension
    {
        static $textExtension = null;
        return $textExtension ??= new TextTwigExtension();
    }
}


if (!function_exists('sanitize')) {
    function sanitize(HtmlString|string $html): HtmlString
    {
        return new HtmlString(HtmlSanitizers::sanitize((string) $html));
    }
}

if (!function_exists('md')) {
    function md(string $text, ?string $flavor = null): HtmlString
    {
        return new HtmlString(\CraftCms\Cms\Support\Facades\Markdown::parse($text, $flavor));
    }
}

if (!function_exists('tag')) {

    function tag(string $type, array|string $attributes = '')
    {
        return new HtmlString(bladeHtmlExtension()->tagFunction($type, $attributes));
    }
}

if (!function_exists('truncate')) {
    /**
     * Truncate a string.
     */
    function truncate(string $string, int $length, string $suffix = '…', bool $splitSingleWord = true): string {
        return bladeTextExtension()->truncateFilter($string, $length, $suffix, $splitSingleWord);
    }
}

if (!function_exists('asDate')) {
    /**
     * Format a date
     */
    function asDate($date, $format = 'short'): string {
        return new Formatter()->asDate($date,$format);
    }
}

if (!function_exists('asDateTime')) {
    /**
     * Format a date and time
     */
    function asDateTime($date, $format = 'short'): string {
        return new Formatter()->asDateTime($date,$format);
    }
}


if (!function_exists('t')) {
    function t(
        string $text,
        array $parameters = [],
        ?string $category = 'site',
        ?string $locale = null,
    ): string {
        return CraftCms\Cms\t($text, $parameters, $category, $locale);
    }
}

if (!function_exists('renderTwig')) {
    function renderTwig($template, array $data = [], ?TemplateMode $templateMode = null): HtmlString {
        return new HtmlString(app(TemplateRenderer::class)->renderTemplate($template, $data, $templateMode));
    }
}

if (!function_exists('single')) {
    function single(string $section): ?Entry
    {
        return Entry::find()->section($section)->one();
    }
}
