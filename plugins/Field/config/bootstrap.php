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

use QuickApps\Event\HookManager;

if (!function_exists('fieldsInfo')) {
    /**
     * Gets a collection of information of every registered field in the system., or
     * information for a particular field.
     *
     * Some fields may register themselves as hidden when they are intended to be
     * used exclusively by plugins. So users can not `attach` them to entities using
     * Field UI.
     *
     * ### Usage:
     *
     * ```php
     * $visibleOnly = fieldsInfo()->filter(function ($info) {
     *     return !$info['hidden'];
     * });
     * ```
     *
     * @param string|null $field Field for which get its information as an array, or
     *  null (default) to get a all of them as a collection.
     * @return \Cake\Collection\Collection|array A collection of fields information
     */
    function fieldsInfo($field = null)
    {
        $fields = [];
        foreach (listeners() as $listener) {
            if (strpos($listener, 'Field.') === 0 &&
                str_ends_with($listener, '.Instance.info')
            ) {
                $handler = explode('.', $listener)[1];
                $response = array_merge([
                    'type' => 'varchar',
                    'name' => null,
                    'description' => null,
                    'hidden' => false,
                    'handler' => $handler,
                    'maxInstances' => 0,
                    'searchable' => true,
                ], (array)HookManager::trigger($listener)->result);
                $fields[$handler] = $response;
            }
        }

        if ($field === null) {
            return collection(array_values($fields));
        }

        if (isset($fields[$field])) {
            return $fields[$field];
        }

        throw new \Exception(__d('field', 'The field handler "{0}" was not found.', $field));
    }
}
