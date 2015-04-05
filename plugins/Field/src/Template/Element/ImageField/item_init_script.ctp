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
        id: <?php echo $field->metadata->instance_id; ?>,
        name: '<?php echo $field->name; ?>',
        showDescription: false,
        showThumbnail: <?php echo !empty($field->metadata->settings['preview']) ? 'true' : 'false'; ?>,
        showTitle: <?php echo !empty($field->metadata->settings['title_attr']) ? 'true' : 'false'; ?>,
        showAlt: <?php echo !empty($field->metadata->settings['alt_attr']) ? 'true' : 'false'; ?>,
        thumbnailSize: '<?php echo $field->metadata->settings['preview']; ?>',
        itemFormatter: 'imageFieldItemFormatter',
    },
    uploader: {
        buttonText: '<?php echo __d('field', 'Upload Image'); ?>',
        uploadLimit:  <?php echo $field->metadata->settings['multi'] - count((array)$field->extra); ?>,
        uploader: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'upload', 'prefix' => false, $field->name], true); ?>',
        remover: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'delete', 'prefix' => false, $field->name], true); ?>',
        errorMessages: {
            502: 'The file {{file.name}} could not be uploaded: invalid image given.'
        }
    }
});
