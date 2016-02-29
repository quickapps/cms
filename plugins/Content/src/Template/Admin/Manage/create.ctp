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
?>

<div class="row">
    <div class="col-md-12">
        <?= $this->element('Content.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?=
            $this->Menu->render($types, [
                'class' => 'list-group',
                'formatter' => function ($contentType, $info) {
                    if (!$contentType->userAllowed('create')) {
                        return false;
                    }
                    $content = '<h4 class="list-group-item-heading">';
                        $content .= $contentType->name;
                    $content .= '</h4>';
                    $content .= '<p class="list-group-item-text">';
                        $content .= !empty($contentType->description) ? $contentType->description : __d('content', '(no description)');
                    $content .= '</p>';

                    return
                        $this->Html->link($content, [
                            'plugin' => 'Content',
                            'controller' => 'manage',
                            'action' => 'add',
                            'prefix' => 'admin',
                            $contentType->slug
                        ], ['class' => 'list-group-item', 'escape' => false]);
                }
            ]);
        ?>
    </div>
</div>
