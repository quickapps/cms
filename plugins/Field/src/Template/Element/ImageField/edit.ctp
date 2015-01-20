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

<?php
	$options = [
		'sortable' => true,
		'initScript' => $this->element('Field.ImageField/item_init_script', compact('field'))
	];
?>
<?php echo $this->element('Field.ImageField/upload_item'); ?>
<?php echo $this->element('Field.FileField/edit', compact('field', 'options')); ?>
