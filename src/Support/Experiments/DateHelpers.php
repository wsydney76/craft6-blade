<?php

use CraftCms\Cms\Support\DateTimeHelper;
use CraftCms\Cms\Support\Facades\I18N;
use CraftCms\Cms\Support\Str;
use CraftCms\Cms\Translation\Locale;
use Illuminate\Support\Facades\Date;


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

