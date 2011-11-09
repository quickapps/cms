<?php 
/**
 * Render full Node details based on viewMode
 *
 * @package QuickApps.Plugin.Node.View
 * @author Christopher Castro
 */
?>

<?php
    // node
    echo $this->Layout->renderNode();
    // end node

    // comments
    if ($Layout['node']['Node']['comment'] > 0) {
        $collect = $this->Layout->hook('before_render_node_comments', $this, array('collectReturn' => true));

        echo implode(' ', (array)$collect);

        $comments = $this->element('node' . DS . 'comments');

        if ($Layout['node']['Node']['comment'] == 2) {
            $comments .= $this->element('node' . DS . 'comments_form');
        }

        echo $this->Html->tag('div', $comments, array('id' => 'comments', 'class' => 'node-comments'));

        $collect = $this->Layout->hook('after_render_node_comments', $this, array('collectReturn' => true));

        echo implode(' ', (array)$collect);
    }
    // end comments