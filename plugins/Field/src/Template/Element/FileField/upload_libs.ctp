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

use Cake\Core\Configure;
?>

<?php if (!isset($this->viewVars['__FileFieldUploadLibs__'])): ?>
    <?php $this->viewVars['__FileFieldUploadLibs__'] = '__LOADED__'; ?>
    <?= $this->Html->css('Field.uploadify'); ?>
    <?= $this->Html->script('Jquery.jquery-ui.min.js'); ?>
    <?= $this->Html->script('System.mustache'); ?>
    <?= $this->Html->script('Field.FileField'); ?>
    <?= $this->Html->script('Field.uploadify/jquery.uploadify.min.js?' . time()); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            FileField.defaultSettings.uploader.mimeIconsBaseURL = '<?= $this->Url->build('/', true); ?>';
            FileField.defaultSettings.uploader.swf = '<?= $this->Url->build('/field/js/uploadify/uploadify.swf', true); ?>';
            FileField.defaultSettings.uploader.debug = <?= Configure::read('debug') ? 'true' : 'false'; ?>;
            FileField.defaultSettings.uploader.errorMessages = {
                504: '<?= __d('field', 'The file {{file.name}} could not be uploaded: invalid field instance.'); ?>',
                501: '<?= __d('field', 'The file {{file.name}} could not be uploaded: invalid file extension.'); ?>',
                502: '<?= __d('field', 'The file {{file.name}} could not be uploaded: internal server error.'); ?>',
            };
            FileField.defaultSettings.uploader.itemTemplate = '<div id="${fileID}" class="uploadify-queue-item">\
                <div class="cancel">\
                    <a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>\
                </div>\
                <em class="fileName help-block">${fileName} (${fileSize})</span><span class="data"></em>\
                <div class="uploadify-progress progress">\
                    <div class="uploadify-progress-bar progress-bar progress-bar-success progress-bar-striped active"><!--Progress Bar--></div>\
                </div>\
            </div>';

            $(window).on('beforeunload',function() {
                if ($('.file-handler .file-item').not('.is-perm').length > 0) {
                    return '<?= __d('field', 'Are you sure you want to leave this page?'); ?>';
                }
            });

            $('form').on('submit', function () {
                $(window).unbind('beforeunload');
            });
        });
    </script>

    <?= $this->element('Field.FileField/upload_item'); ?>
<?php endif; ?>
