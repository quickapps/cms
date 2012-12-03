<?php
/**
 * Installer Component
 *
 * PHP version 5
 *
 * @package	 QuickApps.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class InstallerComponent extends Component {
/**
 * Holds a list of all errors.
 *
 * @var array
 */
	private $__errors = array();

/**
 * Controller reference.
 *
 * @var Controller
 */
	public $Controller;

/**
 * Array of defaults options used by install process.
 *
 * - type: type of package to install, 'module' or 'theme'. (default 'module')
 * - status: set to 1 for "install and activate", set to zero (0) otherwise. (default 1)
 *
 * @var array
 */
	public $options = array(
		'name' => null,
		'type' => 'module',
		'status' => 1
	);

/**
 * Initializes InstallerComponent for use in the controller.
 *
 * @param Controller $controller A reference to the instantiating controller object
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;

		return true;
	}

/**
 * Begins install process for the specified package.
 * An update will be performed if the three conditions below are met:
 *
 * - Module/theme is already installed.
 * - The module/theme being installed is newer than the installed (higher version number).
 * - The module/theme has the `beforeUpdate` callback on its `InstallComponent` class.
 *
 * #### Expected module package estructure
 *
 * - ZIP/
 *	  - ModuleFolderName/
 *		  - Config/
 *			  - bootstrap.php
 *			  - routes.php
 *		  - Controller/
 *			  - Component/
 *				  - InstallComponent.php
 *		  - ModuleFolderName.yaml
 *
 * #### Expected theme package estructure
 *
 * - ZIP/
 *    - CamelCaseThemeName/
 *        - Layouts/
 *        - app/
 *            - ThemeCamelCaseThemeName/  (Note `Theme` prefix)
 *                ... (same as modules)
 *        - webroot/
 *        - CamelCaseThemeName.yaml
 *        - thumbnail.png  (206x150px recommended)
 *
 * @param array $data Data form POST submit of the .app package ($this->data)
 * @param array $options Optional settings, see InstallerComponent::$options
 * @return boolean TRUE on success or FALSE otherwise
 */
	public function install($data = false, $options = array()) {
		if (!$data) {
			return false;
		}

		$oldMask = umask(0);
		$this->options = array_merge($this->options, $options);
		$Folder = new Folder;

		if (is_string($data['Package']['data'])) {
			$workingDir = CACHE . 'installer' . DS . md5($data['Package']['data']) . DS;

			$Folder->delete($workingDir);

			// download from remote url
			if (!$this->__downloadPackage($data['Package']['data'], $workingDir . 'package' . DS)) {
				$this->logError(__t('Could not download package file.'));

				return false;
			} else {
				$file_dst_pathname = $workingDir . 'package' . DS . md5($data['Package']['data']) . '.zip';
			}
		} else {
			// Upload
			App::import('Vendor', 'Upload');

			$workingDir = CACHE . 'installer' . DS . $data['Package']['data']['name'] . DS;
			$Upload = new Upload($data['Package']['data']);
			$Upload->allowed = array('application/*');
			$Upload->file_overwrite = true;
			$Upload->file_src_name_ext = 'zip';

			$Folder->delete($workingDir);
			$Upload->Process($workingDir . 'package' . DS);

			if (!$Upload->processed) {
				$this->logError(__t('Package upload error.') . ": {$Upload->error}");

				return false;
			}

			$file_dst_pathname = $Upload->file_dst_pathname;
		}

		// Unzip & Install
		App::import('Vendor', 'PclZip');

		$PclZip = new PclZip($file_dst_pathname);

		$PclZip->delete(PCLZIP_OPT_BY_EREG, '/__MACOSX/');
		$PclZip->delete(PCLZIP_OPT_BY_EREG, '/\.DS_Store$/');
		
		
		if (!$v_result_list = $PclZip->extract(PCLZIP_OPT_PATH, $workingDir . 'unzip')) {
			$this->logError(__t('Unzip error.') . ": " . $PclZip->errorInfo(true));

			return false;
		} else {
			// Package Validation
			$Folder->path = $workingDir . 'unzip' . DS;
			$folders = $Folder->read();$folders = $folders[0];
			$packagePath = isset($folders[0]) && count($folders) === 1 ? $workingDir . 'unzip' . DS . str_replace(DS, '', $folders[0]) . DS : false;
			$appName = (string)basename($packagePath);

			// Look for GitHub Package:
			// QACMS-ModuleNameInCamelCase-branch_id. e.g. QACMS-ModuleName-1.0
			// parts = array(0 => 'QACMS', 1 => 'ModuleName', 2 => '1.0');
			$parts = explode('-', $appName);

			if (in_array('QACMS', $parts) && count($parts) > 1) {
				$i = count($parts) - 1;
				$appName = null;

				// last occurrence of `QACMS`
				while ($i >= 0) {
					if ($parts[$i] == 'QACMS') {
						$appName = $parts[$i + 1];
						break;
					}

					$i--;
				}

				if (empty($appName)) {
					$this->logError(__t('Invalid package structure after unzip.'));

					return false;
				}
			}

			$this->options['__packagePath'] = $packagePath;
			$this->options['__appName'] = $appName;

			if (!$packagePath) {
				$this->logError(__t('Invalid package structure after unzip.'));

				return false;
			}

			switch ($this->options['type']) {
				case 'module':
					default:
						$tests = array(
							'ForbiddenName' => array(
								'test' => (
									strpos('Theme', Inflector::camelize($appName)) !== 0 &&
									!in_array(Inflector::camelize($appName), array('Default')) &&
									strlen(Inflector::camelize($appName)) != 3 &&
									preg_match('/^[a-zA-Z0-9]+$/', Inflector::camelize($appName))
								),
								'header' => __t('Forbidden Names'),
								'msg' => __t('Forbidden module name "%s"', $appName, Inflector::camelize($appName))
							),
							'CamelCaseName' => array(
								'test' => (Inflector::camelize($appName) == $appName),
								'header' => __t('Theme name'),
								'msg' => __t('Invalid module name (got "%s", expected: "%s")', $appName, Inflector::camelize($appName))
							),
							'notAlreadyInstalled' => array(
								'test' => (
									$this->Controller->Module->find('count', array('conditions' => array('Module.name' => $appName, 'Module.type' => 'module'))) === 0 &&
									!file_exists(ROOT . DS . 'Modules' . DS . $appName)
								),
								'header' => __t('Already installed'),
								'msg' => __t('This module is already installed')
							),
							'Config' => array(
								'test' => file_exists($packagePath . 'Config'),
								'header' => __t('Config Folder'),
								'msg' => __t('"Config" folder not found')
							),
							'bootstrap' => array(
								'test' => file_exists($packagePath . 'Config' . DS . 'bootstrap.php'),
								'header' => __t('Bootstrap File'),
								'msg' => __t('"Config/bootstrap.php" file not found')
							),
							'routes' => array(
								'test' => file_exists($packagePath . 'Config' . DS . 'routes.php'),
								'header' => __t('Routes File'),
								'msg' => __t('"Config/routes.php" file not found')
							),
							'Controller' => array(
								'test' => file_exists($packagePath . 'Controller'),
								'header' => __t('Controller Folder'),
								'msg' => __t('"Controller" folder not found')
							),
							'Component' => array(
								'test' => file_exists($packagePath . 'Controller' . DS . 'Component'),
								'header' => __t('Component Folder'),
								'msg' => __t('"Component" folder not found')
							),
							'InstallComponent.php' => array(
								'test' => file_exists($packagePath . 'Controller' . DS . 'Component' . DS . 'InstallComponent.php'),
								'header' => __t('Installer File'),
								'msg' => __t('Installer file (InstallComponent.php) not found')
							),
							'yaml' => array(
								'test' => file_exists($packagePath . "{$appName}.yaml"),
								'header' => __t('YAML File'),
								'msg' => __t('YAML File (%s) not found', "{$appName}.yaml")
							)
						);
				break;

				case 'theme':
					$tests = array(
						'CamelCaseName' => array(
							'test' => (Inflector::camelize($appName) == $appName),
							'header' => __t('Theme name'),
							'msg' => __t('Invalid theme name (got "%s", expected: "%s")', $appName, Inflector::camelize($appName))
						),
						'notAlreadyInstalled' => array(
							'test' => (
								$this->Controller->Module->find('count', array('conditions' => array('Module.name' => 'Theme' . $appName, 'Module.type' => 'theme'))) === 0 &&
								!file_exists(THEMES . $appName)
							),
							'header' => __t('Already Installed'),
							'msg' => __t('This theme is already installed')
						),
						'Layouts' => array(
							'test' => file_exists($packagePath . 'Layouts'),
							'header' => __t('Layouts Folder'),
							'msg' => __t('"Layouts" folder not found')
						),
						'app' => array(
							'test' => file_exists($packagePath . 'app'),
							'header' => __t('app Folder'),
							'msg' => __t('"app" folder not found')
						),
						'plugin_app' => array(
							'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName),
							'header' => __t('Plugin app'),
							'msg' => __t('Module app ("%s") folder not found', 'Theme' . Inflector::camelize($appName))
						),
						'Config' => array(
							'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName . DS . 'Config'),
							'header' => __t('Config Folder'),
							'msg' => __t('"Config" folder not found')
						),
						'bootstrap' => array(
							'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName . DS . 'Config' . DS . 'bootstrap.php'),
							'header' => __t('Bootstrap File'),
							'msg' => __t('"Config/bootstrap.php" file not found')
						),
						'routes' => array(
							'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName . DS . 'Config' . DS . 'routes.php'),
							'header' => __t('Routes File'),
							'msg' => __t('"Config/routes.php" file not found')
						),
						'InstallComponent.php' => array(
							'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName .  DS . 'Controller' . DS . 'Component' . DS . 'InstallComponent.php'),
							'header' => __t('Installer File'),
							'msg' => __t('Installer file (InstallComponent.php) not found')
						),
						'webroot' => array(
							'test' => file_exists($packagePath . 'webroot'),
							'header' => __t('webroot Folder'),
							'msg' => __t('"webroot" folder not found')
						),
						'yaml' => array(
							'test' => file_exists($packagePath . "{$appName}.yaml"),
							'header' => __t('YAML File'),
							'msg' => __t('YAML File (%s) not found', "{$appName}.yaml")
						),
						'thumbnail' => array(
							'test' => file_exists($packagePath . 'thumbnail.png'),
							'header' => __t('Theme thumbnail'),
							'msg' => __t('Thumbnail image ("%s") not found', 'thumbnail.png')
						)
					);
				break;
			}

			// Load YAML
			$yaml = Spyc::YAMLLoad($packagePath . "{$appName}.yaml");

			// Install component
			$installComponentPath = $this->options['type'] == 'theme' ? $packagePath . 'app' . DS . 'Theme' . $appName . DS . 'Controller' . DS . 'Component' . DS : $packagePath . 'Controller' . DS . 'Component' . DS;
			$Install = $this->loadInstallComponent($installComponentPath);
			$doUpdate = !$tests['notAlreadyInstalled']['test'] && method_exists($Install, 'beforeUpdate');

			if ($doUpdate && $this->options['type'] == 'module') {
				$doUpdate = isset($yaml['version']) ? version_compare(Configure::read("Modules.{$appName}.yaml.version"), $yaml['version'], '<') : false;
			}

			if ($doUpdate) {
				unset($tests['notAlreadyInstalled']);
			}

			if (!$this->checkTests($tests)) {
				return false;
			}

			// YAML validation
			switch ($this->options['type']) {
				case 'module':
					default:
						$tests = array(
							'yaml' => array(
								'test' => (
									(isset($yaml['name']) && !empty($yaml['name'])) &&
									(isset($yaml['description']) && !empty($yaml['description'])) &&
									(isset($yaml['category']) && !empty($yaml['category'])) &&
									(isset($yaml['version']) &&  !empty($yaml['version'])) &&
									(isset($yaml['core']) && !empty($yaml['core']))
								),
								'header' => __t('YAML Validation'),
								'msg' => __t('Module configuration file (%s) appears to be invalid.', "{$appName}.yaml")
							)
						);
				break;

				case 'theme':
					$tests = array(
						'yaml' => array(
							'test' => (
									(isset($yaml['info']) && !empty($yaml['info'])) &&
									(isset($yaml['info']['name']) && !empty($yaml['info']['name'])) &&
									(isset($yaml['info']['core']) && !empty($yaml['info']['core'])) &&
									(isset($yaml['regions']) && !empty($yaml['regions'])) &&
									(isset($yaml['layout']) && !empty($yaml['layout']))
							),
							'header' => __t('YAML Validation'),
							'msg' => __t('Theme configuration file (%s) appears to be invalid.', "{$appName}.yaml")
						),
						'requiredRegions' => array(
							'test' => (
								!isset($yaml['info']['admin']) ||
								!$yaml['info']['admin'] ||
								(
									isset($yaml['info']['admin']) &&
									$yaml['info']['admin'] &&
									in_array('toolbar', array_keys($yaml['regions'])) &&
									in_array('help', array_keys($yaml['regions']))
								)
							),
							'header' => __t('Required regions'),
							'msg' => __t('Missing theme region(s) "toolbar" and/or "help". Are required for backend themes.')
						)
					);
				break;
			}

			if (!$this->checkTests($tests)) {
				$this->logError(__t('Invalid information file (.yaml)'));

				return false;
			}

			// Validate dependencies and required core version
			$core = $this->options['type'] == 'module' ? "core ({$yaml['core']})" : "core ({$yaml['info']['core']})";
			$r = $this->checkIncompatibility($this->parseDependency($core), Configure::read('Variable.qa_version'));

			if ($r !== null) {
				if ($this->options['type'] == 'module') {
					$this->logError(__t('This module is incompatible with your QuickApps version.'));
				} else {
				   $this->logError(__t('This theme is incompatible with your QuickApps version.'));
				}

				return false;
			}

			if (
				($this->options['type'] == 'theme' && isset($yaml['info']['dependencies']) && !$this->checkDependency($yaml['info']['dependencies'])) ||
				($this->options['type'] == 'module' && isset($yaml['dependencies']) && !$this->checkDependency($yaml['dependencies']))
			) {
				if ($this->options['type'] == 'module') {
					$this->logError(_t("This module depends on other modules that you do not have or doesn't meet the version required: %s", implode(', ', $yaml['dependencies'])));
				} else {
					$this->logError(__t("This theme depends on other modules that you do not have or doesn't meet the version required: %s", implode(', ', $yaml['info']['dependencies'])));
				}

				return false;
			}
			// end of dependencies check

			// Validate custom fields.
			// Only modules are allowed to define fields.
			if ($this->options['type'] == 'module' && file_exists($packagePath . 'Fields')) {
				$Folder = new Folder($packagePath . 'Fields');
				$fields = $Folder->read();
				$fieldErrors = false;

				if (isset($fields[0])) {
					$fields = $fields[0];

					foreach ($fields as $field) {
						if (strpos($field, $appName) === 0) {
							if (file_exists($packagePath . 'Fields' . DS . $field . DS . "{$field}.yaml")) {
								$yaml = Spyc::YAMLLoad($packagePath . 'Fields' . DS . $field . DS . "{$field}.yaml");

								if (!isset($yaml['name']) || !isset($yaml['description'])) {
									$fieldErrors = true;
									$this->logError(__t('invalid information file (.yaml). Field "%s"', $field));
								}
							} else {
								$fieldErrors = true;
								$this->logError(__t('Invalid field "%s". Information file (.yaml) not found.', $field));
							}
						} else {
							$fieldErrors = true;
							$this->logError(__t('Invalid field name "%s".', $field));
						}
					}
				}

				if ($fieldErrors) {
					return false;
				}
			}
			// End of field validations

			$r = true;

			if ($doUpdate) {
				$r = $Install->beforeUpdate();
			} elseif (method_exists($Install, 'beforeInstall')) {
				$r = $Install->beforeInstall();
			}

			if ($r !== true) {
				return false;
			}

			// Copy files
			$copyTo = ($this->options['type'] == 'module') ? ROOT . DS . 'Modules' . DS . $appName . DS : THEMES . $appName . DS;

			if (!$this->rcopy($packagePath, $copyTo)) {
				return false;
			}

			if (!$doUpdate) {
				// DB Logic
				$moduleData = array(
					'name' => ($this->options['type'] == 'module' ? $appName : 'Theme' . $appName),
					'type' => ($this->options['type'] == 'module' ? 'module' : 'theme' ),
					'status' => intval($this->options['status']),
					'ordering' => 0
				);

				if ($this->options['type'] == 'module') {
					$max_order = $this->Controller->Module->find('first',
						array(
							'conditions' => array('Module.type' => 'module'),
							'order' => array('Module.ordering' => 'DESC'),
							'recursive' => -1
						)
					);
					$max_order = $max_order ? $max_order['Module']['ordering'] + 1 : 0;
					$moduleData['ordering'] = $max_order;
				}

				$this->Controller->Module->save($moduleData); // register module

				// Build ACOS && Register module in core
				switch ($this->options['type']) {
					case 'module':
						CakePlugin::load($appName);
						$this->buildAcos($appName);
					break;

					case 'theme':
						$this->buildAcos('Theme' . $appName);
						App::build(array('plugins' => array(THEMES . $appName . DS . 'app' . DS)));
					break;
				}

				// copy block positions
				if ($this->options['type'] == 'theme') {
					$BlockRegion = ClassRegistry::init('Block.BlockRegion');

					$BlockRegion->bindModel(
						array(
							'belongsTo' => array(
								'Block' => array(
									'className' => 'Block.Block'
								)
							)
						)
					);

					$regions = $BlockRegion->find('all',
						array(
							'conditions' => array(
								'BlockRegion.theme' => Inflector::camelize(Configure::read('Variable.site_theme')),
								'BlockRegion.region' => array_keys($yaml['regions'])
							)
						)
					);

					foreach ($regions as $region) {
						if (strpos($region['Block']['module'], 'Theme') === 0) {
							continue;
						}

						$region['BlockRegion']['theme'] = $appName;
						$region['BlockRegion']['ordering']++;

						unset($region['BlockRegion']['id']);
						$BlockRegion->create();

						if ($BlockRegion->save($region['BlockRegion']) &&
							$region['Block']['id'] &&
							strpos($region['Block']['themes_cache'], ":{$appName}:") === false
						) {
							$region['Block']['themes_cache'] .= ":{$appName}:";
							$region['Block']['themes_cache'] = str_replace('::', ':', $region['Block']['themes_cache']);

							$BlockRegion->Block->save(
								array(
									'id' => $region['Block']['id'],
									'themes_cache' => $region['Block']['themes_cache']
								)
							);
						}
					}
				}
			}

			// Delete unziped package
			$Folder->delete($workingDir);

			// Finish
			if ($doUpdate) {
				if (method_exists($Install, 'afterUpdate')) {
					$Install->afterUpdate();
				}
			} elseif (!$doUpdate && method_exists($Install, 'afterInstall')) {
				$Install->afterInstall();
			}

			$this->__clearCache();
		}

		umask($oldMask);

		return true;
	}

/**
 * Uninstall module by name.
 *
 * @param string $pluginName
 *  Name of the plugin to uninstall, it may be either a theme-associated-module or module.
 *  Both formats are allowed CamelCase and under_scored. e.g.: `ModuleName` or `module_name`
 * @return boolean TRUE on success or FALSE otherwise
 */
	public function uninstall($pluginName = false) {
		if (!$pluginName ||
			!is_string($pluginName) ||
			!in_array($this->options['type'], array('module', 'theme'))
		) {
			return false;
		}

		$this->options['name'] = $pluginName;
		$Name = Inflector::camelize($this->options['name']);
		$pData = $this->Controller->Module->findByName($Name);

		if (!$pData) {
			$this->logError(__t('Module does not exist.'));

			return false;
		} elseif (in_array($Name, Configure::read('coreModules'))) {
			$this->logError(__t('Core modules can not be uninstalled.'));

			return false;
		}

		$dep = $this->checkReverseDependency($Name);

		if (count($dep)) {
			$this->logError(__t('This module can not be uninstalled, because it is required by: %s', implode('<br />', Hash::extract($dep, '{n}.name'))));

			return false;
		}

		// useful for before/afterUninstall
		$this->options['type'] = $pData['Module']['type'];
		$this->options['__data'] = $pData;
		$this->options['__path'] = $pData['Module']['type'] == 'theme' ? THEMES . preg_replace('/^Theme/', '', $Name) . DS . 'app' . DS . $Name . DS : CakePlugin::path($Name);
		$this->options['__Name'] = $Name;

		// check if can be deleted
		$folderpath = ($this->options['type'] == 'module') ? $this->options['__path'] : dirname(dirname($this->options['__path']));

		if (!$this->isRemoveable($folderpath)) {
			$this->logError(__t('This module can not be uninstalled because some files/folder can not be deleted, please check the permissions.'));

			return false;
		}

		// core plugins can not be deleted
		if (in_array($this->options['__Name'], array_merge(array('ThemeDefault', 'ThemeAdmin'), Configure::read('coreModules')))) {
			return false;
		}

		$pluginPath = $this->options['__path'];

		if (!file_exists($pluginPath)) {
			return false;
		}

		$Install =& $this->loadInstallComponent($pluginPath . 'Controller' . DS . 'Component' . DS);

		if (!is_object($Install)) {
			return false;
		}

		$r = true;

		if (method_exists($Install, 'beforeUninstall')) {
			$r = $Install->beforeUninstall();
		}

		if ($r !== true) {
			return false;
		}

		if (!$this->Controller->Module->deleteAll(array('Module.name' => $Name))) {
			return false;
		}

		/**
		 * System.Controller/ThemeController does not allow to delete in-use-theme,
		 * but for precaution we assign to Core Default ones if for some reason
		 * the in-use-theme is being deleted.
		 */
		if ($this->options['type'] == 'theme') {
			if (Configure::read('Variable.site_theme') == preg_replace('/^Theme/', '', $Name)) {
				ClassRegistry::init('System.Variable')->save(
					array(
						'name' => 'site_theme',
						'value' => 'Default'
					)
				);
			} elseif (Configure::read('Variable.admin_theme') == preg_replace('/^Theme/', '', $Name)) {
				ClassRegistry::init('System.Variable')->save(
					array(
						'name' => 'admin_theme',
						'value' => 'Admin'
					)
				);
			}
		}

		if (method_exists($Install, 'afterUninstall')) {
			$Install->afterUninstall();
		}

		$this->afterUninstall();

		return true;
	}

	public function enableModule($module) {
		return $this->__toggleModule($module, 1);
	}

	public function disableModule($module) {
		return $this->__toggleModule($module, 0);
	}

	public function afterUninstall() {
		$this->__clearCache();

		// delete all menus created by module/theme
		ClassRegistry::init('Menu.Menu')->deleteAll(
			array(
				'Menu.module' => $this->options['__Name']
			)
		);

		// delete foreign links
		$MenuLink = ClassRegistry::init('Menu.MenuLink');
		$links = $MenuLink->find('all',
			array(
				'conditions' => array(
					'MenuLink.module' => $this->options['__Name']
				)
			)
		);

		foreach ($links as $link) {
			$MenuLink->Behaviors->detach('Tree');
			$MenuLink->Behaviors->attach('Tree',
				array(
					'parent' => 'parent_id',
					'left' => 'lft',
					'right' => 'rght',
					'scope' => "MenuLink.menu_id = '{$link['MenuLink']['menu_id']}'"
				)
			);
			$MenuLink->removeFromTree($link['MenuLink']['id'], true);
		}

		// delete blocks created by module/theme
		ClassRegistry::init('Block.Block')->deleteAll(
			array(
				'Block.module' => $this->options['__Name']
			)
		);

		// delete module (and related fields) acos
		$this->__removeModuleAcos($this->options['__Name']);

		// delete node types created by module/theme
		ClassRegistry::init('Node.NodeType')->deleteAll(
			array(
				'NodeType.module' => $this->options['__Name']
			)
		);

		// delete blocks position & cache
		if ($this->options['type'] == 'theme') {
			$themeName = preg_replace('/^Theme/', '', $this->options['__Name']);
			$BlockRegion = ClassRegistry::init('Block.BlockRegion');

			$BlockRegion->bindModel(
				array(
					'belongsTo' => array(
						'Block' => array(
							'className' => 'Block.Block'
						)
					)
				)
			);

			$regions = $BlockRegion->find('all',
				array(
					'conditions' => array(
						'BlockRegion.theme' => $themeName
					)
				)
			);

			foreach ($regions as $region) {
				if ($BlockRegion->delete($region['BlockRegion']['id']) && $region['Block']['id']) {
					$region['Block']['themes_cache'] = str_replace(":{$themeName}:", ':', $region['Block']['themes_cache']);
					$region['Block']['themes_cache'] = str_replace('::', ':', $region['Block']['themes_cache']);

					$BlockRegion->Block->save(
						array(
							'id' => $region['Block']['id'],
							'themes_cache' => $region['Block']['themes_cache']
						)
					);
				}
			}

			Cache::delete("theme_{$themeName}_yaml");
		}

		// delete app folder
		$folderpath = $this->options['type'] == 'module' ? $this->options['__path'] : dirname(dirname($this->options['__path']));
		$Folder = new Folder($folderpath);

		$Folder->delete();
	}

/**
 * Parse a dependency for comparison by InstallerComponent::checkIncompatibility().
 *
 * ### Usage
 *
 *     parseDependency('foo (>=7.x-4.5-beta5, 3.x)');
 *
 * @param string $dependency A dependency string as example above
 * @return mixed
 *	An associative array with three keys as below, callers should pass this
 *	structure to `checkIncompatibility()`:
 *	-	`name`: includes the name of the thing to depend on (e.g. 'foo')
 *	-	`original_version`: contains the original version string
 *	-	`versions`: is a list of associative arrays, each containing the keys
 *		'op' and 'version'. 'op' can be one of: '=', '==', '!=', '<>', '<',
 *		'<=', '>', or '>='. 'version' is one piece like '4.5-beta3'.
 * @see InstallerComponent::checkIncompatibility()
 */
	public function parseDependency($dependency) {
		$p_op = '(?P<operation>!=|==|=|<|<=|>|>=|<>)?';
		$p_core = '(?:' . preg_quote(Configure::read('Variable.qa_version')) . '-)?';
		$p_major = '(?P<major>\d+)';
		$p_minor = '(?P<minor>(?:\d+|x)(?:-[A-Za-z]+\d*)?)';
		$value = array();
		$parts = explode('(', $dependency, 2);
		$value['name'] = trim($parts[0]);

		if (isset($parts[1])) {
			$value['original_version'] = ' (' . $parts[1];

			foreach (explode(',', $parts[1]) as $version) {
				if (preg_match("/^\s*{$p_op}\s*{$p_core}{$p_major}\.{$p_minor}/", $version, $matches)) {
					$op = !empty($matches['operation']) ? $matches['operation'] : '=';

					if ($matches['minor'] == 'x') {
						if ($op == '>' || $op == '<=') {
							$matches['major']++;
						}

						if ($op == '=' || $op == '==') {
							$value['versions'][] = array('op' => '<', 'version' => ($matches['major'] + 1) . '.x');
							$op = '>=';
						}
					}

					$value['versions'][] = array('op' => $op, 'version' => $matches['major'] . '.' . $matches['minor']);
				}
			}
		}

		return $value;
	}

/**
 * Check whether a version is compatible with a given dependency.
 *
 * @param array $v The parsed dependency structure from `parseDependency()`
 * @param string $current_version The version to check against (e.g.: 4.2)
 * @return mixed NULL if compatible, otherwise the original dependency version string that caused the incompatibility
 * @see InstallerComponent::parseDependency()
 */
	public function checkIncompatibility($v, $current_version) {
		if (!empty($v['versions'])) {
			foreach ($v['versions'] as $required_version) {
				if ((isset($required_version['op']) && !version_compare($current_version, $required_version['version'], $required_version['op']))) {
					return $v['original_version'];
				}
			}
		}

		return null;
	}

/**
 * Verify if the given list of modules & version are installed and actives.
 *
 * ### Usage
 *
 *     $dependencies = array(
 *         'ModuleOne (1.0)',
 *         'ModuleTwo (>= 1.0)',
 *         'ModuleThree (1.x)',
 *         'ModuleFour'
 *     );
 *
 *     checkDependency($dependencies);
 *
 * @param array $dependencies List of dependencies to check.
 * @return boolean
 *	TRUE if all modules are available.
 *	FALSE if any of the required modules is not installed/version-compatible
 */
	public function checkDependency($dependencies = array()) {
		if (empty($dependencies)) {
			return true;
		}

		if (is_array($dependencies)) {
			foreach ($dependencies as $p) {
				$d = $this->parseDependency($p);

				if (!$m = Configure::read('Modules.' . Inflector::camelize($d['name']))) {
					return false;
				}

				$check = $this->checkIncompatibility($d, $m['yaml']['version']);

				if ($check !== null) {
					return false;
				}
			}
		}

		return true;
	}

/**
 * Verify if there is any module that depends of `$module`.
 *
 * @param string $module Module alias
 * @param boolean $returnList
 *	Set to true to return an array list of all modules that uses $module.
 *	This list contains all the information of each module: Configure::read('Modules.{module}')
 * @return mixed
 *	Boolean If $returnList is set to false, a FALSE return means that there are no module that uses $module.
 *	Or an array list of all modules that uses $module when $returnList is set to true, an empty array is
 *	returned if there are no module that uses $module.
 */
	function checkReverseDependency($module, $returnList = true) {
		$list = array();
		$module = Inflector::camelize($module);

		foreach (Configure::read('Modules') as $p) {
			if (isset($p['yaml']['dependencies']) &&
				is_array($p['yaml']['dependencies'])
			) {
				$dependencies = array();

				foreach ($p['yaml']['dependencies'] as $d) {
					$dependencies[] = $this->parseDependency($d);
				}

				$dependencies = Hash::extract($dependencies, '{n}.name');
				$dependencies = array_map(array('Inflector', 'camelize'), $dependencies);

				if (in_array($module, $dependencies, true) && $returnList) {
					$list[] = $p;
				} elseif (in_array($module, $dependencies, true)) {
					return true;
				}
			}
		}

		if ($returnList) {
			return $list;
		}

		return false;
	}

/**
 * Loads plugin's installer component.
 *
 * @param string $search Path where to look for component
 * @return mix OBJECT Instance of component, Or FALSE if Component could not be loaded
 */
	public function loadInstallComponent($search = false) {
		if (!file_exists($search . 'InstallComponent.php')) {
			return false;
		}

		include_once($search . 'InstallComponent.php');

		$class = "InstallComponent";
		$component = new $class($this->Controller->Components);

		if (method_exists($component, 'initialize')) {
			$component->initialize($this->Controller);
		}

		if (method_exists($component, 'startup')) {
			$component->startup($this->Controller);
		}

		$component->Installer = $this;

		return $component;
	}

/**
 * Creates acos for specified module by parsing its Controller folder. (Module's fields are also analyzed).
 * If module is already installed then an Aco update will be performed.
 *
 * ### Usage
 *
 *     buildAcos('User');
 *
 * The above would generate all the permissions tree for the Core module User.
 *
 * @param string $plugin Plugin name to analyze (CamelCase or underscored)
 * @return void
 */
	public function buildAcos($plugin) {
		$plugin = Inflector::camelize($plugin);

		if (!CakePlugin::loaded($plugin)) {
			return false;
		}

		$pluginPath = CakePlugin::path($plugin);
		$__folder = new Folder;

		// Fields
		if (file_exists($pluginPath . DS . 'Fields')) {
			$__folder->path = $pluginPath . DS . 'Fields' . DS;
			$fieldsFolders = $__folder->read();
			$fieldsFolders = $fieldsFolders[0];

			foreach ($fieldsFolders as $field) {
				$this->buildAcos(basename($field));
			}
		}

		$cPath = $pluginPath . DS . 'Controller' . DS;
		$__folder->path = $cPath;
		$controllers = $__folder->read();
		$controllers = $controllers[1];

		if (count($controllers) === 0) {
			return false;
		}

		$appControllerPath = $cPath . $plugin . 'AppController.php';
		$acoExists = $this->Controller->Acl->Aco->find('first',
			array(
				'conditions' => array(
					'Aco.alias' => Inflector::camelize($plugin),
					'Aco.parent_id' => null
				)
			)
		);

		if ($acoExists) {
			$_controllers = $this->Controller->Acl->Aco->children($acoExists['Aco']['id'], true);

			// delete removed controllers (and all its methods)
			foreach ($_controllers as $c) {
				if (!in_array("{$c['Aco']['alias']}Controller.php", $controllers)) {
					$this->Controller->Acl->Aco->removeFromTree($c['Aco']['id'], true);
				}
			}

			$_controllersNames = Hash::extract($_controllers, '{n}.Aco.alias');
		}

		if (!$acoExists) {
			$this->Controller->Acl->Aco->create();
			$this->Controller->Acl->Aco->save(array('alias' => Inflector::camelize($plugin)));

			$_parent_id =  $this->Controller->Acl->Aco->getInsertID();
		} else {
			$_parent_id = $acoExists['Aco']['id'];
		}

		foreach ($controllers as $c) {
			if (strpos($c, 'AppController.php') !== false) {
				continue;
			}

			$alias = str_replace(array('Controller', '.php'), '', $c);
			$methods = $this->__getControllerMethods($cPath . $c, $appControllerPath);

			foreach ($methods as $i => $m) {
				if (strpos($m, '__') === 0 ||
					strpos($m, '_') === 0 ||
					in_array($m, array('beforeFilter', 'beforeRender', 'beforeRedirect', 'afterFilter'))
				) {
					unset($methods[$i]);
				}
			}

			if ($acoExists && in_array($alias, $_controllersNames)) {
				$controller = Hash::extract($_controllers, "{n}.Aco[alias={$alias}]");
				$_methods = $this->Controller->Acl->Aco->children($controller[0]['id'], true);

				// delete removed methods
				foreach ($_methods as $m) {
					if (!in_array($m['Aco']['alias'], $methods)) {
						$this->Controller->Acl->Aco->removeFromTree($m['Aco']['id'], true);
					}
				}

				$_methods = Hash::extract((array)$_methods, '{n}.Aco.alias');

				// add new methods
				foreach ($methods as $m) {
					if (!in_array($m, $_methods)) {
						$this->Controller->Acl->Aco->create();
						$this->Controller->Acl->Aco->save(
							array(
								'parent_id' => $controller[0]['id'],
								'alias' => $m
							)
						);
					}
				}
			} else {
				$this->Controller->Acl->Aco->create();
				$this->Controller->Acl->Aco->save(
					array(
						'parent_id' => $_parent_id,
						'alias' => $alias
					)
				);

				$parent_id =  $this->Controller->Acl->Aco->getInsertID();

				foreach ($methods as $m) {
					$this->Controller->Acl->Aco->create();
					$this->Controller->Acl->Aco->save(
						array(
							'parent_id' => $parent_id,
							'alias' => $m
						)
					);
				}
			}
		}
	}

/**
 * Check if all files & folders contained in `dir` can be removed.
 *
 * @param string $dir Path content to check
 * @return boolean TRUE if all files & folder can be removed. FALSE otherwise
 */
	public function isRemoveable($dir) {
		if (!is_writable($dir)) {
			return false;
		}

		$Folder = new Folder($dir);
		$read = $Folder->read(false, false, true);

		foreach ($read[1] as $file) {
			if (!is_writable($dir)) {
				return false;
			}
		}

		foreach ($read[0] as $folder) {
			if (!$this->isRemoveable($folder)) {
				return false;
			}
		}

		return true;
	}

/**
 * Check if all files & folders contained in `source` can be copied to `destination`
 *
 * @param string $src Path content to check
 * @param string $dst Destination path that $source should be copied to
 * @return boolean TRUE if all files & folder can be copied to `destination`. FALSE otherwise
 */
	public function packageIsWritable($src, $dst) {
		if (!file_exists($dst)) {
			return $this->packageIsWritable($src, dirname($dst));
		}

		$e = 0;
		$Folder = new Folder($src);
		$files = $Folder->findRecursive();

		if (!is_writable($dst)) {
			$e++;
			$this->logError(__t('path: %s, not writable', $dst));
		}

		foreach ($files as $file) {
			$file = str_replace($this->options['__packagePath'], '', $file);
			$file_dst = str_replace(DS . DS, DS, $dst . DS . $file);

			if (file_exists($file_dst) && !is_writable($file_dst)) {
				$e++;
				$this->logError(__t('path: %s, not writable', $file_dst));
			}
		}

		return ($e == 0);
	}

/**
 * Creates a new block and optionally assign it to the specified theme and region.
 *
 * ### Block options
 *
 * -	module (string) [required]: CamelCased module name which is creating the block.
 *		default: name of the module being installed/uninstalled, "Block" otherwise.
 * -	delta (string) [optional]: under_scored unique ID for block within a module.
 *		If not specfied, an auto-increment ID is automatically calculated for the given module. default: null
 * -	title (string) [optional]: custom title for the block. default: null
 * -	body (string) [optional]: block's content body, VALID ONLY WHEN `module` = "Block". default: null
 * -	description (string) [required]: brief description of your block, VALID ONLY WHEN `module` = "Block".
 *		default: same as module.
 * -	status (int) [optional]: block enabled status. 1 for enabled or 0 disabled. default: 1
 * -	visibility (int) [optional]: flag to indicate how to show blocks on pages. default: 0
 *			- 0: Show on all pages except listed pages
 *			- 1: Show only on listed pages
 *			- 2: Use custom PHP code to determine visibility
 * -	pages (string) [optional]: list of paths (one path per line) on which to include/exclude the block or PHP code
 *		depending on "visibility" setting. default: null
 * -	locale (array) [optional]: list of language codes. default: none
 * -	settings (array) [optional]: extra information used by the block. default: none
 *
 * ### Usage
 *
 *     $block = array(
 *         'title' => 'My block title',
 *         'body' => 'My block content',
 *         'module' => 'Block'
 *     );
 *
 *     createBlock($block, 'ThemeName.sidebar_left');
 *
 * The above will create a new Custom Block, and then assigned to the `sidebar_left` region of the `ThemeName` theme.
 *
 *     $block = array(
 *         'title' => 'Widget Title',
 *         'module' => 'CustomModule',
 *         'delta' => 'my_widget' // required!
 *     );
 *
 *     createBlock($block, 'ThemeName.sidebar_left');
 *
 * Similar as before, but this time it will create a Widget Block. In this case the 'delta' option is require and must
 * be unique within the `CustomModule` module.
 *
 * @param array $block Block information
 * @param string $_theme Optional "Theme.region" where to assign the block
 * @return boolean TRUE on success, FALSE otherwise
 */
	public function createBlock($block, $_theme = '') {
		$defaultModule = isset($this->options['__appName']) ? $this->options['__appName'] : 'Block';
		$Block = ClassRegistry::init('Block.Block');
		$block = array_merge(
			array(
				'title' => null,
				'body' => null,
				'description' => $defaultModule,
				'delta' => null,
				'module' => $defaultModule,
				'status' => 1,
				'visibility' => 0,
				'pages' => '',
				'locale' => array(),
				'settings' => array()
			), $block
		);

		$block['module'] = Inflector::camelize($block['module']);

		if (isset($block['id'])) {
			$block['delta'] = $block['id'];
			unset($block['id']);
		}

		if (isset($block['themes_cache'])) {
			unset($block['themes_cache']);
		}

		if (!$block['module']) {
			return false;
		}

		if ($block['module'] == 'Block') {
			unset($block['delta']);
		} else {
			$block['delta'] = Inflector::underscore($block['delta']);

			if (!$block['delta']) {
				$max_delta = $Block->find('first',
					array(
						'conditions' => array('Block.module' => $block['module']),
						'fields' => array('delta'),
						'order' => array('delta' => 'DESC')
					)
				);
				$max_delta = !empty($max_delta) ? (int)$max_delta['Block']['delta'] + 1 : 1;
				$block['delta'] = $max_delta;
			}
		}

		list($theme, $region) = pluginSplit($_theme);

		if (!empty($_theme)) {
			$block['themes_cache'] = ":{$theme}:";
		}

		$Block->create($block);

		if ($save = $Block->save()) {
			if (!empty($_theme)) {
				$BlockRegion = ClassRegistry::init('Block.BlockRegion');
				$data = array(
					'region' => $region,
					'block_id' => $save['Block']['id'],
					'theme' => $theme
				);

				$BlockRegion->create($data);
				$BlockRegion->save();
			}

			if ($block['body'] && $block['module'] == 'Block') {
				$BlockCustom = ClassRegistry::init('Block.BlockCustom');

				$BlockCustom->create(
					array(
						'block_id' => $save['Block']['id'],
						'body' => $block['body'],
						'description' => $block['description']
					)
				);
				$BlockCustom->save();
			}

			return true;
		}

		return false;
	}

/**
 * Recursively copy `source` to `destination`.
 *
 * @param string $src Path content to copy
 * @param string $dst Destination path that $source should be copied to
 * @return boolean TRUE on success. FALSE otherwise
 */
	public function rcopy($src, $dst) {
		$dir = opendir($src);

		$this->rmkdir($dst);

		while (false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . DS . $file)) {
					$this->rcopy($src . DS . $file, $dst . DS . $file);
				} else {
					if (!copy($src . DS . $file, $dst . DS . $file)) {
						return false;
					}
				}
			}
		}

		closedir($dir);

		return true;
	}

/**
 * Recursive make dir.
 *
 * @param string $pathname Folder path
 * @param mixed $mode (default 0777)
 * @return boolean
 */
	public function rmkdir($pathname, $mode = 0777) {
		if (is_array($pathname)) {
			foreach ($pathname as $path) {
				$this->rmkdir($path, $mode);
			}
		} else {
			is_dir(dirname($pathname)) || $this->rmkdir(dirname($pathname), $mode);

			return is_dir($pathname) || @mkdir($pathname, $mode);
		}
	}

/**
 * Insert a new link to specified menu.
 *
 * ### Usage
 *
 * Example of use on module install, the code below will insert a new link
 * to the backend menu (`management`):
 *
 *     menuLink(
 *         array(
 *             'link' => '/my_new_module/dashboard',
 *             'description' => 'This is a link to my new awesome module',
 *             'label' => 'My Awesome Module'
 *         ), 1
 *     );
 *
 * Notice that this example uses `1` as menu ID instead of `management`.
 *
 * @param array $link
 *	Associative array information of the link to add:
 *
 *  -	[parent|parent_id]: Parent link ID.
 *  -	[url|link|path|router_path]: Link url (href).
 *  -	[description]: Link description used as `title` attribute.
 *  -	[title|label|link_title]: Link text to show between tags: <a href="">TEXT</a>
 *  -	[module]: Name of the module that link belongs to,
 *			by default it is set to the name of module being installed or
 *			to `System` if method is called on non-install process.
 *
 * @param mixed $menu_id
 *	Set to string value to indicate the menu id slug, e.g.: `management`.
 *	Or set to one of the following integer values:
 *
 *  -	0: Main menu of the site.
 *  -	1: Backend menu (by default).
 *  -	2: Navigation menu.
 *  -	3: User menu.
 *
 * @param integer $move
 *	Number of positions to move the link after add.
 *	Negative values will move down, positive values will move up, zero value (0) wont move.
 * @return mixed Array information of the new inserted link. FALSE on failure
 */
	public function menuLink($link, $menu_id = 1, $move = 0) {
		$menu_id = is_string($menu_id) ? trim($menu_id) : $menu_id;
		$Menu = ClassRegistry::init('Menu.Menu');
		$MenuLink = ClassRegistry::init('Menu.MenuLink');

		if (is_integer($menu_id)) {
			switch ($menu_id) {
				case 0:
					default:
						$menu_id = 'main-menu';
				break;

				case 1:
					$menu_id = 'management';
				break;

				case 2:
					$menu_id = 'navigation';
				break;

				case 3:
					$menu_id = 'user-menu';
				break;
			}
		}

		if (!($menu = $Menu->findById($menu_id))) {
			return false;
		}

		// Column alias
		if (isset($link['path'])) {
			$link['router_path'] = $link['path'];
			unset($link['path']);
		}

		if (isset($link['url'])) {
			$link['router_path'] = $link['url'];
			unset($link['url']);
		}

		if (isset($link['link'])) {
			$link['router_path'] = $link['link'];
			unset($link['link']);
		}

		if (isset($link['label'])) {
			$link['link_title'] = $link['label'];
			unset($link['label']);
		}

		if (isset($link['title'])) {
			$link['link_title'] = $link['title'];
			unset($link['title']);
		}

		if (isset($link['parent'])) {
			$link['parent_id'] = $link['parent'];
			unset($link['parent']);
		}

		if (isset($this->options['__appName']) &&
			!empty($this->options['__appName']) &&
			!isset($link['module'])
		) {
			$link['module'] = $this->options['__appName'];
		}

		$__link = array(
			'parent_id' => 0,
			'router_path' => '',
			'description' => '',
			'link_title' => '',
			'module' => 'System',
			'target' => '_self',
			'expanded' => false,
			'status' => 1
		);

		$link = Hash::merge($__link, $link);
		$link['menu_id'] = $menu_id;

		$MenuLink->Behaviors->detach('Tree');
		$MenuLink->Behaviors->attach('Tree',
			array(
				'parent' => 'parent_id',
				'left' => 'lft',
				'right' => 'rght',
				'scope' => "MenuLink.menu_id = '{$menu_id}'"
			)
		);

		$MenuLink->create();

		$save = $MenuLink->save($link);

		if (is_integer($move) && $move !== 0) {
			if ($move > 0) {
				$MenuLink->moveUp($save['MenuLink']['id'], $move);
			} else {
				$MenuLink->moveDown($save['MenuLink']['id'], abs($move));
			}
		}

		return $save;
	}

/**
 * Defines a new content type and optionally attaches a list of fields to it.
 *
 * ### Usage
 *
 *     createContentType(
 *         array(
 *             'module' => 'Blog',  (OPTIONAL)
 *             'name' => 'Blog Entry',
 *             'label' => 'Entry Title'
 *         ),
 *         array(
 *             'FieldText' => array(
 *                 'name' => 'blog_body',
 *                 'label' => 'Entry Body'
 *             )
 *         )
 *     );
 *
 * Note that `module` key is OPTIONAL when the method is invoked
 * from an installation session. If `module` key is not set and this method is invoked
 * during an install process (e.g.: called from `afterInstall`) it will use the name of the module
 * being installed.
 *
 * Although this method should be used on `afterInstall` callback only:
 * If you are adding fields that belongs to the module being installed
 * MAKE SURE to use this method on `afterInstall` callback, this is after
 * module has been installed and its fields has been REGISTERED on the system.
 *
 * @param array $type Content Type information. see $__type
 * @param array $fields
 *	Optional Associative array of fields to attach to the new content type:
 *
 *     $fields = array(
 *         'FieldText' => array(
 *             'label' => 'Title',  [required]
 *             'name' => 'underscored_unique_name', [required]
 *             'required' => true, [optional]
 *             'description' => 'Help text, instructions.', [optional]
 *             'settings' => array(  [optional array of specific settings for this field.]
 *			       'extensions' => 'jpg,gif,png',
 *			       ...
 *             )
 *         ),
 *	       ...
 *     );
 *
 * Keys `label` and `name` are REQUIRED!
 *
 * @return mixed boolean FALSE on failure. NodeType array on success
 * @link https://github.com/QuickAppsCMS/QuickApps-CMS/wiki/Field-API
 */
	public function createContentType($type, $fields = array()) {
		$__type = array(
			// The module defining this node type
			'module' => (isset($this->options['__appName']) ? $this->options['__appName'] : false),
			// information
			'name' => false,
			'description' => '',
			'label' => false,
			// display format
			'author_name' => 0, // show publisher info.: NO
			'publish_date' => 0, // show publish date: NO
			// comments
			'comments' => 0, // comments: CLOSED (1: read only, 2: open)
			'comments_approve' => 0, // auto approve: NO
			'comments_per_page' => 10,
			'comments_anonymous' => 0,
			'comments_title' => 0, // allow comment title: NO
			// language
			'language' => '', // language: any
			// publishing
			'published' => 1, // active: YES
			'promote' => 0, // publish to front page: NO
			'sticky' => 0 // sticky at top of lists: NO
		);
		$type = array_merge($__type, $type);

		if (!$type['name'] ||
			!$type['label'] ||
			empty($type['module']) ||
			!CakePlugin::loaded($type['module'])
		) {
			return false;
		}

		$NodeType = ClassRegistry::init('Node.NodeType');
		$newType = $NodeType->save(
			array(
				'NodeType' => array(
					'module' => $type['module'],
					'name' => $type['name'],
					'description' => $type['description'],
					'title_label' => $type['label'],
					'node_show_author' => $type['author_name'],
					'node_show_date' => $type['publish_date'],
					'default_comment' => $type['comments'],
					'comments_approve' => $type['comments_approve'],
					'comments_per_page' => $type['comments_per_page'],
					'comments_anonymous' => $type['comments_anonymous'],
					'comments_subject_field' => $type['comments_title'],
					'default_language' => $type['language'],
					'default_status' => $type['published'],
					'default_promote' => $type['promote'],
					'default_sticky' => $type['sticky']
				)
			)
		);

		if ($newType) {
			if (!empty($fields)) {
				$NodeType->Behaviors->attach('Field.Fieldable', array('belongsTo' => "NodeType-{$newType['NodeType']['id']}"));

				foreach ($fields as $module => $data) {
					$data['field_module'] = $module;

					if (!$NodeType->attachFieldInstance($data)) {
						$NodeType->delete($newType['NodeType']['id']);

						return false;
					}
				}
			}

			return $newType;
		} else {
			return false;
		}
	}

/**
 * Execute an SQL statement.
 *
 * ### Usage
 *
 *     sql('DROP TABLE `#__table_to_remove`');
 *
 * NOTE: Remember to include the table prefix pattern on each query string. (`#__` by default)
 *
 * @param string $query SQL to execute
 * @param string $prefix_pattern Pattern to replace for database prefix. default to `#__`
 * @return boolean
 */
	public function sql($query, $prefix_pattern = '#__') {
		$dSource = $this->Controller->Module->getDataSource();
		$query = str_replace($prefix_pattern, $dSource->config['prefix'], $query);

		return $dSource->execute($query);
	}

/**
 * Log a single or multiple messages passed as arguments.
 *
 * ### Usage
 *
 *     logError('Error 1', 'Error 2');
 *
 * @return void
 */
	public function logError() {
		$messages = func_get_args();

		foreach ($messages as $m) {
			$this->__errors[] = $m;

			LogError($m);
		}
	}

/**
 * Return all errors logged using logError.
 *
 * @return array List of error messages
 */	
	public function errors() {
		return $this->__errors;
	}

/**
 * Checks the given test list.
 *
 * ### Usage
 *
 *     $tests = array(
 *         array('test' => true, 'header' => 'Test 1', 'msg' => 'Test 1 has failed'),
 *         array('test' => false, 'header' => 'Test 2', 'msg' => 'Test 2 has failed'),
 *         ...
 *     );
 *
 *     checkTests($tests);
 *
 * In the example above 'Test 1' is passed, but 'Test 2' will fail.  
 * The `test` key must be any valid boolean expression which will determinate
 * the success or failure of the test. For example:
 *
 *     [test] => (isset($variable_1) && isset($variable))
 *
 * Above, the test will pass the validation only if both variables are defined.
 *
 * @param array $tests Formatted list of tests to check
 * @param boolean $header Include title on message error on test failure.
 * @return boolean TRUE on success all tests, FALSE otherwise
 */
	public function checkTests($tests, $header = false) {
		$e = 0;

		foreach ($tests as $key => $test) {
			if (!$test['test']) {
				$e++;
				$errorMsg = $header ? "<b>{$test['header']}</b>: {$test['msg']}" : $test['msg'];
				$this->logError($errorMsg);
			}
		}

		return ($e == 0);
	}

/**
 * Removes all ACOs of the specified module, and ACOs of all its `Field`.
 *
 * @param string $module_name Name of the module in CamelCase
 * @return void
 */
	private function __removeModuleAcos($module_name) {
		$ppath = CakePlugin::path($module_name);

		if (file_exists($ppath . 'Fields')) {
			$Folder = new Folder($ppath . 'Fields');
			$fields = $Folder->read();

			if (isset($fields[0])) {
				foreach ($fields[0] as $field) {
					$this->__removeModuleAcos(basename($field));
				}
			}
		}

		$rootAco = $this->Controller->Acl->Aco->find('first',
			array(
				'conditions' => array(
					'Aco.alias' => $module_name,
					'Aco.parent_id' => null
				)
			)
		);

		$this->Controller->Acl->Aco->delete($rootAco['Aco']['id']);
	}

/**
 * Tries to Activate/Desactivate the specified module.
 *
 * @param string $module CamelCase or underscored_name of the module
 * @param integer $to Module status. 1 means Activate, 0 Desactivate
 * @return boolean TRUE on success. FALSE otherwise.
 */
	private function __toggleModule($module, $to) {
		$module = Inflector::camelize($module);
		$isTheme = strpos($module, 'Theme') === 0;
		$path =  $isTheme ? THEMES . preg_replace('/^Theme/', '', $module) . DS . 'app' . DS . $module . DS : CakePlugin::path($module);
		$yamlPath = $isTheme ? THEMES . preg_replace('/^Theme/', '', $module) . DS . preg_replace('/^Theme/', '', $module) . '.yaml' : CakePlugin::path($module) . "{$module}.yaml";
		$Install =& $this->loadInstallComponent($path . 'Controller' . DS . 'Component' . DS);
		$this->options = array(
			'name' => $module,
			'type' => ($isTheme ? 'theme' : 'module'),
			'status' => $to
		);

		if (!$Install) {
			$this->logError(__t('Module does not exist.'));

			return false;
		}

		if (!$to) {
			$dep = $this->checkReverseDependency($module);

			if (count($dep)) {
				$this->logError(__t('This module can not be disabled, because it is required by: %s', implode('<br />', Hash::extract($dep, '{n}.name'))));

				return false;
			}
		} else {
			$yaml = Spyc::YAMLLoad($yamlPath);
			$core = $isTheme ? "core ({$yaml['info']['core']})" : "core ({$yaml['core']})";
			$r = $this->checkIncompatibility($this->parseDependency($core), Configure::read('Variable.qa_version'));

			if ($r !== null) {
				if (!$isTheme) {
					$this->logError(__t('This module is incompatible with your QuickApps version.'));
				} else {
				   $this->logError(__t('This theme is incompatible with your QuickApps version.'));
				}

				return false;
			}

			if (
				($isTheme && isset($yaml['info']['dependencies']) && !$this->checkDependency($yaml['info']['dependencies'])) ||
				(!$isTheme && isset($yaml['dependencies']) && !$this->checkDependency($yaml['dependencies']))
			) {
				if ($this->options['type'] == 'module') {
					$this->logError(__t("This module depends on other modules that you do not have or doesn't meet the version required: %s", implode('<br/>', $yaml['dependencies'])));
				} else {
					$this->logError(__t("This theme depends on other modules that you do not have or doesn't meet the version required: %s", implode('<br/>', $yaml['info']['dependencies'])));
				}

				return false;
			}
		}

		$r = true;

		if ($to) {
			if (method_exists($Install, 'beforeEnable')) {
				$r = $Install->beforeEnable();
			}
		} else {
			if (method_exists($Install, 'beforeDisable')) {
				$r = $Install->beforeDisable();
			}
		}

		if ($r !== true) {
			return false;
		}

		// turn on/off related blocks
		ClassRegistry::init('Block.Block')->updateAll(
			array('Block.status' => $to),
			array('Block.status <>' => 0, 'Block.module' => $module)
		);

		// turn on/off related menu links
		ClassRegistry::init('Menu.MenuLink')->updateAll(
			array('MenuLink.status' => $to),
			array('MenuLink.status <>' => 0, 'MenuLink.module' => $module)
		);

		// turn on/off module
		$this->Controller->Module->updateAll(
			array('Module.status' => $to),
			array('Module.name' => $module)
		);

		if ($to) {
			if (method_exists($Install, 'afterEnable')) {
				$Install->afterEnable();
			}
		} else {
			if (method_exists($Install, 'afterDisable')) {
				$Install->afterDisable();
			}
		}

		$this->__clearCache();

		return true;
	}

/**
 * Get a list of methods for the spcified controller class.
 *
 * @param string $path Full path to the Controller class .php file
 * @param mixed $includeBefore
 *	Optional classes to load (include) before Controller class,
 *	use this to load classes which the Controller depends.
 *	- Array list of full paths for classes to include before Controller class is loaded.
 *	- String value to load a single class file before Controller class is loaded.
 *	- FALSE for load nothing.
 * @return array List of all controller's method names
 * @see InstallerComponent::buildAcos()
 */
	private function __getControllerMethods($path, $includeBefore = false) {
		$methods = array();

		if (file_exists($path)) {
			if ($includeBefore) {
				if (is_array($includeBefore)) {
					foreach ($includeBefore as $i) {
						if (file_exists($i)) {
							include_once $i;
						}
					}
				} elseif (is_string($includeBefore) && file_exists($includeBefore)) {
					include_once $includeBefore;
				}
			}

			include_once $path;

			$file = basename($path);
			$className = str_replace('.php', '', $file);
			$methods = QuickApps::get_this_class_methods($className);
		}

		return $methods;
	}

/**
 * Regenerate caches:
 *
 * - Modules
 * - Variables
 * - Hook-Objects mapping
 * - Modules loading order
 *
 * @return void
 */
	private function __clearCache() {
		// clear modules & variables
		Cache::delete('Modules');
		Cache::delete('Variable');

		// clear custom styles cache
		if ($this->options['type'] == 'theme') {
			$theme_name = preg_replace('/^theme_/', '', Inflector::underscore($this->options['name']));

			ClassRegistry::init('Block.Block')->clearCache();
			clearCache("theme__{$theme_name}", 'persistent', '');
		}

		// clear blocks cache
		clearCache('blocks', '', '');

		// clear objects map
		Cache::delete('hook_objects_admin_theme');
		Cache::delete('hook_objects_site_theme');

		// clear bootstrap plugin paths
		Cache::delete('plugin_paths');

		// clear core modules/themes list
		Cache::delete('core_modules');
		Cache::delete('core_themes');

		// clear loading order list
		Cache::delete('modules_load_order');

		if (isset($this->Controller->Variable) && $this->Controller->Variable->cacheSources) {
			clearCache('cake_model_*_list', 'models', '');
		}

		// regenerate modules & variables
		$this->Controller->QuickApps->loadModules();
		$this->Controller->QuickApps->loadVariables();
	}

/**
 * Download module/theme ZIP package from given URL.
 *
 * @param string $url URL of the package
 * @param string $dst Full path where to save the downloaded package
 * @return boolean. TRUE on success, FALSE otherwise
 */
	private function __downloadPackage($url, $dst) {
		App::uses('HttpSocket', 'Network/Http');

		$path = $dst . md5($url) . '.zip';
		$Foder = new Folder($dst, true);
		$uri = parse_url($url);
		$http = new HttpSocket(
			array(
				'host' => $uri['host'],
				'request' => array(
					'redirect' => true
				)
			)
		);
		$response = $http->get($uri);
		$fp = fopen($path, "w+");

		if (!$fp) {
			return false;
		} else {
			fwrite($fp, $response);
			fclose($fp);

			return true;
		}
	}
}