<?php
	$links = array(
		array(__t('Manage'), '/admin/system/modules'),
		array(__t('Load Order'), '/admin/system/modules/load_order'),
		array(__t('Modules Directory'), '/admin/system/directory/modules')
	);

	echo $this->Menu->toolbar($links);