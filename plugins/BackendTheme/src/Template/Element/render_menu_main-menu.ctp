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
    'class' => 'sidebar-menu',
    'hasChildrenClass' => 'treeview',
    'breadcrumbGuessing' => true,
    'formatter' => function ($item, $info) {
        $options = [];
        if ($info['depth'] > 0) {
            $item->title = '<i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;' . $item->title;
        }
        if (!empty($info['children'])) {
            $info['children'] = str_replace('<ul class=""', '<ul class="treeview-menu"', $info['children']);
            $options['templates']['link'] = '<a href="{{url}}"{{attrs}}>{{content}} <i class="fa fa-angle-left pull-right"></i></a>';
        }
        return $this->Menu->formatter($item, $info, $options);
    }
]);