<?php
/**
 * Packages Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Locale.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class PackagesController extends LocaleAppController {
	public $name = 'Packages';
	public $uses = array();

	public function admin_index() {
		$modules = array();
		$modules['Default'] = __t('ALL');
		$field_modules = QuickApps::field_info();

		foreach (App::objects('plugin') as $plugin) {
			if (strpos($plugin, 'Theme') === 0) {
				if (Configure::read("Modules.{$plugin}.yaml.info.name")) {
					$modules[$plugin] = __t('Theme: %s', Configure::read("Modules.{$plugin}.yaml.info.name"));
				}
			} elseif (QuickApps::is('module.field', $plugin)) {
				$modules[$plugin] = __t('Field: %s', $field_modules[$plugin]['name']);
			} else {
				if (Configure::read("Modules.{$plugin}.yaml.name")) {
					$modules[$plugin] = __t('Module: %s', Configure::read("Modules.{$plugin}.yaml.name"));
				}
			}
		}

		$this->set('field_modules', $field_modules);
		$this->set('modules', $modules);
		$this->set('languages', $this->_languageList());
		$this->set('packages', $this->__packagesList());
		$this->setCrumb(
			'/admin/locale',
			array(__t('Translation packages'))
		);
		$this->title(__t('Translation Packages'));
	}

	public function admin_download_package($plugin, $language) {
		$plugin = Inflector::camelize($plugin);
		$language = strtolower($language);
		$err = false;
		$packagesList = $this->__packagesList();

		if (isset($packagesList[$plugin][$language])) {
			$file = $packagesList[$plugin][$language];

			if (file_exists($file)) {
				$this->viewClass = 'Media';
				$params = array(
					'id' => basename($file),
					'name' => basename($file),
					'download' => true,
					'extension' => 'po',
					'path' => dirname($file) . DS
				);
				$this->set($params);
			} else {
				$err = true;
			}
		} else {
			$err = true;
		}

		if ($err) {
			throw new NotFoundException(__t('Package not found'));
		}
	}

	public function admin_uninstall($plugin, $language) {
		$plugin = Inflector::camelize($plugin);
		$language = strtolower($language);
		$packagesList = $this->__packagesList();
		$file = $packagesList[$plugin][$language];

		if (file_exists($file)) {
			App::uses('File', 'Utility');

			$Folder = new File($packagesList[$plugin][$language]);

			if (!$Folder->delete()) {
				$this->flashMsg(__t("Could not delete package folder. Please check folder permissions for '%s'.", $ppath . 'Locale' . DS . $language . DS), 'error');
			} else {
				$this->flashMsg(__t('Language package removed!'), 'success');
			}
		} else {
			$this->flashMsg(__t('Invalid module or language '), 'error');
		}

		$this->redirect('/admin/locale/packages');
	}

	public function admin_install() {
		if (!isset($this->data['po']) || empty($this->data['module'])) {
			$this->redirect('/admin/locale/packages');
		}

		if (in_array($this->data['language'], array_keys($this->_languageList()))) {
			App::import('Vendor', 'Upload');

			$file_name = Inflector::underscore($this->data['module']);
			$destFolder = ROOT . DS . 'Locale' . DS . $this->data['language'] . DS . 'LC_MESSAGES' . DS;
			$Folder = new Folder;
			$Upload = new Upload($this->data['po']);
			$Upload->file_overwrite = true;
			$Upload->file_new_name_ext = 'po';
			$Upload->file_new_name_body = $file_name;
			$Upload->Process($destFolder);

			if (!$Upload->processed) {
				$this->flashMsg($Upload->error, 'error');
			} else {
				$this->flashMsg(__t('Language package upload success'), 'success');
			}
		}

		$this->redirect('/admin/locale/packages');
	}

	private function __packagesList($force = false) {
		$poFolders = array();
		$Locale = new Folder(ROOT . DS . 'Locale' . DS);
		$f = $Locale->read();
		$f = $f[0];
		$languages = $this->_languageList();

		foreach ($f as $langF) {
			if (!$force && !isset($languages[$langF])) {
				continue;
			}

			if (file_exists(ROOT . DS . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'default.po')) {
				$poFolders['Default'][$langF] = ROOT . DS . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'default.po';
			}
		}

		// Plugins .po
		foreach (App::objects('plugin') as $plugin) {
			if (!Configure::read("Modules.{$plugin}.yaml")) {
				continue;
			}

			foreach ($f as $langF) {
				if (!$force && !isset($languages[$langF])) {
					continue;
				}

				if (file_exists(ROOT . DS . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . Inflector::underscore($plugin) . '.po')) {
					$poFolders[$plugin][$langF] = ROOT . DS . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . Inflector::underscore($plugin) . '.po';
				}
			}
		}

		return $poFolders;
	}
}