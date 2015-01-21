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
 * Renders the given node in RSS format.
 * 
 */
?>

<?php if (!empty($node->_fields)): ?>
	<?php foreach ($node->_fields->sortByViewMode($this->inUseViewMode()) as $field): ?>
		<?php echo $this->render($field); ?>
	<?php endforeach; ?>
<?php endif; ?>
