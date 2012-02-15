<?php
    $info = $this->viewVars['acos_details'][$data['Aco']['id']];
    $return = "<span title=\"{$info['description']}\">{$info['name']}</span>";

    if (!$hasChildren && $depth == 2) {
        $return .= " <a href=\"\" onclick=\"edit_aco({$info['id']}); return false;\">" . $this->Html->image('/user/img/key.png') . "</a>";
    } elseif (is_string($info['id']) && strpos($info['id'], '.') !== false && $info['parent_id']) {
        $return .= " <a href=\"\" onclick=\"edit_aco('{$info['id']}'); return false;\">" . $this->Html->image('/user/img/key.png') . "</a>";
    }

    echo $return;