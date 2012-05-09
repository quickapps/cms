<?php
	$links = array(
		array(__t('Manage'), '/admin/system/modules'),
		array(__t('Load Order'), '/admin/system/modules/load_order')
	);

	echo $this->Layout->toolbar($links);