<?php
/**
 * Default breadcrumbs rendering.
 *
 * @package	 QuickApps.View.Elements
 * @author	 Christopher Castro <chris@quickapps.es>
 */
?>

<?php
	$out = array();

	if (!empty($breadcrumb) && is_array($breadcrumb)) {
		foreach ($breadcrumb as $item) {
			$item['options']['style'] = $item['active'] ? 'text-decoration:underline;' : '';
			$out[] = $this->Html->link($item['title'], $item['url'], $item['options']);
		}
	}

	if (!empty($out)) {
		echo implode(' » ', $out) . ' » ';
	}