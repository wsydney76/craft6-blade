<?php

use CraftCms\Cms\Support\Html;
use CraftCms\Cms\Support\Str;
use CraftCms\Cms\Twig\Extensions\TextTwigExtension;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Stringable;



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

