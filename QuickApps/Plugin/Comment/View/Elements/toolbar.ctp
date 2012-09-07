<?php
	$links = array(
		array(__t('Published'), '/admin/comment/published', 'pattern' => '*admin/comment/list/show/published*'),
		array(__t('Unpublished (%s)', $countUnpublished), '/admin/comment/unpublished', 'pattern' => '*admin/comment/list/show/unpublished*')
	);

	echo $this->Menu->toolbar($links);