<?php
	echo $this->Form->input('formatter', [
		'id' => 'formatter-type',
		'label' => __d('taxonomy', 'Show as'),
		'type' => 'select',
		'options' => array(
			'plain' => __d('taxonomy', 'Plain text'),
			'link_localized' => __d('taxonomy', 'Links (localized)'),
			'plain_localized' => __d('taxonomy', 'Plain text (localized)')
		),
		'empty' => false,
		'onchange' => 'toggleUrlPrefix()',
	]);
?>

<div class="option-url-prefix">
	<?php
		echo $this->Form->input('link_template', [
			'label' => __d('taxonomy', 'Link template'),
			'type' => 'text',
		]);
	?>

	<em class="help-block">
		<?php echo __d('taxonomy', '<strong>Experts only</strong>. A string compatible with HtmlHelper::url() used to create each link. Placeholders are:'); ?>
		<ul>
			<li><code>{{url}}</code>: <?php echo __d('taxonomy', 'Will be replaced with auto-generated URL.'); ?></li>
			<li><code>{{attrs}}</code>: <?php echo __d('taxonomy', 'Will be replaced with auto-generated HTML attributes.'); ?></li>
			<li><code>{{content}}</code>: <?php echo __d('taxonomy', "Will be replaced with term's name"); ?></li>
		</ul>
		<strong><?php echo __d('taxonomy', 'Example'); ?></strong>: <code><?php echo h('<a href="{{url}}"{{attrs}}>{{content}}</a>'); ?></code>
	</em>
</div>

<script>
	$(document).ready(function () {
		toggleUrlPrefix();
	});

	function toggleUrlPrefix() {
		if ($('#formatter-type').val() === 'link_localized') {
			$('div.option-url-prefix').show();
		} else {
			$('div.option-url-prefix').hide();
		}
	}
</script>
