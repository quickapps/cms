<?php
	$links = array(
		array(__t('Languages'), '/admin/locale/languages', array('title' => __t('Configure languages for content and the user interface.'))),
		array(__t('Translatable entries'), '/admin/locale/translations/list', array('title' => __t('Translate interface')), 'pattern' => '*translations/import*'),
		array(__t('Translation packages'), '/admin/locale/packages', array('title' => __t('Manage translation files packages')))
	);

	if ($this->request->params['controller'] == 'translations') {
		array_splice($links, 1, 0,
			array(
				array(
					__t('Add translatable entry'),
					'/admin/locale/translations/add',
					'options' => array('title' => __t('Add new translatable entry.'))
				)
			)
		);

		array_splice($links, 2, 0,
			array(
				array(
					__t('Fuzzy entries'),
					'/admin/locale/translations/fuzzy_list',
					'options' => array('title' => __t('Fuzzy entries.')),
					'pattern' => '*fuzzy_list*'
				)
			)
		);
	}

	echo $this->Menu->toolbar($links);