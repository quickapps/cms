<?php
/**
 * Permissions Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class PermissionsController extends UserAppController {
	public $name = 'Permissions';
	public $uses = array('User.User');

	public function beforeFilter() {
		$this->QuickApps->disableSecurity();

		parent::beforeFilter();
	}

	public function admin_index() {
		$this->Layout['stylesheets']['all'][] = '/user/css/treeview.css';
		$this->Layout['javascripts']['file'][] = '/user/js/jquery.cookie.js';
		$this->Layout['javascripts']['file'][] = '/user/js/treeview.js';
		$this->Layout['javascripts']['file'][] = '/user/js/acos.js';
		$this->Layout['javascripts']['inline'][] = '$(document).ready(function() { $("#acos").treeview({collapsed: true}); });';
		$results = $this->Acl->Aco->find('threaded',
			array(
				'order' => array('lft' => 'ASC'),
				'recursive' => -1,
				'fields' => array('alias', 'id', 'lft', 'rght', 'parent_id')
			)
		);

		$this->__acosDetails($results);

		$this->set('results', $results);
		$this->setCrumb(
			'/admin/user/',
			array(__t('User Permissions'))
		);
		$this->title(__t('User Permissions'));
	}

	public function admin_edit($acoId) {
		if (is_string($acoId) && strpos($acoId, '.') !== false) {
			// preset
			list($module, $preset) = pluginSplit($acoId);

			if (CakePlugin::loaded($module)) {
				$ppath = CakePlugin::path($module);
				$isField = QuickApps::is('module.field', $module);
				$isTheme = QuickApps::is('module.theme', $module);

				if ($isField) {
					$m = array();
					$m['yaml'] = Spyc::YAMLLoad("{$ppath}{$module}.yaml");
				} else {
					$m = Configure::read('Modules.' . $module);
				}

				if ($isField) {
					$acoPath[] = __t('Field: %s', $m['yaml']['name']);
				} elseif ($isTheme) {
					$acoPath[] = __t('Theme: %s', $m['yaml']['name']);
				} else {
					$acoPath[] = __t('Module: %s', $m['yaml']['name']);
				}

				if ($yaml = $this->__permissionsYaml($module)) {
					if (isset($yaml['Preset'][$preset])) {
						$acoPath[] = $yaml['Preset'][$preset]['name'];
						$aros = array();
						$yaml['Preset'][$preset]['id'] = $acoId;

						foreach ($this->User->Role->find('all') as $role) {
							$aros[$role['Role']['name']] = array(
								'id' => $role['Role']['id'],
								'allowed' => $this->__presetStatus("{$module}.{$preset}", $role['Role']['id'])
							);
						}

						$this->set('aros', $aros);
						$this->set('preset', $yaml['Preset'][$preset]);
						$this->set('acoPath', $acoPath);
					}
				}
			}
		} else {
			// normal aco id
			$acoPath = $this->Acl->Aco->getPath($acoId);

			if (!$acoPath) {
				return;
			}

			$aros = array();

			$this->loadModel('Permission');

			foreach ($this->User->Role->find('all') as $role) {
				$hasAny = array(
					'aco_id' => $acoId,
					'aro_id' => $role['Role']['id'],
					'_create' => 1,
					'_read' => 1,
					'_update' => 1,
					'_delete' => 1
				);
				$aros[$role['Role']['name']] = array(
					'id' => $role['Role']['id'],
					'allowed' => (int)$this->Permission->hasAny($hasAny)
				);
			}

			$results = $this->Acl->Aco->find('all',
				array(
					'order' => array('lft' => 'ASC'),
					'recursive' => -1,
					'fields' => array('alias', 'id', 'lft', 'rght', 'parent_id')
				)
			);

			$this->__acosDetails($results);

			$this->set('acoPath', $acoPath);
			$this->set('aros', $aros);
		}
	}

	public function admin_toggle($acoId, $aroId) {
		if (is_string($acoId) && strpos($acoId, '.') !== false) {
			// preset
			$allowed = intval($this->__presetStatus($acoId, $aroId));
			$allowed = $allowed ? 0 : 1;

			$this->loadModel('Permission');

			foreach ($this->__presetAcosId($acoId) as $acoId) {
				$conditions = array(
					'Permission.aco_id' => $acoId,
					'Permission.aro_id' => $aroId,
				);

				if ($this->Permission->hasAny($conditions)) {
					$data = $this->Permission->find('first', array('conditions' => $conditions));

					// set preset status
					$data['Permission']['_create'] = $allowed;
					$data['Permission']['_read'] = $allowed;
					$data['Permission']['_update'] = $allowed;
					$data['Permission']['_delete'] = $allowed;
				} else {
					// create - CRUD with presetStatus
					$data = array();
					$data['Permission']['aco_id'] = $acoId;
					$data['Permission']['aro_id'] = $aroId;
					$data['Permission']['_create'] = $allowed;
					$data['Permission']['_read'] = $allowed;
					$data['Permission']['_update'] = $allowed;
					$data['Permission']['_delete'] = $allowed;

					$this->Permission->create();
				}

				$this->Permission->save($data);
			}

			$this->set('allowed', $allowed);
		} elseif ($aroId != 1) {
			$this->loadModel('Permission');

			$conditions = array(
				'Permission.aco_id' => $acoId,
				'Permission.aro_id' => $aroId,
			);

			if ($this->Permission->hasAny($conditions)) {
				$data = $this->Permission->find('first', array('conditions' => $conditions));

			   if ($data['Permission']['_create'] == 1 &&
					$data['Permission']['_read'] == 1 &&
					$data['Permission']['_update'] == 1 &&
					$data['Permission']['_delete'] == 1
				) {
					// from 1 to 0
					$data['Permission']['_create'] = 0;
					$data['Permission']['_read'] = 0;
					$data['Permission']['_update'] = 0;
					$data['Permission']['_delete'] = 0;
					$allowed = 0;
				} else {
					// from 0 to 1
					$data['Permission']['_create'] = 1;
					$data['Permission']['_read'] = 1;
					$data['Permission']['_update'] = 1;
					$data['Permission']['_delete'] = 1;
					$allowed = 1;
				}
			} else {
				// create - CRUD with 1
				$data = array();
				$data['Permission']['aco_id'] = $acoId;
				$data['Permission']['aro_id'] = $aroId;
				$data['Permission']['_create'] = 1;
				$data['Permission']['_read'] = 1;
				$data['Permission']['_update'] = 1;
				$data['Permission']['_delete'] = 1;
				$allowed = 1;
			}

			$this->Permission->save($data);
			$this->set('allowed', $allowed);
		} else {
			$this->set('allowed', 1);
		}
	}

/**
 * Returns all ID of the acos that belongs to the specified preset.
 *
 * @param string $preset Dot-Syntax `module.preset_name`
 */ 
	private function __presetAcosId($preset) {
		list($module, $preset) = pluginSplit($preset);
		$ids = array();
		$module = $this->Acl->Aco->find('first',
			array(
				'conditions' => array('Aco.alias' => $module, 'Aco.parent_id' => null),
				'recursive' => -1
			)
		);

		if ($yaml = $this->__permissionsYaml($module['Aco']['alias'])) {
			if (isset($yaml['Preset'][$preset]['acos'])) {
				foreach ($yaml['Preset'][$preset]['acos'] as $a) {
					list($controller, $action) = pluginSplit($a);
					$controller = $this->Acl->Aco->find('first',
						array(
							'conditions' => array('Aco.alias' => $controller, 'Aco.parent_id' => $module['Aco']['id']),
							'recursive' => -1
						)
					);

					$action = $this->Acl->Aco->find('first',
						array(
							'conditions' => array('Aco.alias' => $action, 'Aco.parent_id' => $controller['Aco']['id']),
							'recursive' => -1
						)
					);

					$ids[] = $action['Aco']['id'];
				}
			}
		}

		return $ids;
	}

/**
 * Checks if all preset's acos are allowed to the specified role.
 * A preset is considered allowed only if all its acos are allowed.
 *
 * @param string $preset Dot-Syntax `module.preset_name`
 */ 
	private function __presetStatus($preset, $role_id) {
		$acos = $this->__presetAcosId($preset);
		$allowed = false;

		$this->loadModel('Permission');

		foreach ($acos as $aco) {
			$hasAny = array(
				'aco_id' => $aco,
				'aro_id' => $role_id,
				'_create' => 1,
				'_read' => 1,
				'_update' => 1,
				'_delete' => 1
			);

			// preset will be considered ON only if all its acos are ON
			$allowed = (int)$this->Permission->hasAny($hasAny);

			if (!$allowed) {
				break;
			}
		}

		return $allowed;
	}

	private function __permissionsYaml($module) {
		if (CakePlugin::loaded($module)) {
			$ppath = CakePlugin::path($module);

			if (file_exists("{$ppath}Permissions.yaml")) {
				$yaml = Spyc::YAMLLoad("{$ppath}Permissions.yaml");

				return $yaml;
			}
		}

		return false;
	}

/**
 * Prepares permissions tree. Looks for name/descriptions entries on YAML.
 * Sets view variable `acos_details` used by admin_edit()
 *
 * @param array $results Result from Model::find
 * @param array $list Holds the output result. Used internally by recursive calls
 * @param array $acosYaml Holds a list of YAML array of each app. Used internally by recursive calls
 * @return void
 */
	private function __acosDetails(&$results, &$list = array(), &$acosYaml = array()) {
		foreach ($results as $key => &$aco) {
			$list[$aco['Aco']['id']] = $aco['Aco'];

			if (!$aco['Aco']['parent_id']) {
				// module
				if (CakePlugin::loaded($aco['Aco']['alias'])) {
					$yaml = array();
					$ppath = CakePlugin::path($aco['Aco']['alias']);
					$isField = strpos($ppath, DS . 'Fields' . DS);
					$isTheme = strpos($ppath, DS . 'Themed' . DS);

					if ($isField) {
						$m = array();
						$m['yaml'] = Spyc::YAMLLoad("{$ppath}{$aco['Aco']['alias']}.yaml");
					} else {
						$m = Configure::read('Modules.' . $aco['Aco']['alias']);
					}

					if ($isField) {
						$aco['Aco']['name'] = __t('Field: %s', $m['yaml']['name']);
					} elseif ($isTheme) {
						$aco['Aco']['name'] = __t('Theme: %s', $m['yaml']['name']);
					} else {
						$aco['Aco']['name'] = __t('Module: %s', $m['yaml']['name']);
					}

					$aco['Aco']['description'] = $m['yaml']['description'];

					if (file_exists("{$ppath}Permissions.yaml")) {
						$acosYaml[$aco['Aco']['id']] = $yaml = Spyc::YAMLLoad("{$ppath}Permissions.yaml");
					}

					if (isset($yaml['Preset'])) {
						foreach ($yaml['Preset'] as $pname => $pdata) {
							if (isset($pdata['name']) && isset($pdata['acos'])) {
								$results[$key]['children'][] = array(
									'Aco' => array(
										'id' => "{$aco['Aco']['alias']}.{$pname}",
										'alias' => $pdata['name'],
										'parent_id' => $aco['Aco']['id']
									)
								);
							}
						}
					}
				} else {
					$aco['Aco']['name'] = $aco['Aco']['alias'];
					$aco['Aco']['description'] = '';
				}
			} else {
				if (isset($acosYaml[$aco['Aco']['parent_id']])) {
					// controller
					$yaml = $acosYaml[$aco['Aco']['parent_id']];

					if (isset($yaml['Controller'][$aco['Aco']['alias']]['hidden']) &&
						$yaml['Controller'][$aco['Aco']['alias']]['hidden']
					) {
						unset($results[$key]);
						continue;
					}

					$aco['Aco']['name'] = isset($yaml['Controller'][$aco['Aco']['alias']]['name']) ? $yaml['Controller'][$aco['Aco']['alias']]['name'] : $aco['Aco']['alias'];
					$aco['Aco']['description'] = isset($yaml['Controller'][$aco['Aco']['alias']]['description']) ? $yaml['Controller'][$aco['Aco']['alias']]['description'] : '';
				} else {
					// method
					$controller = $list[$aco['Aco']['parent_id']];
					$yaml = isset($acosYaml[$controller['parent_id']]) ? $acosYaml[$controller['parent_id']] : array();

					if (isset($yaml['Controller'][$controller['alias']]['actions'][$aco['Aco']['alias']]['hidden']) &&
						$yaml['Controller'][$controller['alias']]['actions'][$aco['Aco']['alias']]['hidden']
					) {
						unset($results[$key]);
						continue;
					}

					$aco['Aco']['name'] = isset($yaml['Controller'][$controller['alias']]['actions'][$aco['Aco']['alias']]['name']) ? $yaml['Controller'][$controller['alias']]['actions'][$aco['Aco']['alias']]['name']: $aco['Aco']['alias'];
					$aco['Aco']['description'] = isset($yaml['Controller'][$controller['alias']]['actions'][$aco['Aco']['alias']]['description']) ? $yaml['Controller'][$controller['alias']]['actions'][$aco['Aco']['alias']]['description'] : '';
				}
			}

			$list[$aco['Aco']['id']] = $aco['Aco'];

			if (isset($aco['children']) && !empty($aco['children'])) {
				$this->__acosDetails($results[$key]['children'], $list, $acosYaml);
			}
		}

		$this->set('acos_details', $list);
	}
}