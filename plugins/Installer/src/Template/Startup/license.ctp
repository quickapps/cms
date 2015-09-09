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
	echo $this->Form->textarea('license', [
		'readonly',
		'rows' => 10,
		'value' => file_get_contents(QUICKAPPS_CORE . 'LICENSE.txt')
	]);
?>
<p>
	<?php
		echo $this->Html->link(__d('installer', 'I Agree'), [
			'plugin' => 'Installer',
			'controller' => 'startup',
			'action' => 'database'
		], [
			'class' => 'btn btn-primary pull-right'
		]);
	?>
</p>