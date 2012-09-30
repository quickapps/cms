<?php
$tSettings = array(
	'columns' => array(
		'<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
			'value' => '<input type="checkbox" name="data[Items][id][]" value="{Comment.id}">',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '25', 'align' => 'center')
		),
		__t('Subject') => array(
			'value' => '<a href="' . $this->Html->url('/admin/comment/list/view/') .'{Comment.id}">{Comment.subject}</a>',
			'sort' => 'Comment.subject'
		),
		__t('Author') => array(
			'value' => '{php} return ("{Comment.name}" != "" ? "{Comment.name}" : "{User.name}"); {/php}',
			'sort' => 'User.name'
		),
		__t('Posted in') => array(
			'value' => '<a href="' . $this->Html->url('/admin/node/contents/edit/') . '{Node.slug}">{Node.title}</a>',
			'sort' => 'Node.title'
		),
		__t('Posted on') => array(
			'value' => '{php} return date("' . __t('Y/m/d - H:i') . '", {Comment.created}); {/php}',
			'sort' => 'Comment.created'
		)
	),
	'noItemsMessage' => __t('There are no comments to display'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Form->create(null, array('class' => 'form-inline')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Filter Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Comment']['filter']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Html->useTag('fieldsetstart', __t('Author')); ?>
				<?php echo $this->Form->input('Comment.filter.Comment|name',
						array(
							'type' => 'text',
							'label' => __t('Author’s name')
						)
					);
				?>

				<?php echo $this->Form->input('Comment.filter.Comment|mail',
						array(
							'type' => 'text',
							'label' => __t('Author’s e-mail'),
							'placeholder' => 'demo@example.com'
						)
					);
				?>

				<?php echo $this->Form->input('Comment.filter.Comment|hostname',
						array(
							'type' => 'text',
							'label' => __t('Author’s host name (IP)'),
							'placeholder' => env('REMOTE_ADDR')
						)
					);
				?>

				<?php echo $this->Form->input('Comment.filter.Comment|homepage',
						array(
							'type' => 'text',
							'label' => __t('Author’s web site'),
							'placeholder' => 'http://www.example.com/'
						)
					);
				?>
			<?php echo $this->Html->useTag('fieldsetend'); ?>

			<?php echo $this->Html->useTag('fieldsetstart', __t('Comment')); ?>
				<?php echo $this->Form->input('Comment.filter.Comment|subject',
						array(
							'type' => 'text',
							'label' => __t('Subject')
						)
					);
				?>

				<?php echo $this->Form->input('Comment.filter.Comment|body',
						array(
							'type' => 'text',
							'label' => __t('Comment’s body')
						)
					);
				?>
			<?php echo $this->Html->useTag('fieldsetend'); ?>

			<?php echo $this->Html->useTag('fieldsetstart', __t('Posted in')); ?>
				<?php echo $this->Form->input('Comment.filter.Node|title',
						array(
							'type' => 'text',
							'label' => __t('Content’s title')
						)
					);
				?>

				<?php echo $this->Form->input('Comment.filter.Node|slug',
						array(
							'type' => 'text',
							'label' => __t('Content’s slug')
						)
					);
				?>

				<?php echo $this->Form->input('Comment.filter.Node|id',
						array(
							'type' => 'text',
							'label' => __t('Content’s id')
						)
					);
				?>
			<?php echo $this->Html->useTag('fieldsetend'); ?>

			<?php echo $this->Form->submit(__t('Filter')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create(null, array('class' => 'form-inline', 'onsubmit' => 'return confirm("' . __t('Are you sure ?') . '");')); ?>
	<!-- Update -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Update Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Comment']['update']) ? '' : 'display:none;'; ?>">
			<?php
				$options = array(
					'approve' => __t('Approve selected comments'),
					'unapprove' => __t('Unapprove selected comments'),
					'delete' => __t('Delete selected comments')
				);

				if ($status == 'published') {
					unset($options['approve']);
				} else {
					unset($options['unapprove']);
				}

				echo $this->Form->input('Comment.update',
					array(
						'type' => 'select',
						'label' => false,
						'options' => $options
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Update')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->table($results, $tSettings); ?>
<?php echo $this->Form->end(); ?>