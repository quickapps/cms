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
use Taxonomy\Utility\TaxonomyToolbox;

$menuOptions = [];
if ($block->settings['link_template']) {
    $menuOptions['templates'] = ['link' => $block->settings['link_template']];
}

$vocabularyIds = (array)$block->settings['vocabularies'];
$vocabularyIds = empty($vocabularyIds) ? [-1] : $vocabularyIds;
$vocabularies = TableRegistry::get('Taxonomy.Vocabularies')
    ->find()
    ->where(['Vocabularies.id IN' => $vocabularyIds]);
?>

<h2><?= $block->title; ?></h2>
<?php if ($block->settings['show_vocabulary']): ?>
    <ul>
        <?php
            foreach ($vocabularies as $vocabulary) {
                echo "<li>{$vocabulary->name}";

                $terms = TableRegistry::get('Taxonomy.Terms')
                    ->find('threaded')
                    ->where(['Terms.vocabulary_id' => $vocabulary->id])
                    ->order(['Terms.lft' => 'ASC']);

                TaxonomyToolbox::termsForBlock($terms, $block);
                if ($terms) {
                    echo $this->Menu->render($terms, $menuOptions);
                }
                echo "</li>";
            }
        ?>
    </ul>
<?php else: ?>
    <?php
        $terms = TableRegistry::get('Taxonomy.Terms')
            ->find('threaded')
            ->where(['Terms.vocabulary_id IN' => $vocabularyIds])
            ->order(['Terms.lft' => 'ASC']);

        TaxonomyToolbox::termsForBlock($terms, $block);

        if ($terms) {
            echo $this->Menu->render($terms, $menuOptions);
        }
    ?>
<?php endif; ?>
