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

FileField.init({
    instance: {
        id: <?= $field->metadata->instance_id; ?>,
        name: '<?= $field->name; ?>',
        showDescription: false,
        showThumbnail: <?= !empty($field->metadata->settings['preview']) ? 'true' : 'false'; ?>,
        showTitle: <?= !empty($field->metadata->settings['title_attr']) ? 'true' : 'false'; ?>,
        showAlt: <?= !empty($field->metadata->settings['alt_attr']) ? 'true' : 'false'; ?>,
        thumbnailSize: '<?= $field->metadata->settings['preview']; ?>',
        itemFormatter: 'imageFieldItemFormatter',
    },
    uploader: {
        buttonText: '<?= __d('field', 'Upload Image'); ?>',
        uploadLimit:  <?= $field->metadata->settings['multi'] - count((array)$field->extra); ?>,
        uploader: '<?= $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'upload', 'prefix' => false, $field->name], true); ?>',
        remover: '<?= $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'delete', 'prefix' => false, $field->name], true); ?>',
        errorMessages: {
            502: 'The file {{file.name}} could not be uploaded: invalid image given.'
        }
    }
});
