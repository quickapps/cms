<?php
/**
 * Locale View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class LocaleHookHelper extends AppHelper {
/**
 * Toolbar menu for section: `Languages`.
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		if (Router::getParam('admin') && $this->request->params['plugin'] == 'locale') {
			$this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- LocaleHookHelper -->'), 'toolbar');
		}

		return true;
	}

/**
 * Block: Language selector.
 *
 * @return array formatted block array
 */
	public function locale_language_switcher($block) {
		return array(
			'title' => false,
			'body' => $this->_View->element('Locale.locale_language_switcher', array('block' => $block))
		);
	}

/**
 * Block settings: Language selector.
 *
 * @return string HTML element
 */
	public function locale_language_switcher_settings($data) {
		return $this->_View->element('Locale.locale_language_switcher_settings', array('block' => $data));
	}
}