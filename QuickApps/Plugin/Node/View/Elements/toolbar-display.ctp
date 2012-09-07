<?php
	$links = array();

	foreach ($this->data['NodeType']['displayModes'] as $dm) {
		if ($info = QuickApps::displayModes("Node.{$dm}")) {
			$links[] = array(__t($info['label']), "/admin/node/types/display/{$this->data['NodeType']['id']}/{$dm}");
		}
	}

	if (!empty($links)) {
		echo $this->Menu->toolbar($links);
	}