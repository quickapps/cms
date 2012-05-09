<?php
/**
 * Locale Application Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class LocaleAppController extends AppController {
	protected function _languageList() {
		$list = array();
		$_languages = Configure::read('Variable.languages');

		foreach ($_languages as $l) {
			$list[$l['Language']['code']] = $l['Language']['native'];
		}

		return $list;
	}
}