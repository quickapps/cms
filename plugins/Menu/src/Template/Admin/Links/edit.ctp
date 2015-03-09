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

<?php echo $this->Form->create($link); ?>
    <fieldset>
        <legend><?php echo __d('menu', "Editing Menu's Link"); ?></legend>
        <?php echo $this->Form->input('title', ['label' => __d('menu', "Link's title *")]); ?>
        <em class="help-block"><?php echo __d('menu', 'The text to be used for this link in the menu.'); ?></em>

        <?php if ($link->menu->handler === 'Menu'): ?>
            <?php echo $this->Form->input('url', ['label' => __d('menu', 'URL'), 'class' => 'link-url']); ?>
            <?php echo $this->Form->input('content_link', ['type' => 'select', 'label' => __d('menu', 'Link to content'), 'options' => $contentLinks, 'value' => $link->url, 'empty' => true, 'onchange' => "$('.link-url').val(this.value);"]); ?>
            <em class="help-block"><?php echo __d('menu', 'The path for this menu link. This can be an internal QuickApps CMS path such as "/article/my-first-article.html" or an external URL such as http://quickappscms.org. Enter "/" to link to the front page. You can easily link to an existing content using the "Link to content" option above.'); ?></em>

            <hr />
        <?php else: ?>
            <?php echo $this->Form->label(null, __d('menu', 'URL')); ?>
            <p><?php echo $this->Html->link($link->title, ($link->url ? $link->url : '#'), ['target' => '_blank']); ?></p>
        <?php endif; ?>

        <?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('menu', 'Enabled')]); ?>
        <em class="help-block"><?php echo __d('menu', 'Links that are not enabled will not be listed in any menu.'); ?></em>

        <?php echo $this->Form->input('description', ['label' => __d('menu', 'Description')]); ?>
        <em class="help-block"><?php echo __d('menu', 'Shown when hovering over the menu link.'); ?></em>
        <?php
            echo $this->Form->input('target', [
                'label' => __d('menu', 'Target window/tab'),
                'type' => 'select',
                'options' => [
                    '_self' => __d('menu', 'Opens in the same frame as it was clicked.'),
                    '_blank' => __d('menu', 'Opens in a new window or tab.'),
                    '_parent' => __d('menu', 'Opens in the parent frame.'),
                    '_top' => __d('menu', 'Opens in the full body of the window.'),
                ],
                'empty' => __d('menu', '(Automatic)'),
            ]);
        ?>
        <em class="help-block"><?php echo __d('menu', "Target browser's window when the link is clicked."); ?></em>

        <?php
            echo $this->Form->input('expanded', [
                'type' => 'select',
                'label' => __d('menu', 'Show as expanded'),
                'options' => [
                    1 => __d('menu', 'Expand, show children if it has.'),
                    0 => __d('menu', 'Do not expand, hide children.'),
                ]
            ]);
        ?>

        <fieldset>
            <legend><?php echo __d('menu', 'Link Activation'); ?></legend>
            <em class="help-block"><?php echo __d('menu', 'Choose a method to determinate when this link should be marked as "active".'); ?></em>
            
            <?php
                echo $this->Form->radio('activation', [
                    'auto' => __d('menu', 'Automatic, let application decide.'),
                    'any' => __d('menu', "When visitor's URL match ANY of those listed."),
                    'none' => __d('menu', "When visitor's URL match NONE of those listed."),
                    'php' => __d('menu', 'When following PHP code returns TRUE (experts only).'),
                ]);
            ?>

            <?php echo $this->Form->input('active', ['type' => 'textarea', 'label' => false]); ?>
            <em class="help-block"><?php echo __d('menu', 'Specify pages by using their paths, enter one path per line. The <code>*</code> character is a wildcard. <code>/</code> is the front page.'); ?></em>
            <em class="help-block"><?php echo __d('menu', 'If the PHP option is chosen, enter PHP code between <code>&lt;?php ... ?&gt;</code> tags. Examples:'); ?></em>
            <em class="help-block"><?php echo __d('menu', '<code>/product/*.html</code> Matches any product page.'); ?></em>
            <em class="help-block"><?php echo __d('menu', '<code>/find/*type:article*</code> Matches any search result containing articles.'); ?></em>
            <em class="help-block"><?php echo __d('menu', "<code>/</code> Matches site's front page (a.k.a. site's index)."); ?></em>
        </fieldset>

        <?php echo $this->Form->submit(__d('menu', 'Save Changes')); ?>
    </fieldset>
<?php echo $this->Form->end(); ?>