<?php
	if (isset($this->request->query['expand'])) {
		$this->Layout->script("
		$(document).ready(function () {
			$('span.app-{$this->request->query['expand']}').click();
		});", 'inline');
	}
?>

<div class="span12 clearfix">
	<div id="acos" class="span6 pull-left well">
		<?php
			echo $this->Menu->render($results,
				array(
					'id' => 'acos-ul',
					'model' => 'Aco',
					'titlePath' => array('name', 'alias'),
					'element' => 'User.permission-node'
				)
			);
		?>
	</div>

	<div id="aco-edit" class="span6 pull-right well"></div>
</div>