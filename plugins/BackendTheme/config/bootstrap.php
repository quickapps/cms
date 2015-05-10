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
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

/**
 * Sets default jQueryUI theme to use.
 */
Configure::write('jQueryUI.defaultTheme', 'Jquery.flick');

/**
 * Used to count pending comments.
 */
Cache::config('pending_comments', [
    'className' => 'File',
    'prefix' => 'qa_',
    'path' => CACHE,
    'duration' => '+3 minutes',
]);

if (!function_exists('backendLayoutVars')) {
    /**
     * Prepares some variables used in "default.ctp" layout, such as skin color to use,
     * pending comments counter, etc.
     *
     * @return array Associative array
     */
    function backendLayoutVars()
    {
        $layoutOptions = [];
        $skin = theme()->settings['skin'];
        $boxClass = 'success';

        $pendingComments = Cache::read('pending_comments', 'pending_comments');
        if ($pendingComments === false) {
            $pendingComments = TableRegistry::get('Comment.Comments')
                ->find()->where(['Comments.status' => 'pending', 'Comments.table_alias' => 'contents'])
                ->count();
            Cache::write('pending_comments', $pendingComments, 'pending_comments');
        }
        $pendingComments = !$pendingComments ? '' : $pendingComments;

        if (strpos($skin, 'blue') !== false || strpos($skin, 'black') !== false) {
            $boxClass = 'info';
        } elseif (strpos($skin, 'green') !== false) {
            $boxClass = 'success';
        } elseif (strpos($skin, 'red') !== false || strpos($skin, 'purple') !== false) {
            $boxClass = 'danger';
        } elseif (strpos($skin, 'yellow') !== false) {
            $boxClass = 'warning';
        }

        if (theme()->settings['fixed_layout']) {
            $layoutOptions[] = 'fixed';
        }
        if (theme()->settings['boxed_layout']) {
            $layoutOptions[] = 'layout-boxed';
        }
        if (theme()->settings['collapsed_sidebar']) {
            $layoutOptions[] = 'sidebar-collapse';
        }

        return compact('skin', 'layoutOptions', 'boxClass', 'pendingComments');
    }
}
