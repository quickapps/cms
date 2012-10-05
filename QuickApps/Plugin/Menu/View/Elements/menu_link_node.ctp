<?php
	$opts = array();
	$opts[] = $this->Html->link(__t('edit'), '/admin/menu/manage/edit_link/' . $data['id']);
	$opts[] = $data['module'] !== 'Menu' ? '' :  $this->Html->link(__t('delete'), "/admin/menu/manage/delete_link/{$data['id']}", array(), __t('Delete selected link ?'));
	$disabled = !$data['status'] ? ' (' . __t('disabled') . ') ' : '';
	$opts = implode(' | ', Hash::filter($opts));
	$return = "<div>" . __t($data['link_title']) . " <em>{$disabled}</em> <span style=\"float:right;\">{$opts}</span></div>";

	echo $return;