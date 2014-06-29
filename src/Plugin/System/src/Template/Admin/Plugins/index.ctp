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

<?php foreach ($plugins as $plugin => $info): ?>
	<?php echo $this->element('System.plugin_item', ['plugin' => $plugin, 'info' => $info]); ?>
<?php endforeach; ?>

<script>
	$(document).ready(function () {

		$('a.toggler').click(function () {
			$a = $(this);
			$a.closest('div').find('.extended-info').toggle();

			if ($a.hasClass('glyphicon-arrow-up')) {
				$a.removeClass('glyphicon-arrow-up');
				$a.addClass('glyphicon-arrow-down');
			} else {
				$a.removeClass('glyphicon-arrow-down');
				$a.addClass('glyphicon-arrow-up');
			}

			return false;
		});
	});
</script>