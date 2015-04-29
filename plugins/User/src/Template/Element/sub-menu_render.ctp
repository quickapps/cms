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

$links = [];
if ($this->request->isUserAdmin()) {
    $links[] = ['title' => __d('user', 'Administer Site'), 'url' => '/admin'];
}

if (!$this->request->isUserLoggedIn()) {
    $links[] = ['title' => __d('user', 'Sign in'), 'url' => '/login'];
} else{
    $links[] = ['title' => __d('user', 'My account'), 'url' => '/user/me'];
    $links[] = ['title' => __d('user', 'Sign out'), 'url' => '/logout'];
}

echo $this->Menu->render($links, ['id' => 'user-submenu', 'class' => 'dropdown-menu', 'role' => 'menu']);
