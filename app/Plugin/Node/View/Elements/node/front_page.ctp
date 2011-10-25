<?php 
/**
 * Nodes list for front page
 * will render promoted nodes or 
 * 'frontpage' if it was set in configuration panel.
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */ 
?>

<?php if (!empty($Layout['node'])): # render nodes ?>
    <?php foreach ($Layout['node'] as $node): ?>
        <?php echo $this->Layout->renderNode($node); ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php echo $front_page; ?>
<?php endif; ?>