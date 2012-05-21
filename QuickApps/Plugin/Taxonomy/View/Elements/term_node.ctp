<?php
/**
 * Tree node for Drag-and-Drop terms interface
 *
 */
	$edit = $this->Html->link(__t('edit'), '/admin/taxonomy/vocabularies/edit_term/' . $data['slug']);
	$delete = $this->Html->link(__t('delete'), "/admin/taxonomy/vocabularies/delete_term/{$data['slug']}", array(), __t('Delete selected term ?'));
	$opts = array($edit, $delete);
	$opts = implode(' | ', Hash::filter($opts));
	$description = !empty($data['description']) ? "<br/><em>{$data['description']}</em>" : '';
	$return = "<div>" . __t($data['name']) . "{$description} <span style='float:right;'>{$opts}</span></div>";

	echo $return;