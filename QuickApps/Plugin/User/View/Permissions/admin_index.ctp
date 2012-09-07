<?php
	if (isset($this->request->query['expand'])) {
		$this->Layout->script("
		$(document).ready(function () {
			$('span.app-{$this->request->query['expand']}').click();
		});", 'inline');
	}
?>

<div id="acos">
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

<div id="aco-edit"></div>