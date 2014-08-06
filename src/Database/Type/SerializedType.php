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
namespace QuickApps\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\ORM\Entity;

/**
 * Serialized type converter.
 *
 * Serialize data, if needed. Arrays and object are automatically serialized
 * to be stored in DB.
 */
class SerializedType extends Type {

/**
 * Deserialize the stored information.
 * 
 * @param string $value The serialized element to deserialize
 * @param \Cake\Database\Driver $driver
 * @return mixed
 */
	public function toPHP($value, Driver $driver) {
		//@codingStandardsIgnoreStart
		$unserialized = @unserialize($value);
		//@codingStandardsIgnoreEnd

		if (!$unserialized) {
			return $value;
		}

		return $unserialized;
	}

/**
 * Serializes the information to be stored in DB.
 * 
 * @param mixed $value Array or object to be serialized, any other type will not be serialized
 * @param \Cake\Database\Driver $driver
 * @return string
 */
	public function toDatabase($value, Driver $driver) {
		if (is_array($value) || is_object($value)) {
			return serialize($value);
		}

		return (string)$value;
	}

}
