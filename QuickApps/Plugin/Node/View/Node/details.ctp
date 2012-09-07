<?php
/**
 * Render Node details based on display type
 *
 * @package QuickApps.Plugin.Node.View
 * @author Christopher Castro <chris@quickapps.es>
 */
?>

<?php
	echo $this->Node->render();

	if ($Layout['node']['Node']['comment'] > 0) {
		$collect = $this->Layout->hook('before_render_node_comments', $this, array('collectReturn' => true));

		echo implode(' ', (array)$collect);

		$comments = $this->element('theme_node_comments');

		if ($Layout['node']['Node']['comment'] == 2) {
			$comments .= $this->element('theme_node_comments_form');
		}

		echo $this->Html->tag('div', $comments, array('id' => 'comments', 'class' => 'node-comments'));

		$collect = $this->Layout->hook('after_render_node_comments', $this, array('collectReturn' => true));

		echo implode(' ', (array)$collect);
	}