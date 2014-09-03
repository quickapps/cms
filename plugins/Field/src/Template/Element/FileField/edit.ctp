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

<?php echo $this->element('Field.FileField/upload_libs'); ?>
<?php
	if ($field->metadata->settings['multi'] === 'custom') {
		$settings = $field->metadata->settings;
		$settings['multi'] = $field->metadata->settings['multi_custom'];
		$field->metadata->set('settings', $settings);
	}

	$multi = intval($field->metadata->settings['multi']) > 1;
	$instanceID = "FileField-{$field->metadata->field_instance_id}";
?>

<div id="<?php echo $instanceID; ?>" class="file-handler">
	<?php echo $this->Form->label("{$instanceID}-uploader", $field->label); ?>
	<?php if ($error = $this->Form->error(":{$field->name}")): ?>
		<div class="form-group has-error has-feedback">
			<?php echo $error; ?>
		</div>
	<?php endif; ?>

	<?php
		// forces field handler callbacks when 0 files is send
		echo $this->Form->input(":{$field->name}.dummy", ['type' => 'hidden', 'value' => 'dummy']);
	?>

	<ul id="<?php echo $instanceID; ?>-files-list" class="files-list list-unstyled">
		<?php foreach ($field->extra as $key => $file): ?>
			<?php 
				if (!is_integer($key)) {
					continue;
				}
			?>
			<?php $uid = $instanceID . '-' . strtoupper(substr(md5($key . $file['file_name'] . time()), 0, 8));	?>
			<li>
				<script type="text/javascript">
					var view = {
						uid: '<?php echo $uid; ?>',
						perm: true,
						number: <?php echo $key; ?>,
						icon_url: '<?php echo $this->Url->build("/field/img/file-icons/{$file['mime_icon']}", true); ?>',
						link: '<?php echo $this->Url->build(normalizePath("/files/{$field->metadata->settings['upload_folder']}/{$file['file_name']}", '/'), true); ?>',
						file_name: '<?php echo $file['file_name']; ?>',
						file_size: '<?php echo $file['file_size']; ?>',
						instance_name: '<?php echo $field->name; ?>',
						mime_icon: '<?php echo $file['mime_icon']; ?>',
						file_name: '<?php echo $file['file_name']; ?>',
						file_size: '<?php echo $file['file_size']; ?>',
						description: '<?php echo $file['description']; ?>',
						show_icon: <?php echo !empty($file['mime_icon']) ? 'true' : 'false'; ?>,
						show_description: <?php echo $field->metadata->settings['description'] ? 'true' : 'false'; ?>,
					};
					document.write(Mustache.render($('#file-item-template').html(), view));
				</script>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>">
		<?php echo $this->Form->input(":{$field->name}.uploader", ['id' => "{$instanceID}-uploader", 'type' => 'file', 'label' => false]); ?>
		<em class="help-block">
			<?php echo __d('field', 'Files must be less than <strong>{0}B</strong>.', ini_get('upload_max_filesize')); ?><br />

			<?php if (!empty($field->metadata->settings['extensions'])): ?>
				<?php echo __d('field', 'Allowed file types: <strong>{0}</strong>.', str_replace(',', ', ', $field->metadata->settings['extensions'])); ?><br />
			<?php endif; ?>
		</em>
		<div id="<?php echo $instanceID; ?>-uploader-queue" class="uploadify-queue"></div>
	</div>

	<?php if (!empty($field->metadata->description)): ?>
		<em class="help-block"><?php echo $field->metadata->description; ?></em>
	<?php endif; ?>
</div>

<script type="text/javascript">
	$('#<?php echo $instanceID; ?>-files-list').sortable();

	var settings = {
		instanceID: '<?php echo $instanceID; ?>',
		instanceName: '<?php echo $field->name; ?>',
		showDescription: <?php echo !empty($field->metadata->settings['description']) ? 'true' : 'false'; ?>,
		uploader: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'uploadify', 'action' => 'upload', $field->name], true); ?>',
		remover: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'uploadify', 'action' => 'delete', $field->name], true); ?>',

		<?php if (!empty($field->metadata->settings['extensions'])): ?>
			fileTypeExts: '*.<?php echo str_replace(',', ';*.', $field->metadata->settings['extensions']); ?>',
		<?php endif; ?>

		fileTypeDesc: '<?php echo $field->label; ?>',
		queueID: 'queue-<?php echo $field->metadata->field_instance_id; ?>',
		multi: <?php echo $multi ? 'true' : 'false'; ?>,
		queueSizeLimit: 10,
		uploadLimit: <?php echo $field->metadata->settings['multi'] - count($field->extra); ?>,
		buttonText: '<?php echo __d('field', 'Upload File'); ?>',
	};

	$(document).ready(function () {
		FileField.init(settings);
	});
</script>
