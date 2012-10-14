<?php
	$links = array(
		array(__t('Installed Themes'), '/admin/system/themes'),
		array(__t('Themes Directory'), '/admin/system/directory/themes')
	);

	echo $this->Menu->toolbar($links);