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

<?php echo $this->jQuery->theme(); ?>
<?php echo $this->jQuery->ui('datepicker'); ?>

<span id="dp-container-<?php echo $field->name; ?>"><?php echo $this->Form->input($field, ['readonly']); ?></span>

<script>
	$(document).ready(function() {
		$('#dp-container-<?php echo $field->name; ?> input').datepicker();
	});
</script>