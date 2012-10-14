<?php
/**
 * Modules Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ModulesController extends SystemAppController {
	public $name = 'Modules';
	public $uses = array('System.Module');
	public $components = array('Installer');

	public function beforeFilter() {
		if ($this->action == 'admin_load_order') {
			$this->Security->unlockedFields[] = 'Module';
		}

		parent::beforeFilter();
	}

	public function admin_index() {
		if (!is_writable(ROOT . DS . 'Modules' . DS)) {
			$this->flashMsg(__t('Your modules folder is not writable. %s', ROOT . DS . 'Modules' . DS), 'alert');
		}
	}

	public function admin_load_order() {
		if (isset($this->data['Module'])) {
			foreach ($this->data['Module'] as $i => $name) {
				$this->Module->save(
					array(
						'name' => $name,
						'ordering' => $i
					)
				);
			}

			Cache::delete('modules_load_order');
			Cache::delete('hook_objects_site_theme');
			Cache::delete('hook_objects_admin_theme');

			$this->redirect('/admin/system/modules/load_order/');
		}

		$modules = $this->Module->find('all',
			array(
				'conditions' => array('Module.status' => 1, 'Module.type' => 'module'),
				'fields' => array('Module.name', 'Module.type', 'Module.ordering'),
				'order' => array('Module.ordering' => 'ASC'),
				'recursive' => -1
			)
		);

		$this->jQueryUI->add('sortable');
		$this->jQueryUI->theme();

		$this->Layout['stylesheets']['all'][] = '/block/css/sortable.css';

		$this->set('modules', $modules);
		$this->setCrumb(
			'/admin/system/modules',
			array(__t('Load order'))
		);
		$this->title(__t('Load Order'));
	}

	public function admin_settings($module) {
		if (!Configure::read('Modules.' . $module) || strpos($module, 'Theme') === 0) {
			$this->redirect('/admin/system/modules');
		}

		if (isset($this->data['Module']['name']) || isset($this->data['Variable'])) {
			if (isset($this->data['Module']['name'])) {
				$this->Module->save($this->data);
				Cache::delete('Modules'); // regenerate modules cache
				$this->QuickApps->loadModules();
			}

			if (isset($this->data['Variable'])) {
				$this->Variable->save($this->data);
			}

			$this->flashMsg(__t('Module changes has been saved!'), 'success');
			$this->redirect($this->referer());
		}

		$data = array();
		$data['Module'] = Configure::read("Modules.{$module}");
		$this->data = $data;

		$this->setCrumb(
			'/admin/system/modules',
			array($data['Module']['yaml']['name']),
			array(__t('Settings'))
		);
		$this->title(__t('Configure Module'));
	}

	public function admin_toggle($plugin = false) {
		if (!$plugin) {
			$this->redirect('/admin/system/modules');
		}

		$plugin = Inflector::camelize($plugin);
		$to = Configure::read("Modules.{$plugin}.status") == 1 ? 0 : 1;

		if ($to) {
			if ($this->Installer->enableModule($plugin)) {
				$this->flashMsg(__t("Module '%s' has been enabled", $plugin), 'success');
			} else {
				$this->flashMsg(implode('<br />', $this->Installer->errors()), 'alert');
			}
		} else {
			if ($this->Installer->disableModule($plugin)) {
				$this->flashMsg(__t("Module '%s' has been disabled", $plugin), 'success');
			} else {
				$this->flashMsg(implode('<br />', $this->Installer->errors()), 'alert');
			}
		}

		$this->redirect($this->referer());
	}

	public function admin_uninstall($plugin = false) {
		if (!$plugin) {
			$this->redirect('/admin/system/modules');
		} elseif ($this->Installer->uninstall($plugin)) {
			$this->flashMsg(__t("Module '%s' has been uninstalled", $plugin), 'success');
		} else {
			$this->flashMsg(implode('<br />', $this->Installer->errors()), 'error');
		}

		$this->redirect('/admin/system/modules');
	}

	public function admin_install($zipball = null) {
		if (!isset($this->data['Package']['data']) && !$zipball) {
			$this->redirect('/admin/system/modules');
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

		if (!$this->Installer->install($this->data, array('type' => 'module', 'status' => $this->data['Package']['activate']))) {
			$errors = implode('<br />', $this->Installer->errors());
			$this->flashMsg("<b>" . __t('Module could not been installed') . ":</b><br/>{$errors}", 'error');
			$this->redirect('/admin/system/modules');
		} else {
			$this->flashMsg(__t('Module has been installed'), 'success');
			$this->redirect('/admin/system/modules#module-' . $this->Installer->options['__appName']);
		}
	}

/**
 * Regenerates permissions tree for the given module.
 * ADMIN ROLES ONLY can access this action.
 *
 * ### Usage
 *
 *     http://www.example.com/admin/system/modules/rebuild_acos/ModuleName
 *
 * @param string $module Module name, both are allowed Camelized or under_scored
 */
	public function admin_build_acos($module) {
		$module = Inflector::camelize($module);

		$this->Installer->buildAcos($module);
		die("{$module}: Permissions tree regenerated!");
	}
}