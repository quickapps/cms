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
<?= $this->element('Field.FileField/upload_libs'); ?>

<div id="<?= $instanceID; ?>" class="file-handler well well-sm">
    <?= $this->Form->label("{$instanceID}-uploader", $field->label); ?>

    <hr />

    <?php if ($field->metadata->errors): ?>
        <div class="form-group has-error has-feedback">
            <?= $this->Form->error($field->name); ?>
        </div>
    <?php endif; ?>

    <?php // forces field handler to work when no files are send ?>
    <?= $this->Form->input("{$field->name}.dummy", ['type' => 'hidden', 'value' => 'dummy']); ?>

    <ul id="<?= $instanceID; ?>-files-list" class="files-list list-unstyled">
        <?php foreach ((array)$field->extra as $key => $file): ?>
            <?php
                if (!is_integer($key)) {
                    continue;
                }
            ?>
            <li class="item-<?= $key; ?>">
                <script type="text/javascript">
                    $(document).on('<?= $instanceID; ?>-init', function () {
                        var fileSettings = <?= json_encode($file, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>;
                        var view = {
                            perm: true,
                            icon_url: '<?= $this->Url->build("/field/img/file-icons/{$file['mime_icon']}", true); ?>',
                            link: '<?= $this->Url->build(normalizePath("/files/{$field->metadata->settings['upload_folder']}/{$file['file_name']}", '/'), true); ?>',
                            instance_name: '<?= $field->name; ?>',
                            show_icon: <?= !empty($file['mime_icon']) ? 'true' : 'false'; ?>,
                            show_description: <?= $field->metadata->settings['description'] ? 'true' : 'false'; ?>,
                        };
                        $('#<?= $instanceID; ?> li.item-<?= $key; ?>').html(FileField.renderItem(<?= $field->metadata->instance_id; ?>, $.extend(fileSettings, view)));
                    });
                </script>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="uploader <?= $multi ? 'multi-upload' : 'single-upload'; ?>">
        <div id="<?= $instanceID; ?>-uploader-queue" class="uploadify-queue">
            <!-- <?= $instanceID; ?>'s QUEUE -->
        </div>
        <?= $this->Form->input(":{$field->name}.uploader", ['id' => "{$instanceID}-uploader", 'type' => 'file', 'label' => false]); ?>

        <em class="help-block">
            <?= __d('field', 'Files must be less than <strong>{0}B</strong>.', ini_get('upload_max_filesize')); ?><br />
            <?php if (!empty($field->metadata->settings['extensions'])): ?>
                <?= __d('field', 'Allowed file types: <strong>{0}</strong>.', str_replace(',', ', ', $field->metadata->settings['extensions'])); ?><br />
            <?php endif; ?>
            <?= __d('field', 'You can upload up to <strong>{0}</strong> files.', $field->metadata->settings['multi']); ?><br />
        </em>
    </div>

    <?php if (!empty($field->metadata->description)): ?>
        <em class="help-block"><?= $field->metadata->description; ?></em>
    <?php endif; ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        <?php if (!isset($options['sortable']) || $options['sortable'] === true): ?>
            $('#<?= $instanceID; ?>-files-list li').css({'cursor': 'move'});
            $('#<?= $instanceID; ?>-files-list').sortable();
        <?php endif; ?>

        <?php if (!empty($options['initScript'])): ?>
            <?= $options['initScript']; ?>
        <?php else: ?>
            FileField.init({
                instance: {
                    id: <?= $field->metadata->instance_id; ?>,
                    name: '<?= $field->name; ?>',
                    showDescription: <?= !empty($field->metadata->settings['description']) ? 'true' : 'false'; ?>,
                },
                uploader: {
                    queueID: '<?= $instanceID; ?>-uploader-queue',
                    multi: <?= $multi ? 'true' : 'false'; ?>,
                    <?php if (!empty($field->metadata->settings['extensions'])): ?>
                        fileTypeExts: '*.<?= str_replace(',', ';*.', $field->metadata->settings['extensions']); ?>',
                    <?php endif; ?>
                    queueSizeLimit: 10,
                    uploadLimit: <?= $field->metadata->settings['multi'] - count((array)$field->extra); ?>,
                    fileSizeLimit: '<?= ini_get('upload_max_filesize'); ?>B',
                    fileTypeDesc: '<?= $field->label; ?>',
                    buttonText: '<?= __d('field', 'Upload File'); ?>',
                    uploader: '<?= $this->Url->build(['plugin' => 'Field', 'controller' => 'file_handler', 'action' => 'upload', 'prefix' => false, $field->name], true); ?>',
                    remover: '<?= $this->Url->build(['plugin' => 'Field', 'controller' => 'file_handler', 'action' => 'delete', 'prefix' => false, $field->name], true); ?>',
                },
            });
        <?php endif; ?>
    });
</script>
