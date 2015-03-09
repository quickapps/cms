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

<?php foreach ((array)$field->raw as $file): ?>
    <?php $url = normalizePath("/files/{$field->settings['upload_folder']}/{$file['file_name']}", '/'); ?>
    <p><?php echo $this->Html->link($this->Url->build($url, true), $url, ['target' => '_blank']); ?></p>
<?php endforeach; ?>
