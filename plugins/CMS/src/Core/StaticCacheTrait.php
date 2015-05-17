<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace CMS\Core;

/**
 * Provides simple cache functionality.
 *
 * Allows classes to optimize their methods by providing a simple `cache()` static
 * method for reading and writing values.
 */
trait StaticCacheTrait
{

    /**
     * Used internally.
     *
     * @var array
     */
    protected static $_cache;

    /**
     * Reads, writes or search internal class's cache.
     *
     * ### Usages:
     *
     * - When reading if no cache key is found NULL will be returned.
     *   e.g. `$null = static::cache('invalid-key');`
     * - When writing, this method return the value that was written.
     *   e.g. `$value = static::cache('key', 'value');`
     * - Set both arguments to NULL to read the whole cache content at the moment.
     *   e.g. `$allCache = static::cache()`
     * - Set key to null and value to anything to find the first key holding the
     *   given value. e.g. `$key = static::cache(null, 'search key for this value')`,
     *   if no key for the given value is found NULL will be returned.
     *
     * ### Examples:
     *
     * #### Writing cache:
     *
     * ```php
     * static::cache('user_name', 'John');
     * // returns 'John'
     *
     * static::cache('user_last', 'Locke');
     * // returns 'Locke'
     * ```
     *
     * #### Reading cache:
     *
     * ```php
     * static::cache('user_name');
     * // returns: John
     *
     * static::cache('unexisting_key');
     * // returns: null
     *
     * static::cache();
     * // Reads the entire cache
     * // returns: ['user_name' => 'John', 'user_last' => 'Locke']
     * ```
     *
     * #### Searching keys:
     *
     * ```php
     * static::cache(null, 'Locke');
     * // returns: user_last
     *
     * static::cache(null, 'Unexisting Value');
     * // returns: null
     * ```
     *
     * @param null|string $key Cache key to read or write, set both $key and $value
     *  to get the whole cache information
     * @param mixed $value Values to write into the given $key, or null indicates
     *  reading from cache
     * @return mixed
     */
    public static function cache($key = null, $value = null)
    {
        if ($key === null && $value === null) {
            // read all
            return static::$_cache;
        } elseif ($key !== null && $value === null) {
            // read key
            if (isset(static::$_cache[$key])) {
                return static::$_cache[$key];
            }
            return null;
        }
        if ($key !== null && $value !== null) {
            // write key
            static::$_cache[$key] = $value;
            return $value;
        } else {
            // search key for given value
            if (!empty(static::$_cache)) {
                foreach (static::$_cache as $k => $v) {
                    if ($v === $value) {
                        return $k;
                    }
                }
            }
            return null;
        }
    }

    /**
     * Drops the entire cache or a specific key.
     *
     * ## Usage:
     *
     * ```php
     * static::dropCache('user_cache'); // removes "user_cache" only
     * static::dropCache(); // removes every key
     * ```
     *
     * @param string|null $key Cache key to clear, if NULL the entire cache
     *  will be erased.
     * @return void
     */
    public static function dropCache($key = null)
    {
        if ($key !== null && isset(static::$_cache[$key])) {
            unset(static::$_cache[$key]);
        } else {
            static::$_cache = [];
        }
    }
}
