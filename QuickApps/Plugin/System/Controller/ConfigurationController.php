<?php
/**
 * Configuration Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ConfigurationController extends AppController {
	public $name = 'Configuration';
	public $uses = array();

	public function admin_index() {
		if (isset($this->data['Variable'])) {
			$err = false;

			foreach ($this->data['Variable'] as $name => $value) {
				if ($name == 'url_language_prefix') {
					continue;
				}

				if ($name == 'site_name' && empty($value)) {
					$this->Variable->invalidate('site_name', 'Site name can not be blank');

					$err = true;
					break;
				} elseif ($name == 'site_mail' && (empty($value) || !Validation::email($value))) {
					$this->Variable->invalidate('site_mail', 'Invalid site email');

					$err = true;
					break;
				} else {
					$this->Variable->save(array('Variable' => array($name => $value)));
				}
			}

			if (!$err) {
				$this->flashMsg(__t('Configuration has been saved.'), 'success');
				$this->__setLangPrefix();
			} else {
				$this->flashMsg(__t('Configuration could not be saved. Please, try again.'), 'error');
			}

			$this->redirect('/admin/system/configuration');
		} else {
			$this->__setLangs();

			$data = array();
			$data['Variable'] = Configure::read('Variable');
			$this->data = $data;
		}
	}

	private function __setLangPrefix() {
		if (isset($this->data['Variable']['url_language_prefix'])) {
			if (is_writable(ROOT . DS . 'Config' . DS . 'core.php')) {
				App::import('Utility', 'File');

				$File = new File(ROOT . DS . 'Config' . DS . 'core.php', false);
				$core = $File->read();

				if (preg_match('/Configure\:\:write\(\'Variable\.url_language_prefix\'\,(.*)\)\;/s', $core, $match)) {
					$new = $this->data['Variable']['url_language_prefix'] ? "true" : "false";
					$core = str_replace($match[0], "Configure::write('Variable.url_language_prefix', {$new});", $core);
					$File->write($core);
					$File->close();

					Configure::write('Variable.url_language_prefix', $this->data['Variable']['url_language_prefix']);
				} else {
					$this->flashMsg(__t('The file `%s` appears to be invalid, `Variable.url_language_prefix` parameter not found.', ROOT . DS . 'Config' . DS . 'core.php'), 'alert');
				}
			} else {
				$this->flashMsg(__t('The file `%s` could not be written.', ROOT . DS . 'Config' . DS . 'core.php'), 'alert');
			}
		}
	}

	private function __setLangs() {
		$languages = array();

		foreach (Configure::read('Variable.languages') as $l) {
			$languages[$l['Language']['code']] = $l['Language']['native'];
		}

		$this->set('languages', $languages);
	}
}