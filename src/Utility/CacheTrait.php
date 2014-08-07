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
 * Adds "_cache()" static method for reading and writing simple cache.
 *
 */
trait CacheTrait {

/**
 * Used internally to optimize some methods.
 *
 * @var array
 */
	protected static $_cache;

/**
 * Reads or writes internal class cache.
 *
 * NOTES:
 * 
 * - When reading if no cache key is found NULL will be returned.
 * - When writing, this method return the value that was written.
 * 
 * @param string $key Cache key to read or write
 * @param mixed $value Values to write into the given $key, or null indicates reading from cache
 * @return mixed
 */
	protected static function _cache($key, $value = null) {
		if ($value === null) {
			if (isset(static::$_cache[$key])) {
				return static::$_cache[$key];
			}

			return null;
		}

		static::$_cache[$key] = $value;
		return $value;
	}

}
