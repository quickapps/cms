<?php
/**
 * System View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class SystemHookHelper extends AppHelper {
    // Block
    public function system_powered_by() {
        return array(
            'body' => __t('Powered by &copy; QuickApps v%s', Configure::read('Variable.qa_version'))
        );
    }

    // Block
    public function system_language_selector($block = array()) {
        return array(
            'body' => $this->_View->element('system_language_selector', array('block' => $block))
        );
    }

    // Block
    public function system_recent_content($block = array()) {
        $Node = ClassRegistry::init('Node.Node');

        $Block = array(
            'title' => __t('Recent Content'),
            'body' => $this->_View->element(
                'system_recent_content',
                array(
                    'block' => $block,
                    'nodes' => $Node->find('all',
                        array(
                            'limit' => Configure::read('Variable.rows_per_page'),
                            'order' => array('Node.created' => 'DESC')
                        )
                    )
                ),
                array('plugin' => 'System')
            )
        );

        return $Block;
    }
}