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

/**
 * This template can be extended by providing the `$options` view variable,
 * by passing this variable you can alter some aspect of the rendering process.
 *
 * Valid options are:
 *
 * - `sortable` (bool): Whether file elements are sortable or not. Defaults to
 *    true (they can be sorted).
 * - `initScript` (string): Custom JS logic to execute when initializing this
 *    field instance. This JS code will be embed within `<script>` tags. At the
 *    end of this file you can see the default script used to initialize each
 *    field instance.
 *
 * This provides a simple way to customize a few aspects of the rendering
 * process. However, if you need to create a really customized version of this
 * template, you must create your own using this template as reference.
 */
?>

<?php $instanceID = "FileField-{$field->metadata->instance_id}"; ?>
<?php $multi = intval($field->metadata->settings['multi']) > 1; ?>
<?php echo $this->element('Field.FileField/upload_libs'); ?>

<div id="<?php echo $instanceID; ?>" class="file-handler well well-sm">
    <?php echo $this->Form->label("{$instanceID}-uploader", $field->label); ?>

    <hr />

    <?php if ($field->metadata->errors): ?>
        <div class="form-group has-error has-feedback">
            <?php echo $this->Form->error($field->name); ?>
        </div>
    <?php endif; ?>

    <?php // forces field handler to work when no files are send ?>
    <?php echo $this->Form->input("{$field->name}.dummy", ['type' => 'hidden', 'value' => 'dummy']); ?>

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
                        var fileSettings = <?php echo json_encode($file, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>;
                        var view = {
                            perm: true,
                            icon_url: '<?php echo $this->Url->build("/field/img/file-icons/{$file['mime_icon']}", true); ?>',
                            link: '<?php echo $this->Url->build(normalizePath("/files/{$field->metadata->settings['upload_folder']}/{$file['file_name']}", '/'), true); ?>',
                            instance_name: '<?php echo $field->name; ?>',
                            show_icon: <?php echo !empty($file['mime_icon']) ? 'true' : 'false'; ?>,
                            show_description: <?php echo $field->metadata->settings['description'] ? 'true' : 'false'; ?>,
                        };
                        $('#<?php echo $instanceID; ?> li.item-<?php echo $key; ?>').html(FileField.renderItem(<?php echo $field->metadata->instance_id; ?>, $.extend(fileSettings, view)));
                    });
                </script>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>">
        <div id="<?php echo $instanceID; ?>-uploader-queue" class="uploadify-queue">
            <!-- <?php echo $instanceID; ?>'s QUEUE -->
        </div>
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

<script type="text/javascript">
    $(document).ready(function () {
        <?php if (!isset($options['sortable']) || $options['sortable'] === true): ?>
            $('#<?php echo $instanceID; ?>-files-list li').css({'cursor': 'move'});
            $('#<?php echo $instanceID; ?>-files-list').sortable();
        <?php endif; ?>

        <?php if (!empty($options['initScript'])): ?>
            <?php echo $options['initScript']; ?>
        <?php else: ?>
            FileField.init({
                instance: {
                    id: <?php echo $field->metadata->instance_id; ?>,
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
                    uploader: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'file_handler', 'action' => 'upload', 'prefix' => false, $field->name], true); ?>',
                    remover: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'file_handler', 'action' => 'delete', 'prefix' => false, $field->name], true); ?>',
                },
            });
        <?php endif; ?>
    });
</script>
