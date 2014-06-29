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

<?php $type = !empty($field->metadata->settings->type) ? $field->metadata->settings->type : 'text'; ?>
<?php $ckeditor = $field->metadata->settings->text_processing === 'full' && $type === 'textarea' ? 'ckeditor' : ''; ?>
<?php $rows = $type === 'textarea' ? 5 : ''; ?>
<?php echo $this->Form->input($field, ['type'=> $type, 'class' => $ckeditor, 'rows' => $rows]); ?>