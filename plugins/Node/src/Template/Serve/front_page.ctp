<?php 
	$this->asViewMode('teaser', function () use ($nodes) {
		foreach ($nodes as $node) {
			echo $this->render($node);
		}
	});
