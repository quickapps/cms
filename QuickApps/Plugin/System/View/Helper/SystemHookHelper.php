<?php
/**
 * System View Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class SystemHookHelper extends AppHelper {
/**
 * Toolbar menu for section: `Modules`.
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		if ($this->is('view.admin') &&
			isset($this->request->params['plugin']) &&
			strtolower($this->request->params['plugin']) == 'system' &&
			$this->request->params['controller'] == 'modules'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar-modules')), 'toolbar');
		}

		if ($this->is('view.admin') &&
			isset($this->request->params['plugin']) &&
			strtolower($this->request->params['plugin']) == 'system' &&
			$this->request->params['controller'] == 'themes'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar-themes')), 'toolbar');
		}

		return true;
	}

/**
 * Block: `Powered by`.
 *
 * @return array formatted block array
 */
	public function system_powered_by() {
		return __t('Powered by &copy; <a href="http://www.quickappscms.org/">QuickApps CMS</a> v%s', Configure::read('Variable.qa_version'));
	}

/**
 * Block: Recent contents.
 *
 * @return array formatted block array
 */
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