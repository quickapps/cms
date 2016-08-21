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
namespace CMS\Aspect;

use Go\Aop\Aspect as GoAspect;

/**
 * Base Aspect. All aspects should extend this class.
 */
class Aspect implements GoAspect
{

    /**
     * Get property value from the given object, regardless its visibility.
     *
     * @param object $object The object
     * @param string $name Name of the property to get
     * @return mixed Property value
     */
    public function getProperty($object, $name)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Sets property value of the given object, regardless its visibility.
     *
     * @param object $object The object
     * @param string $propertyName Name of the property to set
     * @param mixed $value The new value
     * @return void
     */
    public function setProperty($object, $propertyName, $value)
    {
        $class = new \ReflectionClass($object);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
