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
    'title' => __d('comment', 'All'),
    'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action],
], [
    'title' => __d('comment', 'Pending') . ' <span class="badge">' . $pendingCounter  . '</span>',
    'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'pending'],
], [
    'title' => __d('comment', 'Approved') . ' <span class="badge">' . $approvedCounter  . '</span>',
    'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'approved'],
], [
    'title' => __d('comment', 'Spam') . ' <span class="badge">' . $spamCounter  . '</span>',
    'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'spam'],
], [
    'title' => __d('comment', 'Trash') . ' <span class="badge">' . $trashCounter  . '</span>',
    'url' => ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => $this->request->action, 'trash'],
]];
?>

<p><?= $this->Menu->render($menuItems, ['class' => 'nav nav-pills']); ?></p>