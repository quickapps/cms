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

use Field\Utility\ImageToolbox;
?>

<?php
	echo $this->Form->input('extensions', [
		'type' => 'text',
		'label' => __d('field', 'Allowed image extensions')
	]);
?>
<em class="help-block"><?php echo __d('field', 'Comma separated. e.g. jpg,gif,png'); ?></em>

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
		'label' => __d('field', 'Number of images'),
		'options' => $ranges,
		'onchange' => "if (isNaN(this.value)) { $('.custom-multi').show(); } else { $('.custom-multi').hide(); }"
	]);
?>
<em class="help-block"><?php echo __d('field', 'Maximum number of images users can upload for this field.'); ?></em>

<div class="custom-multi">
	<?php
		$this->Form->input('multi_custom', [
			'type' => 'text',
			'label' => __d('field', 'Customized number of files'),
			'onkeyup' => "if (/\D/g.test(this.value)) { this.value = this.value.replace(/\D/g,'') }"
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
	<?php echo __d('field', 'Optional subdirectory where images will be stored.'); ?><br />
	<?php echo __d('field', 'The root directory is: <code>{0}</code>', normalizePath(SITE_ROOT . '/webroot/files/')); ?><br />
	<?php echo __d('field', 'For example, "my-subdirectory" will maps to <code>{0}my-subdirectory</code>', normalizePath(SITE_ROOT . '/webroot/files/')); ?>
</em>

<?php
	echo $this->Form->input('title_attr', [
		'type' => 'checkbox',
		'label' => __d('field', 'Enable "title" attribute'),
	]);
?>
<em class="help-block"><?php echo __d('field', 'The title attribute is used as a tooltip when the mouse hovers over the image.'); ?></em>

<?php
	echo $this->Form->input('alt_attr', [
		'type' => 'checkbox',
		'label' => __d('field', 'Enable "alt" attribute'),
	]);
?>
<em class="help-block"><?php echo __d('field', 'The alt attribute may be used by search engines, screen readers, and when the image cannot be loaded.'); ?></em>

<?php 
	echo $this->Form->input('preview', [
		'type' => 'select',
		'label' => __d('field', 'Preview image style'),
		'options' => ImageToolbox::previewsOptions(),
		'empty' => __d('field', '-- No preview --'),
	]);
?>
<em class="help-block"><?php echo __d('field', 'The preview image will be shown while editing the content.'); ?></em>

<fieldset>
	<legend><?php echo __d('field', 'Image Restrictions'); ?></legend>

	<?php echo $this->Form->label('min_resolution', __d('field', 'Minimum image resolution')); ?>
	<div class="row">
		<div class="form-group col-md-2">
			<div class="input-group">
				<span class="input-group-addon"><?php echo __d('field', 'Width (px)'); ?></span>
				<?php echo $this->Form->input('min_width', ['label' => false, 'size' => 10]); ?>
			</div>
		</div>
		<div class="form-group col-md-2">
			<div class="input-group">
				<span class="input-group-addon"><?php echo __d('field', 'Height (px)'); ?></span>
				<?php echo $this->Form->input('min_height', ['label' => false, 'size' => 10]); ?>
			</div>
		</div>
	</div>
	<em class="help-block"><?php echo __d('field', 'The minimum allowed image size expressed as WIDTHxHEIGHT (e.g. 640x480). Leave blank for no restriction. If a smaller image is uploaded, it will be rejected.'); ?></em>

	<?php echo $this->Form->label('max_resolution', __d('field', 'Maximum image resolution')); ?>
	<div class="row">
		<div class="form-group col-md-2">
			<div class="input-group">
				<span class="input-group-addon"><?php echo __d('field', 'Width (px)'); ?></span>
				<?php echo $this->Form->input('max_width', ['label' => false, 'size' => 10]); ?>
			</div>
		</div>

		<div class="form-group col-md-2">
			<div class="input-group">
				<span class="input-group-addon"><?php echo __d('field', 'Height (px)'); ?></span>
				<?php echo $this->Form->input('max_height', ['label' => false, 'size' => 10]); ?>
			</div>
		</div>
	</div>
	<em class="help-block"><?php echo __d('field', 'The maximum allowed image size expressed as WIDTHxHEIGHT (e.g. 640x480). Leave blank for no restriction. If a larger image is uploaded, it will be rejected.'); ?></em>

	<div class="form-group">
		<?php echo $this->Form->input('min_ratio', ['size' => 10, 'label' => __d('field', 'Minimum image ratio')]); ?>
		<em class="help-block"><?php echo __d('field', 'The upload will be invalid if the image apect ratio (e.g. 1.6) is lower. Leave blank for no restriction.'); ?></em>

		<?php echo $this->Form->input('max_ratio', ['size' => 10, 'label' => __d('field', 'Maximum image ratio')]); ?>
		<em class="help-block"><?php echo __d('field', 'The upload will be invalid if the image apect ratio (e.g. 1.6) is greater. Leave blank for no restriction.'); ?></em>

		<?php echo $this->Form->input('min_pixels', ['size' => 10, 'label' => __d('field', 'Minimum image pixels')]); ?>
		<em class="help-block"><?php echo __d('field', 'The upload will be invalid if the image number of pixels is lower. Leave blank for no restriction.'); ?></em>

		<?php echo $this->Form->input('max_pixels', ['size' => 10, 'label' => __d('field', 'Maximum image pixels')]); ?>
		<em class="help-block"><?php echo __d('field', 'The upload will be invalid if the image number of pixels is greater. Leave blank for no restriction.'); ?></em>
	</div>
</fieldset>