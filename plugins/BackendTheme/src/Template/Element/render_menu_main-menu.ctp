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

/**
 * Renders menus assigned to `main-menu` region.
 */
echo $this->Menu->render($menu->links, [
    'id' => 'side-menu',
    'class' => 'nav',
    'beautify' => true,
    'templates' => [
        'link' => '<a href="{{url}}"{{attrs}}>{{content}}</a>'
    ],
    'formatter' => function ($item, $info) {
        $options = [];
        if (!empty($info['children'])) {
            $info['children'] = str_replace('<ul class=""', '<ul class="nav nav-second-level"', $info['children']);
            $options['templates']['link'] = '<a href="{{url}}"{{attrs}}>{{content}}<span class="fa arrow"></span></a>';
        }
        return $this->Menu->formatter($item, $info, $options);
    }
]);