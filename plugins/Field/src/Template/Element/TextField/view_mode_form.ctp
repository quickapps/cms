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
	echo $this->Form->input('formatter',
		array(
			'label' => __d('field', 'Display content as'),
			'type' => 'select',
			'options' => array(
				'plain' => __d('field', 'Plain'),
				'full' => __d('field', 'Full'),
				'trimmed' => __d('field', 'Trimmed')
			),
			'empty' => false,
			'escape' => false,
			'onchange' => "if (this.value == 'trimmed') { $('#trimmed').show(); } else { $('#trimmed').hide(); };"
		)
	);
?>

<ul>
	<li><em class="help-block"><strong><?php echo __d('field', 'Full'); ?>:</strong> <?php echo __d('field', 'Text will be rendered with no modifications.'); ?></em></li>
	<li><em class="help-block"><strong><?php echo __d('field', 'Plain'); ?>:</strong> <?php echo __d('field', 'Text will converted to plain text.'); ?></em></li>
	<li><em class="help-block"><strong><?php echo __d('field', 'Trimmed'); ?>:</strong> <?php echo __d('field', 'Text will cut to an specific length.'); ?></em></li>
</ul>

<div id="trimmed" style="<?php echo !empty($this->request->data['display_type']) && $this->request->data['display_type'] !== 'trimmed' ? 'display:none;' : ''; ?>">
	<?php
		echo $this->Form->input('trim_length',
			array(
				'type' => 'text',
				'label' => __d('field', 'Trim length or read-more-cutter')
			)
		);
	?>

	<ul>
		<li><em class="help-block"><?php echo __d('field', 'Numeric value will convert content to plain text and then trim it to the specified number of chars. e.g.: 400'); ?></em></li>
		<li><em class="help-block"><?php echo __d('field', 'String value will cut the content in two by the specified string, the first part will be displayed. e.g.: &lt;!-- readmore --&gt;'); ?></em></li>
	</ul>
</div>