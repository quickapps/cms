<!-- Field File -->
<?php echo $this->Layout->hook('field_file_libs'); ?>
<?php
	if (isset($data['field']['settings']['multi']) && $data['field']['settings']['multi'] == 'custom') {
		$data['field']['settings']['multi'] = $data['field']['settings']['multi_custom'];
	}

	$multi = (isset($data['field']['settings']['multi']) && $data['field']['settings']['multi'] > 1);
?>

<div id="FieldDataFieldFile<?php echo $data['field']['id']; ?>">
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
				'file_path' => '',
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
	if (isset($this->data['FieldData']['FieldFile'][$data['field']['id']]['data']['files'])) {
		$uploaded_path = $this->data['FieldData']['FieldFile'][$data['field']['id']]['uploaded_path'];
		$data['field']['FieldData']['data']['files'] = $this->data['FieldData']['FieldFile'][$data['field']['id']]['data']['files'];
	}

	echo '<ul class="files-list">';

	foreach ($data['field']['FieldData']['data']['files'] as $key => $file) {
		$file = Hash::merge(
			array(
				'mime_icon' => '',
				'file_path' => '',
				'file_name' => '',
				'file_size' => ''
			), (array)$file
		);

		echo "<li>";

		$uid = strtoupper(substr(md5($key . time()), 0, 8));
?>

	<div class="snippet" id="FieldDataFieldFile<?php echo $data['field']['id']; ?>_<?php echo $uid; ?>">
		<img class="file-icon" src="<?php echo isset($file['mime_icon']) && !empty($file['mime_icon']) ? $this->Html->url('/field_file/img/icons/' . $file['mime_icon']) : ''; ?>" />
		<span class="file-name">
			<a href="<?php echo $this->Html->url("/files/{$data['field']['settings']['upload_folder']}/{$file['file_name']}"); ?>" target="_blank">
				<?php echo @$file['file_name']; ?>
			</a>
		</span>
		<span class="file-size">(<?php echo @$file['file_size']; ?>)</span>
		<div class="submit"><input type="button" value="<?php echo __t('Remove'); ?>" onClick="QuickApps.field_file.remove('FieldDataFieldFile<?php echo $data['field']['id']; ?>_<?php echo $uid; ?>'); return false;" /></div>

		<?php
			echo $this->Form->hidden("FieldData.FieldFile.{$data['field']['id']}.data.files.{$key}.mime_icon", array('class' => 'mime_icon', 'value' => $file['mime_icon'])) . "\n";
			echo "\t" . $this->Form->hidden("FieldData.FieldFile.{$data['field']['id']}.data.files.{$key}.file_name", array('class' => 'file_name', 'value' => $file['file_name'])) . "\n";
			echo "\t" . $this->Form->hidden("FieldData.FieldFile.{$data['field']['id']}.data.files.{$key}.file_size", array('class' => 'file_size', 'value' => $file['file_size'])) . "\n";
		?>

	</div>

<?php
		if (isset($data['field']['settings']['description']) && $data['field']['settings']['description']) {
			echo $this->Form->input("FieldData.FieldFile.{$data['field']['id']}.data.files.{$key}.description", array('value' => @$file['description']));
		}

		echo '</li>';
	} // end foreach

	echo '</ul>';

	echo $this->Form->hidden("FieldData.FieldFile.{$data['field']['id']}.id", array('value' => @$data['field']['FieldData']['id']));
	echo $this->Form->hidden("FieldData.FieldFile.{$data['field']['id']}.uploaded_path", array('value' => $uploaded_path));

	$show_uploader = (
		!count($data['field']['FieldData']['data']['files']) ||
		($multi && count($data['field']['FieldData']['data']['files']) < $data['field']['settings']['multi'])
	);
?>

	<div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>" style="<?php echo $show_uploader ? '' : 'display:none;'; ?>">
		<?php
			echo $this->Form->input("FieldData.FieldFile.{$data['field']['id']}.uploader",
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
	$("ul.files-list").sortable({opacity: 0.6, cursor: 'move'});

	var Settings = new Array();
	Settings['fileExt'] = '*.<?php echo str_replace(',', ';*.', $data['field']['settings']['extensions']); ?>';
	Settings['fileDesc'] = '<?php echo $data['field']['label']; ?>';
	Settings['queueID'] = 'FieldQueue<?php echo $data['field']['id']; ?>';
	Settings['upload_folder'] = '<?php echo @$data['field']['settings']['upload_folder']; ?>';
	Settings['description'] = <?php echo isset($data['field']['settings']['description']) && $data['field']['settings']['description'] ? 'true' : 'false'; ?>;
	Settings['instance_id'] = <?php echo $data['field']['id']; ?>;
	Settings['can_upload'] = <?php echo $data['field']['settings']['multi'] - count($data['field']['FieldData']['data']['files']); ?>;
	<?php if ($multi): ?>

	Settings['multi'] = true;
	Settings['queueSizeLimit'] = <?php echo $data['field']['settings']['multi']; ?>;
	<?php else: ?>

	Settings['multi'] = false;
	Settings['queueSizeLimit'] = 1;
	<?php endif; ?>

	QuickApps.field_file.setupField('FieldDataFieldFile<?php echo $data['field']['id']; ?>', Settings);
</script>