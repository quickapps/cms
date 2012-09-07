<?php
/**
 * Nodes list for front page. Will render promoted nodes or
 * 'frontpage' if it was set in configuration panel.
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php
	if (Configure::read('Variable.site_frontpage')) {
		echo $front_page;
	} elseif (!empty($Layout['node'])) {
		foreach ($Layout['node'] as $node) {
			echo $this->Node->render($node);
		}
	}
?>