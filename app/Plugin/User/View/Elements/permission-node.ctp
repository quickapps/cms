<?php
// node template for tree nodes
$return = "{$data['Aco']['alias']}";
if (!$hasChildren )
    $return .= " <a href=\"\" onclick=\"edit_aco({$data['Aco']['id']}); return false;\">" . $this->Html->image('/user/img/key.png') . "</a>";
echo $return;