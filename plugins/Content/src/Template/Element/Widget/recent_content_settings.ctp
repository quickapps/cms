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

<?=
    $this->Form->input('filter_criteria', [
        'type' => 'text',
        'label' => __d('contents', 'Filter criteria')
    ]);
?>
 <em class="help-block"><?= __d('contents', 'Search criteria which determinates Contents that will be present in the list. e.g. "type:articles" if you want to list latest Articles.'); ?></em>

<?=
    $this->Form->input('limit', [
        'type' => 'number',
        'label' => __d('contents', 'Size of the list')
    ]);
?>
