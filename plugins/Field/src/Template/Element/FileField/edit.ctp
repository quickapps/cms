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
	$multi = intval($field->metadata->settings['multi']) > 1;
	$instanceID = "FileField-{$field->metadata->field_instance_id}";
?>

<div id="<?php echo $instanceID; ?>" class="file-handler">
	<?php echo $this->Form->label("{$instanceID}-uploader", $field->label); ?>

	<?php if ($field->metadata->errors): ?>
		<div class="form-group has-error has-feedback">
			<?php echo $this->Form->error(":{$field->name}"); ?>
		</div>
	<?php endif; ?>

	<?php
		// forces field handler callbacks when 0 files is send
		echo $this->Form->input(":{$field->name}.dummy", ['type' => 'hidden', 'value' => 'dummy']);
	?>

	<ul id="<?php echo $instanceID; ?>-files-list" class="files-list list-unstyled">
		<?php foreach ((array)$field->extra as $key => $file): ?>
			<?php 
				if (!is_integer($key)) {
					continue;
				}
			?>
			<li class="item-<?php echo $key; ?>">
				<script type="text/javascript">
					$(document).on('<?php echo $instanceID; ?>-init', function () {
						var view = {
							perm: true,
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
						$('#<?php echo $instanceID; ?> li.item-<?php echo $key; ?>').html(FileField.renderItem(<?php echo $field->metadata->field_instance_id; ?>, view));
					});
				</script>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>">
		<div id="<?php echo $instanceID; ?>-uploader-queue" class="uploadify-queue"></div>
		<?php echo $this->Form->input(":{$field->name}.uploader", ['id' => "{$instanceID}-uploader", 'type' => 'file', 'label' => false]); ?>
		<em class="help-block">
			<?php echo __d('field', 'Files must be less than <strong>{0}B</strong>.', ini_get('upload_max_filesize')); ?><br />

			<?php if (!empty($field->metadata->settings['extensions'])): ?>
				<?php echo __d('field', 'Allowed file types: <strong>{0}</strong>.', str_replace(',', ', ', $field->metadata->settings['extensions'])); ?><br />
			<?php endif; ?>

			<?php echo __d('field', 'You can upload up to <strong>{0}</strong> files.', $field->metadata->settings['multi']); ?><br />
		</em>
	</div>

	<?php if (!empty($field->metadata->description)): ?>
		<em class="help-block"><?php echo $field->metadata->description; ?></em>
	<?php endif; ?>
</div>

<?php if (!empty($field->metadata->settings['JSON']['instance']['id'])): ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#<?php echo $field->metadata->settings['JSON']['instance']['id']; ?>-files-list').sortable();
			FileField.init(<?php echo json_encode($field->metadata->settings['JSON'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
		});
	</script>
<?php else: ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#<?php echo $instanceID; ?>-files-list').sortable();
			FileField.init({
				instance: {
					id: <?php echo $field->metadata->field_instance_id; ?>,
					name: '<?php echo $field->name; ?>',
					showDescription: <?php echo !empty($field->metadata->settings['description']) ? 'true' : 'false'; ?>,
				},
				uploader: {
					queueID: '<?php echo $instanceID; ?>-uploader-queue',
					multi: <?php echo $multi ? 'true' : 'false'; ?>,
					<?php if (!empty($field->metadata->settings['extensions'])): ?>
						fileTypeExts: '*.<?php echo str_replace(',', ';*.', $field->metadata->settings['extensions']); ?>',
					<?php endif; ?>
					queueSizeLimit: 10,
					uploadLimit: <?php echo $field->metadata->settings['multi'] - count((array)$field->extra); ?>,
					fileSizeLimit: '<?php echo ini_get('upload_max_filesize'); ?>B',
					fileTypeDesc: '<?php echo $field->label; ?>',
					buttonText: '<?php echo __d('field', 'Upload File'); ?>',
					uploader: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'file_handler', 'action' => 'upload', $field->name], true); ?>',
					remover: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'file_handler', 'action' => 'delete', $field->name], true); ?>',
				},
			});
		});
	</script>
<?php endif; ?>
