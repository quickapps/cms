<div class="row">
	<div class="col-md-5">
		<div class="thumbnail">
			<?php
				echo $this->Html->image([
					'plugin' => 'System',
					'controller' => 'themes',
					'action' => 'screenshot',
					$theme['name']
				], [
					'style' => 'width:100%;'
				]);
			?>
		</div>
	</div>
	<div class="col-md-7">
		<h1><?php echo $theme['human_name']; ?> <small>(v<?php echo $theme['composer']['version']; ?>)</small></h1>
		<em><?php echo $theme['composer']['description']; ?></em>

		<div class="extended-info">
			<?php echo $this->element('System.composer_details', ['composer' => $theme['composer']]); ?>
		</div>
	</div>
</div>