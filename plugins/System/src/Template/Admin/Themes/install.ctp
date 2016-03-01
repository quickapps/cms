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
        <?= $this->Form->create(null, ['type' => 'file']); ?>
        <fieldset>
            <legend><?= __d('system', 'Upload Theme Package'); ?></legend>
            <?= $this->Form->input('file', ['type' => 'file', 'label' => __d('system', 'Upload ZIP package')]); ?>
            <?= $this->Form->submit(__d('system', 'Upload package'), ['name' => 'upload']); ?>
        </fieldset>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null); ?>
        <fieldset>
            <legend><?= __d('system', 'Download Theme Package'); ?></legend>
            <?= $this->Form->input('url', ['type' => 'text', 'label' => __d('system', 'Download ZIP package from URL')]); ?>
            <?= $this->Form->submit(__d('system', 'Install from URL'), ['name' => 'download']); ?>
        </fieldset>
        <?= $this->Form->end(); ?>

        <?= $this->Form->create(null); ?>
        <fieldset>
            <legend><?= __d('system', 'Use Server Path'); ?></legend>
                <?=
                    $this->Form->input('path', [
                        'label' => __d('system', 'Server directory or ZIP file'),
                        'placeholder' => __d('system', '/example/path/to/theme.zip'),
                        'value' => !empty($this->request->query['directory']) ? $this->request->query['directory'] : null,
                        'class' => 'from-directory',
                    ]);
                ?>
                <?= $this->Form->submit(__d('system', 'Install from File System'), ['name' => 'file_system']); ?>
        </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>

<?php if (!empty($this->request->query['directory'])): ?>
<script type="text/javascript">
    $('input.from-directory').focus();
</script>
<?php endif; ?>
