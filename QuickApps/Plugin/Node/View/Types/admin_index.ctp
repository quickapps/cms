<?php
$tSettings = array(
	'columns' => array(
		__t('Name') => array(
			'value' => '{NodeType.name} <em>(id: {NodeType.id})</em>',
			'sort' => 'NodeType.name'
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/node/types/display/{NodeType.id}{/url}'>" . __t('display') . "</a>
				| <a href='{url}/admin/node/types/edit/{NodeType.id}{/url}'>" . __t('edit') . "</a>
				| <a href='{url}/admin/node/types/fields/{NodeType.id}{/url}'>" . __t('fields') . "</a>
				{php} return ('{NodeType.module}' == 'Node') ? \"| <a href='{url}/admin/node/types/delete/{NodeType.id}{/url}' onClick=\\\"return confirm('" . __t("Are you sure that you want to delete this type of content. ? This action cannot be undone.") . "'); \\\">" . __t('delete') . "</a>\" : '';{/php}
				"
			,
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'paginate' => true,
	'headerPosition' => 'top&bottom',
	'tableOptions' => array('width' => '100%')
);

 echo $this->Html->table($results, $tSettings);