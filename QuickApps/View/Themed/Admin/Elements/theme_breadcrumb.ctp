<?php
	$out = array();

	foreach ($breadcrumb as $node) {
		$selected = $node['MenuLink']['router_path'] == str_replace($this->base, '', $this->here) ? 'text-decoration:underline;' : '';
		$out[] = $this->Html->link($node['MenuLink']['link_title'], $node['MenuLink']['router_path'], array('title' => $node['MenuLink']['description'], 'style' => $selected));
	}

	if (!empty($out)) {
		echo implode(' » ', $out) . ' » ';
	}