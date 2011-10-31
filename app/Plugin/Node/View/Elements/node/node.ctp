<?php 
/**
 * Render Node details for NodeType 'Basic Page' (page).
 * This element is rendered by node_content_page() hook.
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php echo $this->Html->tag('h2', $node['Node']['title'], array('class' => 'node-title')); ?>
<?php if ($node['NodeType']['node_show_author'] || $node['NodeType']['node_show_date']): ?>
    <div class="meta submitter">
        <span>
            <?php $node_time_zone = $this->Layout->loggedIn() ? $this->Session->read('Auth.User.timezone') : Configure::read('Variable.date_default_timezone');  ?>
            <?php echo $node['NodeType']['node_show_author'] ? __d('node', 'published by <a href="%s">%s</a>', $this->Html->url("/user/profile/{$node['CreatedBy']['username']}"), $node['CreatedBy']['username']) : ''; ?>
            <?php echo $node['NodeType']['node_show_date'] ? ' ' . __d('node', 'on %s',  $this->Time->format(__d('node', 'M d, Y H:i'), $node['Node']['created'], null, $node_time_zone)) : ''; ?>
        </span>
    </div>
<?php endif; ?>

<?php foreach ($node['Field'] as $field): ?>
    <?php echo $this->Layout->renderField($field); ?>
<?php endforeach; ?>

<?php if (!in_array($Layout['viewMode'], array('full', 'print', 'rss'))): ?>
    <div class="link-wrapper">
        <?php echo $this->Html->link('<span>' . __d('node', 'Read More') . '</span>', "/d/{$node['Node']['node_type_id']}/{$node['Node']['slug']}", array('class' => 'read-more', 'escape' => false)); ?>
    </div>
<?php endif; ?>