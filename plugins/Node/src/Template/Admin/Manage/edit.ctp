<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php echo $this->Form->create($node, ['id' => 'node-form']); ?>
	<fieldset>
		<legend><?php echo __d('node', 'Basic Information'); ?></legend>
			<?php echo $this->Form->input('title', ['label' => $node->node_type->title_label]); ?>
			<em class="help-block">
				<?php echo __d('node', 'Slug'); ?>: <?php echo __d('node', $node->slug); ?>,
				<?php echo __d('node', 'URL'); ?>: <?php echo $this->Html->link("/{$node->node_type_slug}/{$node->slug}.html", $node->url, ['target' => '_blank']); ?>
			</em>

			<?php if ($node->translation_of): ?>
			<em class="help-block">
				<strong><?php echo __d('node', 'This content is a translation of'); ?>: </strong><?php echo $this->Html->link($node->translation_of->title, ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $node->translation_of->id]); ?>
			</em>
			<?php endif; ?>

			<?php echo $this->Form->input('regenerate_slug', ['type' => 'checkbox', 'label' => __d('node', 'Regenerate Slug')]); ?>
			<em class="help-block"><?php echo __d('node', 'Check this to generate a new slug from title.'); ?></em>

			<?php echo $this->Form->input('description'); ?>
			<em class="help-block"><?php echo __d('node', 'A short description (200 chars. max.) about this content. Will be used as page meta-description when rendering this content node.'); ?></em>
	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Publishing'); ?></legend>
		<?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('node', 'Published')]); ?>
		<?php echo $this->Form->input('promote', ['type' => 'checkbox', 'label' => __d('node', 'Promoted to front page')]); ?>
		<?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __d('node', 'Sticky at top of lists')]); ?>
	</fieldset>

	<?php if (isset($node->_fields) && $node->_fields->count()): ?>
	<fieldset>
		<legend><?php echo __d('node', 'Content'); ?></legend>
		<?php foreach ($node->_fields as $field): ?>
			<?php echo $this->Form->input($field); ?>
		<?php endforeach; ?>
	</fieldset>
	<?php endif; ?>

	<fieldset>
		<legend><?php echo __d('node', 'Settings'); ?></legend>
			<?php echo $this->Form->input('comment_status', ['label' => __d('node', 'Comments'), 'options' => [1 => __d('node', 'Open'), 0 => __d('node', 'Closed'), 2 => __d('node', 'Read Only')]]); ?>

			<?php if (!$node->translation_for): ?>
				<?php echo $this->Form->input('language', ['label' => __d('node', 'Language'), 'options' => $languages, 'empty' => __d('node', '-- ANY --')]); ?>
			<?php else: ?>
				<?php echo $this->Form->label(null, __d('node', 'Language')); ?>
				<em class="help-block"><?php echo __d('node', 'This content is the ({0}) translation of an existing content originally in ({1}).', $node->language, $node->translation_of->language); ?></em>
			<?php endif; ?>

			<?php echo $this->Form->input('roles._ids', ['type' => 'select', 'label' => __d('node', 'Show content for specific roles'), 'options' => $roles, 'multiple' => 'checkbox']); ?>
			<em class="help-block"><?php echo __d('node', 'Show this content only for the selected role(s). If you select no roles, the content will be visible to all users.'); ?></em>
	</fieldset>

	<?php if ($node->has('node_revisions') && count($node->node_revisions)): ?>

	<hr />

	<fieldset>
		<legend><?php echo __d('node', 'Revisions'); ?></legend>
		
		<table class="table table-hover">
			<thead>
				<tr>
					<th><?php echo __d('node', 'Title'); ?></th>
					<th><?php echo __d('node', 'Description'); ?></th>
					<th><?php echo __d('node', 'Language'); ?></th>
					<th><?php echo __d('node', 'Revision date'); ?></th>
					<th><?php echo __d('node', 'Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($node->node_revisions as $revision): ?>
				<tr>
					<td><?php echo $revision->data->title; ?></td>
					<td><?php echo !empty($revision->data->description) ? $revision->data->description : '---'; ?></td>
					<td><?php echo $revision->data->language ? $revision->data->language : __d('node', '--any--'); ?></td>
					<td><?php echo $revision->created->format(__d('node', 'Y-m-d H:i:s')); ?></td>
					<td>
						<div class="btn-group">
							<?php
								echo $this->Html->link('', [
									'plugin' => 'Node',
									'controller' => 'manage',
									'action' => 'edit',
									$node->id, $revision->id
								], [
									'title' => __d('node', 'Load revision'),
									'class' => 'btn btn-default btn-sm glyphicon glyphicon-edit',
								]);
							?>
							<?php
								echo $this->Html->link('', [
									'plugin' => 'Node',
									'controller' => 'manage',
									'action' => 'delete_revision',
									$node->id,
									$revision->id
								], [
									'title' => __d('node', 'Delete revision'),
									'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
									'confirm' => __d('node', 'You are about to delete: "{0}". Are you sure ?', $revision->data->title),
								]);
							?>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			<tbody>
		</table>
	</fieldset>
	<?php endif; ?>

	<?php if (!$node->translation_for & $node->has('translations') && count($node->translations)): ?>
	<fieldset>
		<legend><?php echo __d('node', 'Translations'); ?></legend>
		<ul>
			<?php foreach ($node->translations as $translation): ?>
			<li><?php echo $this->Html->link($translation->title, ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $translation->id]); ?> (<?php echo $translation->language; ?>)</li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<?php endif; ?>

	<?php echo $this->Form->submit(__d('node', 'Save All')); ?>
<?php echo $this->Form->end(); ?>