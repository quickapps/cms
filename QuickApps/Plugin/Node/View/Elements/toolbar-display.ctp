<?php
	$links = array();

	foreach ($this->data['NodeType']['displayModes'] as $id) {
		if ($info = QuickApps::displayModes($id)) {
			$links[] = array(__t($info['label']), "/admin/node/types/display/{$this->data['NodeType']['id']}/{$id}");
		}
	}

	if (!empty($links)) {
		echo $this->Layout->toolbar($links);
	}