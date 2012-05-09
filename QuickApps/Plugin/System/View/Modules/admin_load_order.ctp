<?php $_modules = Configure::read('Modules'); ?>

<?php echo $this->Form->create('Module'); ?>
	<ul class="sortable">
		<?php foreach($modules as $module): ?>
			<li class="ui-state-default">
				<input type="hidden" name="data[Module][]" value="<?php echo $module['Module']['name']; ?>" />

				<div class="fl">
					<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
				</div>

				<div class="fl" style="width:60%;">
					<?php echo $_modules[$module['Module']['name']]['yaml']['name']; ?> (<?php echo strtolower($module['Module']['name']); ?>)
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $this->Form->submit(__t('Update orders')); ?>
<?php echo $this->Form->end(); ?>

<script>
	$(".sortable").sortable().disableSelection();
</script>