<?php
	$links = array(
		array(__t('New Content'), '/admin/node/contents/create'),
		array(__t('Comments'), '/admin/comment')
	);

	echo $this->Menu->toolbar($links);