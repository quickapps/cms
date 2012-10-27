<?php
	$class = !$depth ? "class=\"app-{$data['alias']}\"" : '';
	$return = "<span title=\"" . addslashes(__t($data['description'])) . "\" {$class}>" . __t($data['title']) . "</span>";

	if (!$hasChildren && $depth == 2) {
		$return .= " <a href=\"\" onclick=\"edit_aco({$data['id']}); return false;\"><i class=\"icon-eye-close\"></i></a>";
	} elseif (is_string($data['id']) && strpos($data['id'], '.') !== false && $data['parent_id']) {
		$return .= " <a href=\"\" onclick=\"edit_aco('{$data['id']}'); return false;\"><i class=\"icon-eye-close\"></i></a>";
	}

	echo $return;