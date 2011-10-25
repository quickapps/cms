<?php 
/**
 * Render full Node details based on viewMode
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php
    // node
    echo $this->Layout->renderNode();
    // end node

    // comments
    if ($Layout['node']['Node']['comment'] > 0) {
        $collect = $this->Layout->hook('beforeRenderNodeComments', $this, array('collectReturn' => true));

        echo implode(' ', (array)$collect);

        $comments = $this->element('node' . DS . 'comments');

        if ($Layout['node']['Node']['comment'] == 2) {
            $comments .= $this->element('node' . DS . 'comments_form');
        }

        echo $this->Html->tag('div', $comments, array('id' => 'comments', 'class' => 'node-comments'));

        $collect = $this->Layout->hook('afterRenderNodeComments', $this, array('collectReturn' => true));

        echo implode(' ', (array)$collect);
    }
    // end comments