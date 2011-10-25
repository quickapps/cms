<?php 
/**
 * Edit form for NodeType
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */ 
?>

<?php echo $this->Html->useTag('fieldsetstart', __t('Content')); ?>
    <?php foreach ($data['Field'] as $field): ?>
        <?php echo $this->Layout->hook(Inflector::underscore($field['field_module']) . "_edit", $field); ?>
    <?php endforeach; ?>
<?php echo $this->Html->useTag('fieldsetend'); ?>