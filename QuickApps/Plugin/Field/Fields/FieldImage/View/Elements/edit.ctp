<!-- Field Image -->
<?php echo $this->Layout->hook('field_image_libs'); ?>
<?php
	App::uses('FieldImage', 'FieldImage.Lib');

	if (isset($data['field']['settings']['multi']) && $data['field']['settings']['multi'] == 'custom') {
		$data['field']['settings']['multi'] = $data['field']['settings']['multi_custom'];
	}

	$multi = (isset($data['field']['settings']['multi']) && $data['field']['settings']['multi'] > 1);
?>

<div id="FieldDataFieldImage<?php echo $data['field']['id']; ?>">
<?php
	echo $this->Html->useTag('fieldsetstart', $data['field']['label']);

	if ($data['field']['required']) {
		$options['required'] = 'required';
	}

	$data['field']['FieldData'] = Hash::merge(
		array(
			'id' => null,
			'field_id' => null,
			'foreignKey' => null,
			'belongsTo' => null,
			'data' => array(
				'file_name' => '',
				'description' => ''
			)
		), @$data['field']['FieldData']
	);

	$data['field']['FieldData']['data'] = Hash::merge(
		array(
			'files' => array(),
			'description' => ''
		),
		(array)$data['field']['FieldData']['data']
	);

	$uploaded_path = '[]';

	// form realoaded by validation error
	if (isset($this->data['FieldData']['FieldImage'][$data['field']['id']]['data']['files'])) {
		$uploaded_path = $this->data['FieldData']['FieldImage'][$data['field']['id']]['uploaded_path'];
		$data['field']['FieldData']['data']['files'] = $this->data['FieldData']['FieldImage'][$data['field']['id']]['data']['files'];
	}

	echo '<ul class="images-list">';

	foreach ($data['field']['FieldData']['data']['files'] as $key => $file) {
		$file = Hash::merge(
			array(
				'mime_icon' => '',
				'file_name' => '',
				'file_size' => '',
				'image_title' => '',
				'image_alt' => ''
			), (array)$file
		);

		echo "<li>";

		$uid = strtoupper(substr(md5($key . time()), 0, 8));
?>

	<div class="snippet" id="FieldDataFieldImage<?php echo $data['field']['id']; ?>_<?php echo $uid; ?>">
		<?php if (isset($data['field']['settings']['preview']) && !empty($data['field']['settings']['preview'])): ?>
		<div class="preview">
			<?php
				$img_path = WWW_ROOT . 'files' . DS;
				$img_path .= isset($data['field']['settings']['upload_folder']) && !empty($data['field']['settings']['upload_folder']) ? str_replace('/', DS, $data['field']['settings']['upload_folder']) : '';
				list($w, $h) = FieldImage::getImageSize($img_path . $file['file_name'], $data['field']['settings']['preview']);
			?>
			<img src="<?php echo Router::url("/field_image/uploadify/preview/{$data['field']['id']}/{$file['file_name']}/{$w}/{$h}", true); ?>" width="<?php echo $w; ?>" height="<?php echo $h; ?>" />
		</div>
		<?php endif; ?>

		<div class="info">
			<img class="file-icon" src="<?php echo isset($file['mime_icon']) && !empty($file['mime_icon']) ? $this->Html->url('/field_file/img/icons/' . $file['mime_icon']) : ''; ?>" />
			<span class="file-name">
				<a href="<?php echo $this->Html->url("/files/{$data['field']['settings']['upload_folder']}/{$file['file_name']}"); ?>" target="_blank">
					<?php echo @$file['file_name']; ?>
				</a>
			</span>
			<span class="file-size">(<?php echo @$file['file_size']; ?>)</span>
			<div class="submit"><input type="button" value="<?php echo __t('Remove'); ?>" onClick="QuickApps.field_image.remove('FieldDataFieldImage<?php echo $data['field']['id']; ?>_<?php echo $uid; ?>'); return false;" /></div>

			<?php
				echo $this->Form->hidden("FieldData.FieldImage.{$data['field']['id']}.data.files.{$key}.mime_icon", array('class' => 'mime_icon', 'value' => $file['mime_icon'])) . "\n";
				echo "\t" . $this->Form->hidden("FieldData.FieldImage.{$data['field']['id']}.data.files.{$key}.file_name", array('class' => 'file_name', 'value' => $file['file_name'])) . "\n";
				echo "\t" . $this->Form->hidden("FieldData.FieldImage.{$data['field']['id']}.data.files.{$key}.file_size", array('class' => 'file_size', 'value' => $file['file_size'])) . "\n";
			?>
			
			<?php if (isset($data['field']['settings']['title']) && $data['field']['settings']['title']): ?>
				<?php echo "\t" . $this->Form->input("FieldData.FieldImage.{$data['field']['id']}.data.files.{$key}.image_title", array('label' => __t('Title'), 'type' => 'text', 'class' => 'image_title', 'value' => $file['image_title'], 'helpBlock' => __t('The title is used as a tool tip when the user hovers the mouse over the image.'))) . "\n"; ?>
			<?php endif; ?>

			<?php if (isset($data['field']['settings']['alt']) && $data['field']['settings']['alt']): ?>
				<?php echo "\t" . $this->Form->input("FieldData.FieldImage.{$data['field']['id']}.data.files.{$key}.image_alt", array('label' => __t('Alternate text'), 'type' => 'text', 'class' => 'image_alt', 'value' => $file['image_alt'], 'helpBlock' => __t('This text will be used by screen readers, search engines, or when the image cannot be loaded.'))) . "\n"; ?>
			<?php endif; ?>
		</div>
	</div>

<?php
		if (isset($data['field']['settings']['description']) && $data['field']['settings']['description']) {
			echo $this->Form->input("FieldData.FieldImage.{$data['field']['id']}.data.files.{$key}.description", array('value' => @$file['description']));
		}

		echo '</li>';
	} // end foreach

	echo '</ul>';

	echo $this->Form->hidden("FieldData.FieldImage.{$data['field']['id']}.id", array('value' => @$data['field']['FieldData']['id']));
	echo $this->Form->hidden("FieldData.FieldImage.{$data['field']['id']}.uploaded_path", array('value' => $uploaded_path));

	$show_uploader = (
		!count($data['field']['FieldData']['data']['files']) ||
		($multi && count($data['field']['FieldData']['data']['files']) < $data['field']['settings']['multi'])
	);
?>

	<div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>" style="<?php echo $show_uploader ? '' : 'display:none;'; ?>">
		<?php
			echo $this->Form->input("FieldData.FieldImage.{$data['field']['id']}.uploader",
				array(
					'type' => 'file',
					'label' => false,
					'helpBlock' =>
						__t('Files must be less than <b>%sB</b>.', ini_get('upload_max_filesize')) .
						'<br />' .
						__t('Allowed file types: <b>%s</b>.', str_replace(',', ', ', $data['field']['settings']['extensions']))
				)
			);
		?>

		<div id="FieldQueue<?php echo $data['field']['id']; ?>" class="field-queue"></div>
	</div>

<?php if (!empty($data['field']['description'])): ?>
	<?php echo $this->Form->helpBlock($data['field']['description']); ?>
<?php endif; ?>

<?php echo $this->Html->useTag('fieldsetend'); ?>

</div>

<script type="text/javascript">
	$("ul.images-list").sortable({opacity: 0.6, cursor: 'move'});

	var Settings = new Array();
	
	// Uploadify settings
	Settings['fileExt'] = '*.<?php echo str_replace(',', ';*.', $data['field']['settings']['extensions']); ?>';
	Settings['fileDesc'] = '<?php echo $data['field']['label']; ?>';
	Settings['queueID'] = 'FieldQueue<?php echo $data['field']['id']; ?>';
	
	// field settings
	Settings['upload_folder'] = '<?php echo @$data['field']['settings']['upload_folder']; ?>';
	Settings['title'] = <?php echo isset($data['field']['settings']['title']) && $data['field']['settings']['title'] ? 'true' : 'false'; ?>;
	Settings['alt'] = <?php echo isset($data['field']['settings']['alt']) && $data['field']['settings']['alt'] ? 'true' : 'false'; ?>;
	Settings['preview'] = <?php echo isset($data['field']['settings']['preview']) && $data['field']['settings']['preview'] ? 'true' : 'false'; ?>;
	Settings['instance_id'] = <?php echo $data['field']['id']; ?>;
	Settings['can_upload'] = <?php echo $data['field']['settings']['multi'] - count($data['field']['FieldData']['data']['files']); ?>;

	// multi upload
	<?php if ($multi): ?>
	Settings['multi'] = true;
	Settings['queueSizeLimit'] = <?php echo $data['field']['settings']['multi']; ?>;
	<?php else: ?>
	Settings['multi'] = false;
	Settings['queueSizeLimit'] = 1;
	<?php endif; ?>

	QuickApps.field_image.setupField('FieldDataFieldImage<?php echo $data['field']['id']; ?>', Settings);
</script>