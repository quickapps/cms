<?php
/**
 * Block settings.
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php
	echo $this->Form->input('Block.settings.url_prefix',
		array(
			'between' => $this->Html->url('/', true) . 's/',
			'after' => ' my-search-criteria',
			'type' => 'text',
			'label' => __t('URL prefix'),
			'helpBlock' => __t('Adds a prefix to each search URL query.')
		)
	);
?>