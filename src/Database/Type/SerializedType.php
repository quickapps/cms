<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
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
class SerializedType extends Type
{

    /**
     * Deserialize the stored information if it was serialized before.
     *
     * @param string $value The serialized element to deserialize
     * @param \Cake\Database\Driver $driver Database connection driver
     * @return mixed
     */
    public function toPHP($value, Driver $driver)
    {
        if ($this->_isSerialized($value)) {
            //@codingStandardsIgnoreStart
            $value = @unserialize($value);
            //@codingStandardsIgnoreEnd
        }

        return $value;
    }

    /**
     * Serializes (if it can) the information to be stored in DB.
     *
     * Arrays and object are serialized, any other type of information will be
     * stored as plain text.
     *
     * @param mixed $value Array or object to be serialized, any other type will
     *  not be serialized
     * @param \Cake\Database\Driver $driver Database connection driver
     * @return string
     */
    public function toDatabase($value, Driver $driver)
    {
        if (is_array($value) || is_object($value)) {
            return serialize($value);
        }

        return (string)$value;
    }

    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @param mixed $data Value to check to see if was serialized
     * @return bool False if not serialized and true if it was
     * @author WordPress
     */
    protected function _isSerialized($data)
    {
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ($data == 'N;') {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if ($data[1] !== ':') {
            return false;
        }
        $lastc = substr($data, -1);
        if ($lastc !== ';' && $lastc !== '}') {
            return false;
        }

        $token = $data[0];
        switch ($token) {
            case 's':
                if (substr($data, -2, 1) !== '"') {
                    return false;
                }
                // no break
            case 'a':
                // no break
            case 'O':
                return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
                // no break
            case 'b':
                // no break
            case 'i':
                // no break
            case 'd':
                return (bool)preg_match("/^{$token}:[0-9.E-]+;$/", $data);
                // no break
        }

        return false;
    }
}
