<?php echo $this->Form->create(); ?>
	<!-- New Term -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Add New Term') . '</span>'); ?>
		<div class="fieldset-toggle-container horizontalLayout" style="display:none;">
			<?php echo $this->Form->input('Term.name', array('required' => 'required', 'type' => 'text', 'label' => __t('Name *'))); ?>
			<?php echo $this->Form->input('Term.parent_id', array('type' => 'select', 'label' => __t('Parent term'), 'options' => $parents, 'escape' => false, 'empty' => __t('-- None --'))); ?>
			<?php echo $this->Form->input(__t('Save'), array('type' => 'submit', 'label' => false)); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php if (!empty($results)): ?>
	<?php $this->Layout->css('/menu/css/sortable-menu.css'); ?>
	<?php $this->Layout->script('/menu/js/nestedSortable/jquery-ui-1.8.11.custom.min.js'); ?>
	<?php $this->Layout->script('/menu/js/nestedSortable/jquery.ui.nestedSortable'); ?>
	<?php $this->Layout->script('/system/js/json.js'); ?>

	<div id="menu-sortContainer">
		<?php
			echo $this->Menu->generate($results,
				array(
					'id' => 'termsList',
					'class' => 'sortable',
					'element' => 'Taxonomy.term_node', 
					'model' => 'Term',
					'force' => true
				)
			);
		?>
	</div>

	<?php echo $this->Form->submit(__t('Save changes'), array('id' => 'saveChanges')); ?>
	<span id="saveStatus">&nbsp;</span>

	<script>
		$(document).ready(function() {
			$('ul.sortable').nestedSortable({
				listType: 'ul',
				disableNesting: 'no-nest',
				forcePlaceholderSize: true,
				handle: 'div',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div'
			});

			$('#saveChanges').click(function(e) {
				$('#saveStatus').text('<?php echo __t('Saving...'); ?>');
				arraied = $('ul.sortable').nestedSortable('toArray', {startDepthCount: 0});
				$.ajax({
					type: 'POST',
					url: QuickApps.settings.url,
					data: 'data[Term][sorting]=' + $.toJSON(arraied),
					success: function() {
						$('#saveStatus').text('<?php echo __t('Saved!'); ?>');
					}
				});
			});
		});
	</script>
<?php endif; ?>