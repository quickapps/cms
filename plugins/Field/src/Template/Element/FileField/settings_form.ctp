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

<?php echo $this->Form->input('extensions', ['type' => 'text', 'label' => __d('field', 'Allowed extensions')]); ?>
<em class="help-block"><?php echo __d('field', 'Comma separated, leave empty to allow any extension. e.g. jpg,gif,png'); ?></em>

<?php
	$ranges = [
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
		'7' => '7',
		'8' => '8',
		'9' => '9',
		'10' => '10',
		'custom' => __d('field', 'Custom')
	];
	echo $this->Form->input('multi', [
		'type' => 'select',
		'options' => $ranges,
		'label' => __d('field', 'Number of files'),
		'id' => 'multi-type',
		'onchange' => 'customMulti()'
	]);
?>
<em class="help-block"><?php echo __d('field', 'Maximum number of files users can upload for this field.'); ?></em>

<div class="custom-multi">
	<?php
		echo $this->Form->input('multi_custom', [
			'type' => 'text',
			'label' => __d('field', 'Customized number of files'),
			'onkeyup' => "if (/\D/g.test(this.value)) { this.value = this.value.replace(/\D/g,'') }",
		]);
	?>
</div>

<?php
	echo $this->Form->input('upload_folder', [
		'type' => 'text',
		'label' => __d('field', 'Upload folder'),
	]);
?>
<em class="help-block">
	<?php echo __d('field', 'Optional subdirectory where files will be stored.'); ?><br />
	<?php echo __d('field', 'The root directory is: <code>{0}</code>', normalizePath(SITE_ROOT . '/webroot/files/')); ?><br />
	<?php echo __d('field', 'For example, "my-subdirectory" will maps to <code>{0}my-subdirectory</code>', normalizePath(SITE_ROOT . '/webroot/files/')); ?>
</em>

<?php
	echo $this->Form->input('description', [
		'type' => 'checkbox',
		'label' => __d('field', 'Enable description field'),
	]);
?>
<em class="help-block"><?php echo __d('field', 'The description field allows users to enter a description about the uploaded file.'); ?></em>

<script>
	function customMulti() {
		if (isNaN($('#multi-type').val())) {
			$('.custom-multi').show();
		} else {
			$('.custom-multi').hide();
		}
	}

	$(document).ready(function () {
		customMulti();
	});
</script>
