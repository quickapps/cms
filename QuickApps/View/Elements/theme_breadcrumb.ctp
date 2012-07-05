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

	foreach ($crumbs as $node) {
		$selected = $node['MenuLink']['router_path'] == str_replace($this->base, '', $this->here) ? 'text-decoration:underline;' : '';
		$out[] = $this->Html->link($node['MenuLink']['link_title'],
			$node['MenuLink']['router_path'],
			array(
				'title' => $node['MenuLink']['description'],
				'style' => $selected
			)
		);
	}

	if (empty($out)) {
		return '';
	}

	echo implode(' » ', $out) . ' » ';