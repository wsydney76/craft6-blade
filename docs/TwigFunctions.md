# Twig → Blade/PHP Mapping Reference

Generated from `Twig\Extension\CoreExtension` (twig/twig 3.x).

---

## Functions

| Twig Function | Description | Blade/PHP Equivalent |
|---|---|---|
| `parent()` | Renders the content of the parent block in a child template | `@parent` inside a `@section` |
| `block(name, template?)` | Renders a named block, optionally from another template | `@yield('name')` / `$__env->yieldContent('name')` |
| `attribute(obj, attr, args?)` | Dynamically accesses an attribute or method on a variable | `$obj->$attr` / `data_get($obj, $attr)` |
| `max(…values)` | Returns the largest value from a sequence or argument list | `max(…$values)` |
| `min(…values)` | Returns the smallest value from a sequence or argument list | `min(…$values)` |
| `range(low, high, step?)` | Returns an array of integers between two values (like PHP `range()`) | `range($low, $high, $step)` |
| `constant('NAME', obj?)` | Returns the value of a PHP constant or class constant | `constant('NAME')` / `ClassName::CONST` |
| `cycle(array, position)` | Cycles over an array, wrapping around using modulo | `$array[$position % count($array)]` |
| `random(values?, max?)` | Returns a random item from an array/string, or a random integer | `array_rand($array)` / `mt_rand($min, $max)` / `str_split` + shuffle |
| `date(date?, timezone?)` | Converts a value to a `\DateTime` instance (used for comparisons) | `new \DateTime($date)` / `Carbon::parse($date)` |
| `include(template, vars?, withContext?, ignoreMissing?, sandboxed?)` | Renders and returns a template as a string | `@include('template', $vars)` / `view('template', $vars)->render()` |
| `source(name, ignoreMissing?)` | Returns the raw source code of a template without rendering it | `file_get_contents(resource_path('views/…'))` |
| `enum_cases('EnumClass')` | Returns all cases of a PHP enum | `EnumClass::cases()` |
| `enum('EnumClass')` | Provides access to an enum class (returns first case) | `EnumClass::cases()[0]` / `EnumClass::from(…)` |

---

## Filters

### Formatting

| Twig Filter | Description | Blade/PHP Equivalent |
|---|---|---|
| `\|date(format?, tz?)` | Formats a date/timestamp/DateInterval | `$date->format($format)` / `Carbon::parse($date)->format($format)` |
| `\|date_modify(modifier)` | Modifies a date with a string modifier (e.g. `"+1 day"`) | `(clone $date)->modify($modifier)` / `Carbon::parse($date)->modify($modifier)` |
| `\|format(…args)` | Applies `sprintf()` formatting to a string | `sprintf($format, …$args)` |
| `\|replace({from: to})` | Replaces substrings using a map | `strtr($str, $from)` |
| `\|number_format(dec?, point?, sep?)` | Formats a number with grouped thousands | `number_format($n, $dec, $point, $sep)` |
| `\|abs` | Returns the absolute value | `abs($value)` |
| `\|round(precision?, method?)` | Rounds a number (`common`/`ceil`/`floor`) | `round($v, $p)` / `ceil($v)` / `floor($v)` |

### Encoding

| Twig Filter | Description | Blade/PHP Equivalent |
|---|---|---|
| `\|url_encode` | URL-encodes a string (RFC 3986) or array to query string | `rawurlencode($str)` / `http_build_query($arr, '', '&', PHP_QUERY_RFC3986)` |
| `\|json_encode(options?)` | JSON-encodes a value | `json_encode($value, $options)` |
| `\|convert_encoding(to, from)` | Converts character encoding via `iconv()` | `iconv($from, $to, $string)` |

### String

| Twig Filter | Description | Blade/PHP Equivalent |
|---|---|---|
| `\|title` | Converts string to title case (multibyte-aware) | `mb_convert_case($str, MB_CASE_TITLE)` / `Str::title($str)` |
| `\|capitalize` | Uppercases the first character, lowercases the rest | `mb_strtoupper(mb_substr($str,0,1)).mb_strtolower(mb_substr($str,1))` / `Str::ucfirst($str)` |
| `\|upper` | Converts string to uppercase | `mb_strtoupper($str)` / `Str::upper($str)` |
| `\|lower` | Converts string to lowercase | `mb_strtolower($str)` / `Str::lower($str)` |
| `\|striptags(allowed?)` | Strips HTML/PHP tags | `strip_tags($str, $allowed)` |
| `\|trim(chars?, side?)` | Trims whitespace (or chars) from `both`/`left`/`right` | `trim($str)` / `ltrim($str)` / `rtrim($str)` |
| `\|nl2br` | Inserts `<br>` before newlines (pre-escaped) | `nl2br($str)` |
| `\|spaceless` *(deprecated 3.12)* | Removes whitespace between HTML tags | `preg_replace('/>\s+</', '><', trim($str))` |

### Array

| Twig Filter | Description | Blade/PHP Equivalent |
|---|---|---|
| `\|join(glue?, and?)` | Joins array elements into a string; optional final separator | `implode($glue, $array)` |
| `\|split(delimiter, limit?)` | Splits a string into an array | `explode($delimiter, $str, $limit)` / `str_split` |
| `\|sort(arrow?)` | Sorts an array, preserving keys; optional arrow function comparator | `asort($array)` / `uasort($array, $fn)` |
| `\|merge(…arrays)` | Merges one or more arrays | `array_merge($a, $b)` |
| `\|batch(size, fill?)` | Splits array into chunks of given size, optionally filling the last | `array_chunk($array, $size)` |
| `\|column(name, index?)` | Extracts a column from a 2D array | `array_column($array, $name, $index)` |
| `\|filter(arrow)` | Filters array elements using an arrow function | `array_filter($array, $fn, ARRAY_FILTER_USE_BOTH)` |
| `\|map(arrow)` | Transforms each element using an arrow function | `array_map($fn, $array)` (keys preserved) |
| `\|reduce(arrow, initial?)` | Reduces an array to a single value | `array_reduce($array, $fn, $initial)` |
| `\|find(arrow)` | Returns the first element matching the arrow function | `Arr::first($array, $fn)` / manual `foreach` |

### String + Array

| Twig Filter | Description | Blade/PHP Equivalent |
|---|---|---|
| `\|reverse(preserveKeys?)` | Reverses a string or array | `strrev($str)` / `array_reverse($array, $preserveKeys)` |
| `\|shuffle` | Randomly shuffles characters in a string or elements in an array | `shuffle($array)` / `str_split` + `shuffle` + `implode` |
| `\|length` | Returns length of string (multibyte), array, or Traversable | `mb_strlen($str)` / `count($array)` |
| `\|slice(start, length?, preserveKeys?)` | Extracts a slice of a string or array | `mb_substr($str, $start, $len)` / `array_slice($array, $start, $len, $preserveKeys)` |
| `\|first` | Returns the first element/character | `Arr::first($array)` / `mb_substr($str, 0, 1)` |
| `\|last` | Returns the last element/character | `Arr::last($array)` / `mb_substr($str, -1, 1)` |

### Iteration & Runtime

| Twig Filter | Description | Blade/PHP Equivalent |
|---|---|---|
| `\|default(value?)` | Returns the value if not empty, otherwise the default | `$value ?: $default` / `$value ?? $default` |
| `\|keys` | Returns the keys of an array or Traversable | `array_keys($array)` |
| `\|invoke(…args)` | Calls a closure stored in a variable | `$closure(…$args)` |

---

## Tests (`is` / `is not`)

| Twig Test | Description | Blade/PHP Equivalent |
|---|---|---|
| `is even` | Checks if a number is even | `$n % 2 === 0` |
| `is odd` | Checks if a number is odd | `$n % 2 !== 0` |
| `is defined` | Checks if a variable is defined (not undefined) | `isset($var)` |
| `is same as(value)` | Strict identity check (`===`) | `$a === $b` |
| `is none` / `is null` | Checks if a value is `null` | `is_null($value)` / `$value === null` |
| `is divisible by(n)` | Checks if a number is divisible by n | `$value % $n === 0` |
| `is constant('CONST')` | Checks if a value equals a PHP constant | `$value === constant('CONST')` |
| `is empty` | Checks if a value is null, false, `""`, or `[]` (also Countable/Traversable) | `empty($value)` *(with caveats)* |
| `is iterable` | Checks if a value can be iterated | `is_iterable($value)` |
| `is sequence` | Checks if a value is a list-indexed array (sequential keys) | `is_array($v) && array_is_list($v)` |
| `is mapping` | Checks if a value is an associative array or object | `(is_array($v) && !array_is_list($v)) \|\| is_object($v)` |
| `is true` | Checks if a value is strictly `true` | `$value === true` |

---

## Operators (binary/unary — for reference)

| Twig Operator | Description | PHP Equivalent |
|---|---|---|
| `not` | Logical negation | `!$value` |
| `and` | Logical AND | `&&` |
| `or` | Logical OR | `\|\|` |
| `xor` | Logical XOR | `$a xor $b` |
| `b-and` / `b-or` / `b-xor` | Bitwise AND/OR/XOR | `&` / `\|` / `^` |
| `~` | String concatenation | `.` |
| `..` | Range (creates array) | `range($a, $b)` |
| `//` | Floor division | `(int)($a / $b)` / `intdiv($a, $b)` |
| `**` | Exponentiation | `$a ** $b` |
| `<=>` | Spaceship operator | `$a <=> $b` |
| `===` / `!==` | Strict equality/inequality | `$a === $b` / `$a !== $b` |
| `in` / `not in` | Membership test | `in_array($v, $arr)` |
| `matches` | Regex match | `preg_match($pattern, $str)` |
| `starts with` | String prefix check | `str_starts_with($str, $prefix)` |
| `ends with` | String suffix check | `str_ends_with($str, $suffix)` |
| `has some` | At least one element matches arrow fn | `Arr::first($arr, $fn) !== null` / `array_filter` check |
| `has every` | All elements match arrow fn | `count(array_filter($arr, $fn)) === count($arr)` |
| `?:` | Elvis operator (return self or default) | `$a ?: $b` |
| `??` | Null coalescing | `$a ?? $b` |
| `? :` | Ternary | `$a ? $b : $c` |
