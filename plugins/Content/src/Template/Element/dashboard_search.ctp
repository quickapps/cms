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

<div class="panel panel-default">
    <div class="panel-heading"><?= __d('content', 'Search'); ?></div>
    <div class="panel-body">
        <?= $this->Form->create(null, ['type' => 'get', 'role' => 'form', 'onsubmit' => 'doSearch(); return false;']); ?>
            <div class="input-group">
                <?= $this->Form->input('criteria', ['label' => false, 'required']); ?>
                <span class="input-group-btn">
                    <?= $this->Form->submit(__d('content', 'Go!')); ?>
                </span>
            </div>

            <p>
                <hr />
                <strong><?= __d('content', 'Advanced Search Options'); ?>:</strong>
                <ul>
                    <li><code>type:</code> <?= __d('content', "to specify the type contents (content type's machine-name). e.g.: type:article"); ?></li>
                    <li><code>author:</code> <?= __d('content', 'filter contents matching a given author name. e.g.: author:admin'); ?></li>
                    <li><code>language:</code> <?= __d('content', 'filter contents matching the given languages. e.g.: language:es,en_US'); ?></li>
                    <li><code>promote:</code> <?= __d('content', 'filter contents that were (or were not) promoted to front page. e.g.: promote:true, promote:false'); ?></li>
                    <li><code>created:</code> <?= __d('content', 'to specify when the contents were created. e.g.: created:2013..2014'); ?></li>
                    <li><code>modified:</code> <?= __d('content', 'to specify when the contents were modified. e.g.: modified:2011..2012'); ?></li>
                    <li><code>limit:</code> <?= __d('content', 'limits the number of items of search result. e.g.: limit:10'); ?></li>
                </ul>
            </p>
        <?= $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    function doSearch () {
        if ($('#criteria').val()) {
            $(location).attr('href',
                '<?= $this->Url->build('/find/'); ?>' + decodeURIComponent($('#criteria').val())
            );
        }
    }
</script>