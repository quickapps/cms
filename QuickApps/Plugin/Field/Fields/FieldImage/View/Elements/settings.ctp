<?php
	App::uses('FieldImage', 'FieldImage.Lib');
	$this->Layout->css('/field_image/css/field_image.css');

	echo $this->Form->input('Field.settings.extensions',
		array(
			'type' => 'text',
			'label' => __t('Allowed extensions')
		)
	);
?>
<em><?php echo __t('Comma separated. e.g. jpg,gif,png'); ?></em>

<?php
	$ranges = Hash::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}');
	$ranges['custom'] = __t('Custom');

	echo $this->Form->input('Field.settings.multi',
		array(
			'type' => 'select',
			'options' => $ranges,
			'label' => __t('Number of files'),
			'onchange' => "if (isNaN(this.value)) { $('.custom-multi').show(); } else { $('.custom-multi').hide(); }"
		)
	);

	echo '<div class="custom-multi" style="' . ($this->data['Field']['settings']['multi'] == 'custom' ? '' : 'display:none') . '">' . 
		$this->Form->input('Field.settings.multi_custom',
			array(
				'type' => 'text',
				'label' => __t('Customized number of files'),
				'onkeyup' => "if (/\D/g.test(this.value)) { this.value = this.value.replace(/\D/g,'') }"
			)
		) . '</div>';
?>
<em><?php echo __t('Maximum number of files users can upload for this field.'); ?></em>

<?php
	echo $this->Form->input('Field.settings.upload_folder',
		array(
			'type' => 'text',
			'label' => __t('Upload folder')
		)
	);
?>
<em><?php echo __t('Optional subdirectory within the upload destination where files will be stored. Do not include preceding or trailing slashes.'); ?></em>

<?php
	echo $this->Form->input('Field.settings.title',
		array(
			'type' => 'checkbox',
			'label' => __t('Enable <em>Title</em> field')
		)
	);
?>
<em><?php echo __t('The title attribute is used as a tooltip when the mouse hovers over the image.'); ?></em>

<?php
	echo $this->Form->input('Field.settings.alt',
		array(
			'type' => 'checkbox',
			'label' => __t('Enable <em>Alt</em> field')
		)
	);
?>
<em><?php echo __t('The alt attribute may be used by search engines, screen readers, and when the image cannot be loaded.'); ?></em>

<?php 
	echo $this->Form->input('Field.settings.preview',
		array(
			'type' => 'select',
			'options' => FieldImage::previewsOptions(),
			'empty' => __t('-- No preview --'),
			'label' => __t('Preview image style')
		)
	);
?>
<em><?php echo __t('The preview image will be shown while editing the content.'); ?></em>

<!-- image resolution -->
<?php echo $this->Html->useTag('fieldsetstart', __t('Image resolution')); ?>
	<div class="field-image-resolution">
		<?php echo $this->Form->label('Field.settings.min_resolution', __t('Minimum image resolution')); ?>
		<?php echo $this->Form->input('Field.settings.min_width', array('after' => 'x', 'label' => false, 'type' => 'text', 'size' => 10)); ?>
		<?php echo $this->Form->input('Field.settings.min_height', array('after' => __t('pixels'), 'label' => false, 'type' => 'text', 'size' => 10)); ?>
	</div>
	<em><?php echo __t('The minimum allowed image size expressed as WIDTHxHEIGHT (e.g. 640x480). Leave blank for no restriction. If a smaller image is uploaded, it will be rejected.'); ?></em>

	<div class="field-image-resolution">
		<?php echo $this->Form->label('Field.settings.max_resolution', __t('Maximum image resolution')); ?>
		<?php echo $this->Form->input('Field.settings.max_width', array('after' => 'x', 'label' => false, 'type' => 'text', 'size' => 10)); ?>
		<?php echo $this->Form->input('Field.settings.max_height', array('after' => __t('pixels'), 'label' => false, 'type' => 'text', 'size' => 10)); ?>
	</div>
	<em><?php echo __t('The maximum allowed image size expressed as WIDTHxHEIGHT (e.g. 640x480). Leave blank for no restriction. If a larger image is uploaded, it will be rejected.'); ?></em>

	<?php echo $this->Form->input('Field.settings.min_ratio', array('type' => 'text', 'size' => 10, 'style' => 'width:50px;', 'label' => __t('Minimum image ratio'))); ?>
	<em><?php echo __t('The upload will be invalid if the image apect ratio (e.g. 1.6) is lower. Leave blank for no restriction.'); ?></em>

	<?php echo $this->Form->input('Field.settings.max_ratio', array('type' => 'text', 'size' => 10, 'style' => 'width:50px;', 'label' => __t('Maximum image ratio'))); ?>
	<em><?php echo __t('The upload will be invalid if the image apect ratio (e.g. 1.6) is greater. Leave blank for no restriction.'); ?></em>
	
	<?php echo $this->Form->input('Field.settings.min_pixels', array('type' => 'text', 'size' => 10, 'style' => 'width:50px;', 'label' => __t('Minimum image pixels'))); ?>
	<em><?php echo __t('The upload will be invalid if the image number of pixels is lower. Leave blank for no restriction.'); ?></em>
	
	<?php echo $this->Form->input('Field.settings.max_pixels', array('type' => 'text', 'size' => 10, 'style' => 'width:50px;', 'label' => __t('Maximum image pixels'))); ?>
	<em><?php echo __t('The upload will be invalid if the image number of pixels is greater. Leave blank for no restriction.'); ?></em>
<?php echo $this->Html->useTag('fieldsetend'); ?>