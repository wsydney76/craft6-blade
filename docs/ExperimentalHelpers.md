# Experimental Helpers

Reference for all helpers in `src/Support/Experiments`, sorted alphabetically by helper name.

> Both the helpers and this doc are AI-generated, so may (will) contain errors.

| Function | Purpose | Replacement (PHP/Blade/Craft API)                                   |
|---|---|---------------------------------------------------------------------|
| `c6b_actionInput` | Render a hidden action input. | `Html::actionInput()`                                               |
| `c6b_actionUrl` | Generate an action URL. | `Url::actionUrl()`                                                  |
| `c6b_address` | Format an address element. | -                                                                   |
| `c6b_addresses` | Create an Address element query. | `Address::find()`                                                   |
| `c6b_alias` | Resolve an alias. | `Aliases::get()`                                                    |
| `c6b_append` | Append HTML inside a tag. | -                                                                   |
| `c6b_ascii` | Convert a string to ASCII. | `Str::ascii(...)`                                                   |
| `c6b_asset` | Generate a public asset URL (Laravel helper). | `{{ asset(...) }}`                                                  |
| `c6b_assets` | Create an Asset element query. | `Asset::find()`                                                     |
| `c6b_atom` | Format a date as an Atom timestamp. | -                                                                   |
| `c6b_attr` | Render tag attributes as an HTML string. | `Html::renderTagAttributes()`                                       |
| `c6b_beginBody` | Render registered begin-body tags. | `@craftBeginBody`                                                   |
| `c6b_camel` | Convert a string to camelCase. | `Str::camel(...)`                                                   |
| `c6b_canCreateDrafts` | Check whether the user can create drafts for an element. | -                                                                   |
| `c6b_canDeleteForSite` | Check whether the user can delete an element for the current site. | -                                                                   |
| `c6b_canDelete` | Check whether the user can delete an element. | -                                                                   |
| `c6b_canDuplicate` | Check whether the user can duplicate an element. | -                                                                   |
| `c6b_canSave` | Check whether the user can save an element. | -                                                                   |
| `c6b_canView` | Check whether the user can view an element. | -                                                                   |
| `c6b_clone` | Clone a value. | `clone $var`                                                        |
| `c6b_collect` | Create a Collection (or ElementCollection) from a value. | -                                                                   |
| `c6b_column` | Pluck a column of values from an array. | `Arr::pluck(...)`                                                   |
| `c6b_combine` | Create an array by combining keys and values. | `array_combine(...)`                                                |
| `c6b_configure` | Configure an object with the given attributes. | `Typecast::configure()`                                             |
| `c6b_contains` | Check whether an array contains a given value. | `Arr::contains(...)`                                                |
| `c6b_contentBlocks` | Create a ContentBlock element query. | `ContentBlock::find()`                                              |
| `c6b_cpUrl` | Generate a control panel URL. | `Url::cpUrl()`                                                      |
| `c6b_craftAsset` | Generate a Craft asset URL. | `craftAsset()`                                                      |
| `c6b_create` | Instantiate an object by class name or config array. | -                                                                   |
| `c6b_csrfInput` | Render a CSRF input. | `@csrf`                                                             |
| `c6b_currency` | Format a currency value. | -                                                                   |
| `c6b_dataUrl` | Generate a data URL for a file or asset. | -                                                                   |
| `c6b_dateConvert` | Convert a date value to a Carbon instance, optionally applying a timezone. | -                                                                   |
| `c6b_dateCreate` | Create a DateTimeInterface from a date value. | -                                                                   |
| `c6b_date` | Format a date value using the I18N formatter. | -                                                                   |
| `c6b_datetime` | Format a date+time value using the I18N formatter. | -                                                                   |
| `c6b_dd` | Dump and die. | `@dd(...)`                                                          |
| `c6b_default` | Returns the value if not empty, otherwise the default. | -                                                                   |
| `c6b_diff` | Return values in the first array not present in any subsequent arrays. | `array_diff(...)`                                                   |
| `c6b_dump` | Dump variables for debugging (HTML output). | `@dump(...)`                                                        |
| `c6b_duration` | Format a duration value as a human-readable string. | `DateTimeHelper::humanDuration()`                                   |
| `c6b_elements` | Create a generic Element query for the given element type. | -                                                                   |
| `c6b_encenc` | Encrypt and encode a string (enc-enc). | `Str::encenc(...)`                                                  |
| `c6b_encodeUrl` | Encode a URL. | `Url::encodeUrl($url)`                                              |
| `c6b_endBody` | Render registered end-body tags. | `@craftEndBody`                                                     |
| `c6b_entries` | Create an Entry element query. | `Entry::find()`                                                     |
| `c6b_entryType` | Get an entry type by handle. | -                                                                   |
| `c6b_explodeClass` | Explode a class attribute string into an array. | `Html::explodeClass()`                                              |
| `c6b_explodeStyle` | Explode a style attribute string into an array. | `Html::explodeStyle()`                                              |
| `c6b_expression` | Wrap a raw SQL expression. | `new Expression()`                                                  |
| `c6b_failMessageInput` | Render a hidden fail-message input. | `Html::failMessageInput()`                                          |
| `c6b_fieldValueSql` | Get the SQL expression for a field value. | -                                                                   |
| `c6b_filesize` | Format a filesize value. | -                                                                   |
| `c6b_filter` | Filter an array, optionally using a callback. | `array_filter(...)`                                                 |
| `c6b_firstWhere` | Return the first element matching the given key/value pair. | `collect(...)->firstWhere(...)`                                     |
| `c6b_flatten` | Flatten a multi-dimensional array. | `Arr::flatten(...)`                                                 |
| `c6b_getenv` | Get an environment variable value. | `Env::get()`                                                        |
| `c6b_gql` | Execute a GraphQL query. | -                                                                   |
| `c6b_group` | Group an array by a field name or callback. | -                                                                   |
| `c6b_h1` | Render an h1 heading tag. | -                                                                   |
| `c6b_h2` | Render an h2 heading tag. | -                                                                   |
| `c6b_h3` | Render an h3 heading tag. | -                                                                   |
| `c6b_h4` | Render an h4 heading tag. | -                                                                   |
| `c6b_h5` | Render an h5 heading tag. | -                                                                   |
| `c6b_h6` | Render an h6 heading tag. | -                                                                   |
| `c6b_h` | Render a heading tag (h1-h6). | -                                                                   |
| `c6b_hash` | Hash or encrypt a string. | `Crypt::encrypt(...)` / `hash(...)`                                 |
| `c6b_head` | Render registered head tags. | -                                                                   |
| `c6b_heading` | Alias for c6b_h(). | -                                                                   |
| `c6b_hiddenInput` | Render a hidden input. | `Html::hiddenInput()`                                               |
| `c6b_htmlId` | Normalize an element ID. | `Html::id()`                                                        |
| `c6b_httpdate` | Format a date as an HTTP date (RFC 7231). | -                                                                   |
| `c6b_indexOf` | Return the index of the first occurrence of a value in a string or array. | -                                                                   |
| `c6b_input` | Render an input element. | `Html::input()`                                                     |
| `c6b_intersect` | Return values present in all given arrays. | `array_intersect(...)`                                              |
| `c6b_jsonDecode` | JSON-decode a string. | `Json::decode()`                                                    |
| `c6b_jsonEncode` | JSON-encode a value with safe defaults. | `@json(...)`                                                        |
| `c6b_kebab` | Convert a string to kebab-case. | -                                                                   |
| `c6b_lcfirst` | Lowercase the first character of a string. | `mb_lcfirst(...)`                                                   |
| `c6b_literal` | Escape a query param value so it is treated as a literal string. | `Query::escapeParam()`                                              |
| `c6b_map` | Apply a callback to every element of an array. | `array_map(...)`                                                    |
| `c6b_merge` | Merge two arrays together. | -                                                                   |
| `c6b_modifyAttr` | Modify attributes on a tag. | -                                                                   |
| `c6b_money` | Format a Money object as a string. | -                                                                   |
| `c6b_multisort` | Sort an array by one or more keys. | -                                                                   |
| `c6b_namespaceAttributes` | Namespace HTML attributes. | `new HtmlString(Html::namespaceAttributes(...))`                    |
| `c6b_namespaceId` | Namespace an element ID. | `app(InputNamespace::class)->namespaceId(...)`                      |
| `c6b_namespaceInputName` | Namespace an input name. | `app(InputNamespace::class)->namespaceInputName(...)`               |
| `c6b_namespaceInputs` | Namespace HTML inputs. | `new HtmlString(app(InputNamespace::class)->namespaceInputs(...))`  |
| `c6b_normalizeDateFormat` | Normalize a date format string (icu:/php: prefix handling). | -                                                                   |
| `c6b_ns` | Namespace HTML inputs (alias for c6b_namespaceInputs). | `c6b_namespaceInputs($html, $namespace)`                            |
| `c6b_number` | Format a number. | -                                                                   |
| `c6b_ol` | Render an ordered list. | `new HtmlString(Html::ol($items, $options))`                        |
| `c6b_parseAttr` | Parse a tag's attributes into an array. | -                                                                   |
| `c6b_parseBooleanEnv` | Parse a boolean environment variable. | `Env::parseBoolean($str)`                                           |
| `c6b_parseEnv` | Parse an environment variable string. | `Env::parse($str)`                                                  |
| `c6b_parseRefs` | Parse element references in a string. | -                                                                   |
| `c6b_pascal` | Convert a string to PascalCase. | `Str::pascal(...)`                                                  |
| `c6b_percentage` | Format a value as a percentage. | -                                                                   |
| `c6b_plugin` | Get a plugin instance by handle. | `app(Plugins::class)->getPlugin($handle)`                           |
| `c6b_prepend` | Prepend HTML inside a tag. | -                                                                   |
| `c6b_push` | Append one or more values to an array. | -                                                                   |
| `c6b_randomString` | Generate a random string. | `Str::random($length)`                                              |
| `c6b_raw` | Mark a string as raw/safe HTML. | `new HtmlString(TemplateHelper::raw($value))`                       |
| `c6b_redirectInput` | Render a hidden redirect input. | `new HtmlString(Html::redirectInput($url, $options))`               |
| `c6b_reduce` | Iteratively reduce an array to a single value using a callback. | -                                                                   |
| `c6b_removeClass` | Remove a CSS class from a tag. | -                                                                   |
| `c6b_renderObjectTemplate` | Render an object template string. | `renderObjectTemplate($template, $object)`                          |
| `c6b_replace` | Replace occurrences in a string, supporting regex patterns. | -                                                                   |
| `c6b_rss` | Format a date as an RSS timestamp. | `c6b_dateConvert($date, $timezone)->format(DateTimeInterface::RSS)` |
| `c6b_seq` | Get the next (or current) value of a named sequence. | -                                                                   |
| `c6b_shuffle` | Shuffle an array and return the result. | -                                                                   |
| `c6b_siteUrl` | Generate a site URL. | `Url::siteUrl(...)`                                                 |
| `c6b_snake` | Convert a string to snake_case. | `Str::snake(...)`                                                   |
| `c6b_sort` | Sort an array, optionally using a callback. | -                                                                   |
| `c6b_successMessageInput` | Render a hidden success-message input. | `new HtmlString(Html::successMessageInput(...))`                    |
| `c6b_svg` | Render an inline SVG. | -                                                                   |
| `c6b_time` | Format a time value using the I18N formatter. | -                                                                   |
| `c6b_timestamp` | Format a date as a relative timestamp. | -                                                                   |
| `c6b_ul` | Render an unordered list. | `new HtmlString(Html::ul($items, $options))`                        |
| `c6b_unique` | Remove duplicate values from an array. | `array_unique($array, $flags)`                                      |
| `c6b_unshift` | Prepend one or more values to the beginning of an array. | -                                                                   |
| `c6b_url` | Generate a URL. | `{{ url(...) }}`                                                    |
| `c6b_users` | Create a User element query. | `new UserQuery($config)`                                            |
| `c6b_uuid7` | Generate a UUID v7. | `(string) Str::uuid7()`                                             |
| `c6b_uuid` | Generate a UUID v4. | `(string) Str::uuid()`                                              |
| `c6b_values` | Return the values of an array (re-indexed). | `array_values($array)`                                              |
| `c6b_where` | Filter an array by a key/value condition. | `Arr::where($array, $key, $value)`                                  |
| `c6b_widont` | Prevent widows in a string by replacing the last space with a non-breaking space. | `new HtmlString(Html::widont($string))`                             |
| `c6b_withoutKey` | Return an array without the specified key(s). | -                                                                   |
| `c6b_without` | Return an array without the specified value(s). | `Collection::make($arr)->reject(...)->all()`                        |
