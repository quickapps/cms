<?php
$tSettings = array(
	'columns' => array(
		__t('Title') => array(
			'value' => '<b>{Menu.title}</b><br/><em>{php} return __t("{Menu.description}"); {/php}</em>',
			'sort' => 'Menu.title'
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/menu/manage/edit/{Menu.id}{/url}'>" . __t('edit') . "</a> |
				<a href='{url}/admin/menu/manage/links/{Menu.id}{/url}'>" . __t('links') . "</a> |
				<a href='{url}/admin/menu/manage/add_link/{Menu.id}{/url}'>" . __t('add link') . "</a>
				{php}
					return (in_array('{Menu.id}', array('main-menu', 'management', 'navigation', 'user-menu'))) ?
						'' :
						\"| <a href='{url}/admin/menu/manage/delete/{Menu.id}{/url}' onclick='return confirm(\\\" " . __t('Delete selected menu ?') . " \\\");'>\" . __t('delete') . \"</a>\";
				{/php}
				",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);


echo $this->Html->table($results, $tSettings);