<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Utility;

use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\ORM\Entity;

/**
 * Serialized type converter.
 *
 * Information is stored in database as serialized arrays,
 * then it's converted to Entity object for easily integration with FormHelper.
 */
class SerializedType extends Type {

/**
 * Converts the stored array to an Entity object.
 *
 * Information is always stored as serialized arrays, but it's converted back to PHP
 * as Entity objects. This allows an easy integration with FormHelper.
 * 
 * @param string $value The serialized array to be converted
 * @param \Cake\Database\Driver $driver
 * @return \Cake\ORM\Entity
 */
	public function toPHP($value, Driver $driver) {
		//@codingStandardsIgnoreStart
		$value = @unserialize($value);
		//@codingStandardsIgnoreEnd

		$value = !is_array($value) ? [] : $value;
		return new Entity($value);
	}

/**
 * Stores information as serialized array.
 *
 * You can provide both an array, or an object implementing the `toArray()` method.
 * 
 * @param array|object $value An array of values to be stored, or an object implementing the `toArray()`
 * method (e.g. Entities)
 * @param \Cake\Database\Driver $driver
 * @return string
 */
	public function toDatabase($value, Driver $driver) {
		if (is_object($value) && method_exists($value, 'toArray')) {
			$value = $value->toArray();
		} elseif (!is_array($value)) {
			$value = [];
		}

		//@codingStandardsIgnoreStart
		$value = @serialize($value);
		//@codingStandardsIgnoreEnd

		return (string)$value;
	}

}
