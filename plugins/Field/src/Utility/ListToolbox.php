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
namespace Field\Utility;

use Field\Model\Entity\Field;
use QuickApps\Event\HooktagAwareTrait;

/**
 * List utility class.
 *
 * Utility methods used by ListField Handler.
 */
class ListToolbox
{

    use HooktagAwareTrait;

    /**
     * Holds an instance of this class.
     *
     * @var \Field\Utility\ListToolbox
     */
    protected static $_instance = null;

    /**
     * Returns an instance of this class.
     *
     * Useful when we need to use some of the trait methods.
     *
     * @return \Field\Utility\ListToolbox
     */
    public static function getInstance()
    {
        if (!static::$_instance) {
            static::$_instance = new ListToolbox();
        }

        return static::$_instance;
    }

    /**
     * Formats the given field.
     *
     * @param \Field\Model\Entity\Field $field The field being rendered
     * @return string
     */
    public static function formatter(Field $field)
    {
        $result = '';
        $options = [];

        if (!empty($field->metadata->settings['options'])) {
            foreach (explode("\n", $field->metadata->settings['options']) as $option) {
                $option = explode('|', $option);
                $value = $option[0];
                $label = isset($option[1]) ? $option[1] : $option[0];
                $options[$value] = $label;
            }
        }

        if (is_string($field->extra)) {
            $selectedOptions = [$field->extra];
        } else {
            $selectedOptions = (array)$field->extra;
        }

        foreach ($selectedOptions as $key) {
            switch ($field->viewModeSettings['formatter']) {
                case 'key':
                    $result .= "{$key}<br />";
                    break;

                case 'default':
                default:
                    if (!empty($options[$key])) {
                        $result .= "{$options[$key]}<br />";
                    }
                    break;
            }
        }

        return $result;
    }
}
