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

$menuItems = [[
    'title' => '<span class="glyphicon glyphicon-file"></span> ' . __d('content', 'New Content'),
    'url' => '/admin/content/manage/create',
    'activation' => 'any',
    'active' => '/admin/content/manage/create*',
], [
    'title' => '<span class="glyphicon glyphicon-cog"></span> ' . __d('content', 'Content Types'),
    'url' => '/admin/content/types',
    'activation' => 'any',
    'active' => '/admin/content/types*',
], [
    'title' => '<span class="glyphicon glyphicon-comment"></span> ' . __d('content', 'Comments'),
    'url' => '/admin/content/comments/',
    'activation' => 'any',
    'active' => '/admin/content/comments*',
]];
?>

<p><?php echo $this->Menu->render($menuItems, ['class' => 'nav nav-pills']); ?></p>