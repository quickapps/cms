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

use Cake\ORM\TableRegistry;

$vocabularies = TableRegistry::get('Taxonomy.Vocabularies')
    ->find('list')
    ->toArray();
?>

<?php if (!$vocabularies): ?>
    <div class="alert alert-danger" role="alert">
        <em>
            <?php echo __d('taxonomy', 'There are no vocabularies yet, please <a href="{0}">create at least one</a> to use this block.', $this->Url->build('/admin/taxonomy/vocabularies')); ?>
        </em>
    </div>
<?php else: ?>
    <?php
        echo $this->Form->input('vocabularies', [
            'type' => 'select',
            'multiple' => 'checkbox',
            'options' => $vocabularies,
            'label' => __d('taxonomy', 'Vocabularies')
        ]);
    ?>
     <em class="help-block"><?php echo __d('taxonomy', 'Show terms within selected vocabularies.'); ?></em>

    <hr />

    <?php
        echo $this->Form->input('show_counters', [
            'type' => 'checkbox',
            'options' => [
                0 => __d('taxonomy', 'No'),
                1 => __d('taxonomy', 'Yes')
            ],
            'label' => __d('taxonomy', 'Show content count')
        ]);
    ?>
    <?php
        echo $this->Form->input('show_vocabulary', [
            'type' => 'checkbox',
            'options' => [
                0 => __d('taxonomy', 'No'),
                1 => __d('taxonomy', 'Yes')
            ],
            'label' => __d('taxonomy', 'Show vocabulary and its terms as tree')
        ]);
    ?>
    {no_shortcode}
    <?php
        echo $this->Form->input('link_template', [
            'type' => 'text',
            'label' => __d('taxonomy', 'Link template')
        ]);
    ?>
    <em class="help-block"><?php echo __d('taxonomy', 'Template to use when rendering each link of the list. Valid placeholders are <code>{{url}}</code>, <code>{{attrs}}</code> and <code>{{content}}</code>. If not provided defaults to: <code>&lt;a href="{{url}}"{{attrs}}&gt;&lt;span&gt;{{content}}&lt;/span&gt;&lt;/a&gt;</code>'); ?></em>
    {/no_shortcode}
<?php endif; ?>


