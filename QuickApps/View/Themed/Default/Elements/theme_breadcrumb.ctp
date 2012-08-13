<?php
	$out = array();
	$home = array(
		'title' => __t('Home'),
		'url' => '/',
		'options' => array(
			'style' => (str_replace($this->base, '', $this->here) == '/' ? 'text-decoration:underline;' : '')
		)
	);
	$out[] = $this->Html->link($home['title'], $home['url'], $home['options']);

	foreach ($breadcrumb as $item) {
		$item['options']['style'] = $item['active'] ? 'text-decoration:underline;' : '';
		$out[] = $this->Html->link($item['title'], $item['url'], $item['options']);
	}

	if (!empty($out)) {
		echo implode(' » ', $out) . ' » ';
	}