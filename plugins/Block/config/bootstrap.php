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
use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;

if (!function_exists('registerWidget')) {
    /**
     * Shortcut for registering widget blocks in the system.
     *
     * ### Usage:
     *
     * ```php
     * if (registerWidget($data)) {
     *     // successfully registered
     * } else {
     *     // something went wrong
     * }
     * ```
     *
     * Returning error messages:
     *
     * ```php
     * $errors = registerWidget($data, true);
     * if (empty($errors)) {
     *     // successfully registered
     * } else {
     *     // something went wrong
     *     debug($errors);
     * }
     * ```
     *
     * @param array $data Widget information (title, description, etc)
     * @param bool $returnErrors Whether to return an array of errors (empty on
     *  success) or a boolean response
     * @return bool|array True or empty array on success; false or array of errors
     *  on failure. Type of return depending on $returnErrors
     */
    function registerWidget(array $data, $returnErrors = false)
    {
        $widget = TableRegistry::get('Block.Blocks')->newEntity($data, ['validate' => 'widget']);
        $success = TableRegistry::get('Block.Blocks')->save($widget);

        if (!$returnErrors) {
            return $success;
        }

        return $widget->errors();
    }
}

/**
 * Used to speed up blocks rendering.
 */
Cache::config('blocks', [
    'className' => 'File',
    'prefix' => 'blocks_in_',
    'path' => CACHE,
    'duration' => '+2 hours',
    'groups' => ['views']
]);
