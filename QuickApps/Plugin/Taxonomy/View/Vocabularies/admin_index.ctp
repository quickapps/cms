<?php

$tSettings = array(
	'columns' => array(
		__t('Vocabulary name') => array(
			'value' => '{Vocabulary.title}'
		),
		__t('Description') => array(
			'value' => '<em>{Vocabulary.description}</em>',
			'tdOptions' => array('width' => '30%')
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/taxonomy/vocabularies/edit/{Vocabulary.slug}{/url}'>" . __t('edit') . "</a> |
				<a href='{url}/admin/taxonomy/vocabularies/terms/{Vocabulary.slug}{/url}'>" . __t('terms') . "</a> |
				<a href='{url}/admin/taxonomy/vocabularies/delete/{Vocabulary.id}{/url}' onclick=\"return confirm('" . __t('Delete selected vocabulary and all its terms ?') . "'); \">" . __t('delete') . "</a> |
				<a href='{url}/admin/taxonomy/vocabularies/move/{Vocabulary.id}/up{/url}'>" . __t('move up') . "</a> |
				<a href='{url}/admin/taxonomy/vocabularies/move/{Vocabulary.id}/down{/url}'>" . __t('move down') . "</a>
				",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'noItemsMessage' => __t('There are no vocabularies to display'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);

echo $this->Html->table($results, $tSettings);