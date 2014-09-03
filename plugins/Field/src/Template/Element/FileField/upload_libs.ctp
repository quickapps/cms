<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php if (!static::cache('FileFieldUploadLibs')): ?>
	<?php echo $this->Html->css('Field.uploadify'); ?>
	<?php echo $this->Html->script('System.jquery-ui'); ?>
	<?php echo $this->Html->script('System.mustache'); ?>
	<?php echo $this->Html->script('Field.FileField'); ?>
	<?php echo $this->Html->script('Field.uploadify/jquery.uploadify.min.js?' . time()); ?>
	<?php static::cache('FileFieldUploadLibs', '__LOADED__'); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			FileField.baseUrl = '<?php echo $this->Url->build('/', true); ?>';
			FileField.swf = '<?php echo $this->Url->build('/field/js/uploadify/uploadify.swf', true); ?>';
			FileField.defaultErrorMessages = {
				400: '<?php echo __d('field', 'The file {{file.name}} could not be uploaded: invalid field instance.'); ?>',
				422: '<?php echo __d('field', 'The file {{file.name}} could not be uploaded: invalid file extension.'); ?>',
				500: '<?php echo __d('field', 'The file {{file.name}} could not be uploaded: internal server error.'); ?>',
			};
			FileField.defaultItemTempalte = '<div id="${fileID}" class="uploadify-queue-item">\
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
					return '<?php echo __d('field', 'Are you sure you want to leave this page?'); ?>';
				}
			});

			$('form').on('submit', function () {
				$(window).unbind('beforeunload');
			});
		});
	</script>

	<?php echo $this->element('Field.FileField/upload_item'); ?>
<?php endif; ?>
