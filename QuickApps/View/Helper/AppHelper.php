<?php
App::uses('Helper', 'View');

/**
 * Application Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class AppHelper extends Helper {
	public $helpers = array(
		'Node.Node',
		'Block.Block',
		'System.Layout',
		'Menu.Menu',
		'User.User',
		'Form' => array('className' => 'QaForm'),
		'Html' => array('className' => 'QaHtml'),
		'Js' => array('className' => 'QaJs'),
		'Session'
	);

	public $Options = array(
		'break' => false,
		'breakOn' => false,
		'collectReturn' => false
	);

	private $__Options = array(
		'break' => false,
		'breakOn' => false,
		'collectReturn' => false
	);

/**
 * Look for hooktags in basic site's layout-variables:
 *
 * - meta-description.
 * - site slogan.
 * - maintenance message.
 *
 * @param string $viewFile The view file that is going to be rendered
 * @return void
 */
	public function beforeRender($viewFile) {
		$site_description = $this->hooktags(Configure::read('Variable.site_description'));
		$site_maintenance_message = $this->hooktags(Configure::read('Variable.site_maintenance_message'));
		$site_slogan = $this->hooktags(Configure::read('Variable.site_slogan'));

		Configure::write('Variable.site_description', $site_description);
		Configure::write('Variable.site_maintenance_message', $site_maintenance_message);
		Configure::write('Variable.site_slogan', $site_slogan);
	}

/**
 * Wrapper method to HookCollectionHelper::attachModuleHooks()
 *
 * @see HookCollectionHelper::attachModuleHooks()
 */
	public function attachModuleHooks($module) {
		return $this->_View->HookCollection->attachModuleHooks($module);
	}

/**
 * Wrapper method to HooktagsCollectionHelper::attachModuleHooktags()
 *
 * @see HooktagsCollectionHelper::attachModuleHooktags()
 */
	public function attachModuleHooktags($module) {
		return $this->_View->HooktagsCollection->attachModuleHooktags($module);
	}

/**
 * Wrapper method to HookCollectionHelper::detachModuleHooks()
 *
 * @see HookCollectionHelper::detachModuleHooks()
 */
	public function detachModuleHooks($module) {
		return $this->_View->HookCollection->detachModuleHooks($module);
	}

/**
 * Wrapper method to HooktagsCollectionHelper::detachModuleHooktags()
 *
 * @see HooktagsCollectionHelper::detachModuleHooktags()
 */
	public function detachModuleHooktags($module) {
		return $this->_View->HooktagsCollection->detachModuleHooktags($module);
	}

/**
 * Wrapper method to QuickApps::is()
 *
 * @see QuickApps::is()
 */
	public function is() {
		$params = func_get_args();

		return call_user_func_array('QuickApps::is', $params);
	}

/**
 * Wrapper method to HookCollectionHelper::hook()
 *
 * @see HookCollectionHelper::hook()
 */
	public function hook($hook, &$data = array(), $options = array()) {
		return $this->_View->HookCollection->hook($hook, $data, $options);
	}

/**
 * Wrapper method to HooktagsCollectionHelper::hooktags()
 *
 * @see HooktagsCollectionHelper::hooktags()
 */
	public function hooktags($text) {
		return $this->_View->HooktagsCollection->hooktags($text);
	}

/**
 * Wrapper method to HookCollectionHelper::hookDefined()
 *
 * @see HookCollectionHelper::hookDefined()
 */
	public function hookDefined($hook) {
		return $this->_View->HookCollection->hookDefined($hook);
	}

/**
 * Wrapper method to HookCollectionHelper::hooktagDefined()
 *
 * @see HookCollectionHelper::hooktagDefined()
 */
	public function hooktagDefined($hooktag) {
		return $this->_View->HooktagsCollection->hooktagDefined($hooktag);
	}

/**
 * Wrapper method to HookCollectionHelper::hookEnable()
 *
 * @see HookCollectionHelper::hookEnable()
 */
	public function hookEnable($hook) {
		return $this->_View->HookCollection->hookEnable($hook);
	}

/**
 * Wrapper method to HooktagsCollectionHelper::hooktagEnable()
 *
 * @see HooktagsCollectionHelper::hooktagEnable()
 */
	public function hooktagEnable($hooktag) {
		return $this->_View->HooktagsCollection->hooktagEnable($hooktag);
	}

/**
 * Wrapper method to HookCollectionHelper::hookDisable()
 *
 * @see HookCollectionHelper::hookDisable()
 */
	public function hookDisable($hook) {
		return $this->_View->HookCollection->hookDisable($hook);
	}

/**
 * Wrapper method to HookCollectionHelper::hooktagsList()
 *
 * @see HookCollectionHelper::hooktagsList()
 */
	public function hooktagsList() {
		return $this->_View->HookCollection->hooktagsList();
	}

/**
 * Wrapper method to HooktagsCollectionHelper::hooktagDisable()
 *
 * @see HooktagsCollectionHelper::hooktagDisable()
 */
	public function hooktagDisable($hooktag) {
		return $this->_View->HooktagsCollection->hooktagDisable($hooktag);
	}

/**
 * Wrapper method to HooktagsCollectionHelper::stripHooktags()
 *
 * @see HooktagsCollectionHelper::stripHooktags()
 */
	public function stripHooktags($text) {
		return $this->_View->HooktagsCollection->stripHooktags($text);
	}

/**
 * Wrapper method to HooktagsCollectionHelper::specialTags()
 *
 * @see HooktagsCollectionHelper::specialTags()
 */
	public function specialTags($text) {
		return $this->_View->HooktagsCollection->specialTags($text);
	}

/**
 * Evaluate a string of PHP code.
 *
 * This is a wrapper around PHP's eval(). It uses output buffering to capture both
 * returned and printed text. Unlike eval(), we require code to be surrounded by
 * <?php ?> tags; in other words, we evaluate the code as if it were a stand-alone
 * PHP file.
 *
 * Using this wrapper also ensures that the PHP code which is evaluated can not
 * overwrite any variables in the calling code, unlike a regular eval() call.
 *
 * @param string $code The code to evaluate.
 * @return
 *  A string containing the printed output of the code, followed by the returned
 *  output of the code.
 */
	protected function php_eval($code) {
		ob_start();

		$Layout =& $this->_View->viewVars['Layout'];
		$View =& $this->_View;

		print eval('?>' . $code);

		$output = ob_get_contents();

		ob_end_clean();

		return (bool)$output;
	}
}