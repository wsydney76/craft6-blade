<?php

use CraftCms\Cms\Element\Contracts\ElementInterface;
use CraftCms\Cms\Element\ElementCollection;
use CraftCms\Cms\Support\Arr;
use CraftCms\Cms\Twig\Extensions\ArrayTwigExtension;
use Illuminate\Support\Collection;


use function CraftCms\Cms\renderObjectTemplate;

function c6b_bladeArrayExtension(): ArrayTwigExtension
{
    static $arrayExtension = null;
    return $arrayExtension ??= new ArrayTwigExtension();
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
