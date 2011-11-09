<?php 
/**
 * Nodes list for front page. Will render promoted nodes or 
 * 'frontpage' if it was set in configuration panel.
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */ 
?>

<?php if (Configure::read('Variable.site_frontpage')): ?>
        <?php echo $front_page; ?>
    <?php elseif (!empty($Layout['node'])): ?>
        <?php foreach ($Layout['node'] as $node): ?>
            <?php echo $this->Layout->renderNode($node); ?>
        <?php endforeach; ?>
<?php endif; ?>
