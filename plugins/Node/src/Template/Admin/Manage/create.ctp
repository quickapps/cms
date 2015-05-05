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
        <?php echo $this->element('Node.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
            echo $this->Menu->render($types, [
                'class' => 'list-group',
                'formatter' => function ($nodeType, $info) {
                    if (!$nodeType->userAllowed('create')) {
                        return false;
                    }
                    $content = '<h4 class="list-group-item-heading">';
                        $content .= $nodeType->name;
                    $content .= '</h4>';
                    $content .= '<p class="list-group-item-text">';
                        $content .= !empty($nodeType->description) ? $nodeType->description : __d('node', '(no description)');
                    $content .= '</p>';

                    return
                        $this->Html->link($content, [
                            'plugin' => 'Node',
                            'controller' => 'manage',
                            'action' => 'add',
                            'prefix' => 'admin',
                            $nodeType->slug
                        ], ['class' => 'list-group-item', 'escape' => false]);
                }
            ]);
        ?>
    </div>
</div>
