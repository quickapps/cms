<?php
    $links = array(
        array(__t('Languages'), '/admin/locale/languages', array('title' => __t('Configure languages for content and the user interface.'))),
        array(__t('Translatable entries'), '/admin/locale/translations/list', array('title' => __t('Translate interface'))),
        array(__t('Translation packages'), '/admin/locale/packages', array('title' => __t('Manage translation files packages')))
    );

    if ($this->request->params['controller'] == 'translations') {
        array_splice($links, 1, 0,
            array(
                array(__t('Add translatable entry'),
                    '/admin/locale/translations/add',
                    'options' => array('title' => __t('Add new translatable entry.'))
                )
            )
        );
    }

    echo $this->Layout->toolbar($links);