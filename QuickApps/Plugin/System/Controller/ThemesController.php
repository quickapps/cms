<?php
/**
 * Themes Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemesController extends SystemAppController {
	public $name = 'Themes';
	public $uses = array('Block.Block');
	public $components = array('Installer', 'System.ThemeCustomizer');
	public $helpers = array('System.ThemeCustomizer');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('serve_css');

		if ($this->action == 'admin_settings') {
			$this->Security->disabledFields[] = '__reset';
			$this->Security->disabledFields[] = '__save_css';
		}
	}

	public function serve_css($theme_name, $css) {
		Configure::write('debug', 0);
		header("Content-type: text/css", true);

		if ($cache = Cache::read("theme_{$theme_name}_{$css}", '__theme_css__')) {
			die($cache['content']);
		}

		die(' ');
	}

	public function admin_index() {
		if (!is_writable(ROOT . DS . 'Themes' . DS . 'Themed' . DS)) {
			$this->flashMsg(__t('Your themes folder is not writable. %s', ROOT . DS . 'Themes' . DS . 'Themed' . DS), 'alert');
		}

		$this->set('themes', $this->__availableThemes());
	}

	public function admin_set_theme($theme_name) {
		$data['Variable'] = array(
			'name' => 'site_theme',
			'value' => $theme_name
		);

		$themes = $this->__availableThemes();

		if (in_array($theme_name, array_keys($themes))) {
			if (isset($themes[$theme_name]['info']['admin']) && $themes[$theme_name]['info']['admin']) {
				$data['Variable']['name'] = 'admin_theme';
			}

			$this->Variable->save($data);
			Cache::delete('Variable');
			Cache::delete("hook_objects_{$data['Variable']['name']}");
			Cache::delete("theme_{$theme_name}_yaml");
			$this->QuickApps->loadVariables(); // IMPORTANT! regenerate cache
		}

		$this->Block->clearCache();
		$this->redirect('/admin/system/themes');
	}

	public function admin_settings($theme_name) {
		$themes = $this->__availableThemes();

		if (!in_array($theme_name, array_keys($themes))) {
			$this->redirect('/admin/system/themes');
		}

		if (isset($this->data['Module'])) {
			$this->ThemeCustomizer->savePost();
			$this->Module->save($this->data);
			Cache::delete('Modules');
			$this->QuickApps->loadModules();
			$this->redirect($this->referer());
		}

		$data['Module'] = Configure::read('Modules.' . 'Theme' . $theme_name);
		$this->data = $data;

		$this->setCrumb(
			'/admin/system/themes',
			array(__t('Theme settings'))
		);
		$this->title(__t('Configure Theme'));
		$this->set('theme_name', $theme_name);
	}

	public function admin_uninstall($theme) {
		$Theme = "Theme{$theme}";

		if (!in_array($theme, Configure::read('coreThemes'))) {
			if ($this->Installer->uninstall($Theme)) {
				$this->flashMsg(__t("Theme '%s' has been uninstalled", $theme), 'success');
			} else {
				$message = __t("Error uninstalling theme '%s'", $theme);

				foreach ($this->Installer->errors() as $e) {
					$message .= "<br />- {$e}";
				}

				$this->flashMsg($message, 'error');
			}
		}

		$this->redirect('/admin/system/themes');
	}

	public function admin_install($zipball = null) {
		if (!isset($this->data['Package']['data']) && !$zipball) {
			$this->redirect('/admin/system/themes');
		}

		if ($zipball) {
			$data = array(
				'Package' => array(
					'data' => base64_decode($zipball),
					'activate' => true
				)
			);
			$this->data = $data;
		}

		if (!$this->Installer->install($this->data, array('type' => 'theme'))) {
			$errors = implode('<br />', $this->Installer->errors());
			$this->flashMsg("<b>" . __t('Theme could not been installed') . ":</b><br/>{$errors}", 'error');
		} else {
			$this->flashMsg(__t('Theme has been installed'), 'success');

			Cache::delete("theme_{$this->Installer->options['__appName']}_yaml");
		}

		$this->redirect('/admin/system/themes');
	}

/**
 * Render theme thumbnail.
 *
 * @return void
 */
	public function admin_theme_tn($theme_name) {
		$this->viewClass  = 'Media';

		$params = array(
			'id' => 'thumbnail.png',
			'name' => 'thumbnail',
			'download' => false,
			'extension' => 'png',
			'path' => App::themePath($theme_name)
		);

		$this->set($params);
	}

/**
 * Return all available (installed) themes.
 *
 * @return array List of themes
 */
	private function __availableThemes() {
		$_themes = $this->Module->find('all', array('conditions' => array('Module.type' => 'theme')));
		$themes = array();

		foreach ($_themes as $theme) {
			$ThemeName = Inflector::camelize($theme['Module']['name']);
			$folder = preg_replace('/^Theme/', '', $ThemeName);
			$themes[$folder] = $theme['Module'];
			$yaml = App::themePath($folder) ."{$folder}.yaml";

			if (file_exists($yaml)) {
				$themes[$folder] = Hash::merge($themes[$folder], Spyc::YAMLLoad($yaml));
			}
		}

		return $themes;
	}
}