<?php
	$links = array(
		array(__t('New Menu'), '/admin/menu/manage/add')
	);

	echo $this->Menu->toolbar($links);