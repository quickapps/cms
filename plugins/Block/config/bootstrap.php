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
     * @return bool True on success
     */
    function registerWidget(array $data)
    {
        $widget = TableRegistry::get('Block.Blocks')->newEntity($data, ['validate' => 'widget']);
        return TableRegistry::get('Block.Blocks')->save($widget);
    }
}

/**
 * Used to speed up blocks rendering.
 */
Cache::config('blocks', [
    'className' => 'File',
    'prefix' => 'qa_blocks_in_',
    'path' => CACHE,
    'duration' => '+2 hours',
    'groups' => ['views']
]);
