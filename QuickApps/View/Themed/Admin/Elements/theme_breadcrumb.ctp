<?php
	$out = array();

	foreach ($breadcrumb as $item) {
		$item['options']['style'] = $item['active'] ? 'text-decoration:underline;' : '';
		$out[] = $this->Html->link($item['title'], $item['url'], $item['options']);
	}

	if (!empty($out)) {
		echo implode(' » ', $out) . ' » ';
	}