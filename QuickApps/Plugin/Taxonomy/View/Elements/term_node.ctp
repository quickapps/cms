<?php
/**
 * Tree node for Drag-and-Drop terms interface
 */
    $edit = $this->Html->link(__t('edit'), '/admin/taxonomy/vocabularies/edit_term/' . $data['Term']['slug']);
    $delete = $this->Html->link(__t('delete'), "/admin/taxonomy/vocabularies/delete_term/{$data['Term']['slug']}", array(), __t('Delete selected term ?'));
    $opts = array($edit, $delete);
    $opts = implode(' | ', Hash::filter($opts));
    $description = !empty($data['Term']['description']) ? "<br/><em>{$data['Term']['description']}</em>" : '';
    $return = "<div>" . __t($data['Term']['name']) . "{$description} <span style='float:right;'>{$opts}</span></div>";

    echo $return;