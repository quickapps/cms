<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Utility;

use QuickApps\Utility\CacheTrait;

/**
 * Provides simple cache functionality.
 * 
 * Allows classes to optimize their methods by
 * providing a simple `_cache()` static method for reading and writing values.
 */
trait CacheTrait {

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
 * - When reading if no cache key is found NULL will be returned. e.g.: `$null = static::_cache('invalid-key');`
 * - When writing, this method return the value that was written. e.g.: `$value = static::_cache('key', 'value');`
 * - Set both arguments to NULL to read the whole cache content at the moment. e.g.: `$allCache = static::_cache()`
 * - Set key to null and value to anything to find the first key holding the given value. 
 *   e.g.: `$key = static::_cache(null, 'search key for this value')`, if no key for the given
 *   value is found NULL will be returned.
 * 
 * @param null|string $key Cache key to read or write, set both $key and $value to get the whole cache information
 * @param mixed $value Values to write into the given $key, or null indicates reading from cache
 * @return mixed
 */
	protected static function _cache($key = null, $value = null) {
		if ($key === null && $value === null) {
			// read all
			return static::$_cache;
		} elseif ($key !== null && $value === null) {
			// read key
			if (isset(static::$_cache[$key])) {
				return static::$_cache[$key];
			}
			return null;
		} if ($key !== null && $value !== null) {
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
 * Clears the entire cache.
 *
 * @return void
 */
	protected static function _clearCache() {
		static::$_cache = [];
	}

}
