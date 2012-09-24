<?php
/**
 * Manage Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ManageController extends BlockAppController {
	public $name = 'Manage';
	public $uses = array('Block.Block', 'User.Role');

	public function beforeFilter() {
		if ($this->action == 'admin_index') {
			$this->Security->unlockedFields[] = 'BlockRegion';
		}

		parent::beforeFilter();
	}

	public function admin_index() {
		if (isset($this->data['BlockRegion'])) {
			foreach ($this->data['BlockRegion'] as $theme => $regions) {
				foreach ($regions as $region_name => $block_regions) {
					foreach ($block_regions as $i => $id) {
						$this->Block->BlockRegion->id = $id;
						$this->Block->BlockRegion->saveField('ordering', $i, false);
					}
				}
			}

			$this->Block->clearCache();
			$this->redirect('/admin/block');
		}

		$site_theme = $this->Block->find('all',
			array(
				'conditions' => array(
					'Block.themes_cache LIKE' => '%:' . Configure::read('Variable.site_theme') . ":%",
					'Block.status' => 1
				)
			)
		);

		$admin_theme = $this->Block->find('all',
			array(
				'conditions' => array(
					'Block.themes_cache LIKE' => '%:' . Configure::read('Variable.admin_theme') . ":%",
					'Block.status' => 1
				)
			)
		);

		$site_ids = (array)Hash::extract($site_theme, '{n}.Block.id');
		$admin_ids = (array)Hash::extract($admin_theme, '{n}.Block.id');
		$unassigned = $this->Block->find('all',
			array(
				'conditions' => array(
					'OR' => array(
						'NOT' => array(
							'Block.id' => array_merge($site_ids, $admin_ids)
						),
						'Block.status' => 0
					)
				)
			)
		);

		$this->jQueryUI->add('sortable');
		$this->jQueryUI->theme();

		$this->Layout['stylesheets']['all'][] = '/block/css/sortable.css';

		$this->set('site_theme', (array)$site_theme);
		$this->set('admin_theme', (array)$admin_theme);
		$this->set('unassigned', (array)$unassigned);
		$this->title(__t('Blocks'));
		$this->set('themes', $this->__themesYaml());
		$this->setCrumb('/admin/block');
	}

	public function admin_move($block_region_id, $dir) {
		if (in_array($dir, array('up', 'down'))) {
			if ($dir == 'up') {
				$this->Block->BlockRegion->move($block_region_id, 'up');
			} else {
				$this->Block->BlockRegion->move($block_region_id, 'down');
			}
		}

		$this->redirect($this->referer());
	}

	public function admin_clone($bid) {
		$block = $this->Block->findById($bid) or $this->redirect($this->referer());
		$block = Hash::filter($block);
		$block['Block']['themes_cache'] = '';
		$block['Block']['title'] .= ' (' . __t('Clone') . ')';
		$block['Block']['clone_of'] = $block['Block']['id'];

		unset($block['Block']['id'], $block['BlockRegion']);

		if ($this->Block->saveAll($block)) {
			$this->flashMsg(__t('Block has been cloned'), 'success');
			$this->redirect('/admin/block/manage/edit/' . $this->Block->id);
		} else {
			$this->flashMsg(__t('Block could not be cloned'), 'error');
		}

		$this->redirect($this->referer());
	}

	public function admin_edit($bid) {
		if (isset($this->data['Block'])) {
			$data = $this->data;
			$data['Block']['locale'] = !empty($data['Block']['locale']) ? array_values($data['Block']['locale']) : array();
			$data['Block']['themes_cache'] = $this->__themesCache($data['BlockRegion']);

			if ($this->Block->saveAll($data, array('validate' => 'first'))) {
				// saveAll only will save Block related models!
				if (isset($data['Module'])) {
					// save widgets variables
					$this->Module->save($data['Module']);
					Cache::delete('Modules');
					$this->QuickApps->loadModules();
				}

				if (isset($data['Variable'])) {
					$this->Variable->save($data['Variable']);
					Cache::delete('Variable');
					$this->QuickApps->loadVariables();
				}

				$this->flashMsg(__t('Block has been saved'), 'success');
			} else {
				$this->flashMsg(__t('Block could not be saved. Please, try again.'), 'error');
			}

			$this->redirect("/admin/block/manage/edit/{$bid}");
		}

		$themes = $this->__themesYaml();

		foreach ($themes as $theme => $yaml) {
			$_regions["{$yaml['info']['name']}::{$theme}"] = array();

			foreach ($yaml['regions'] as $name => $title) {
				$_regions["{$yaml['info']['name']}::{$theme}"]["{$name}"] = $title;
			}
		}

		$this->data = $this->Block->findById($bid) or $this->redirect('/admin/block/manage');

		$this->title(__t('Editing Block'));
		$this->setCrumb(
			'/admin/block',
			array(__t('Editing block'), null, null, 'pattern' => '*/block/manage/edit/*')
		);
		$this->set('regions', $_regions);
		$this->set('roles', $this->Role->find('list'));
	}

	public function admin_add() {
		$this->title(__t('Add new block'));
		$this->setCrumb(
			'/admin/block',
			array(__t('New block'))
		);

		if (isset($this->data['Block'])) {
			$data = $this->data;

			foreach ($data['BlockRegion'] as $key => $br) {
				if (empty($br['region'])) {
					unset($data['BlockRegion'][$key]);
				}
			}

			$data['Block']['module'] = 'Block';
			$data['Block']['locale'] = !empty($data['Block']['locale']) ? array_values($data['Block']['locale']) : array();
			$data['Block']['themes_cache'] = $this->__themesCache($data['BlockRegion']);

			if ($this->Block->saveAll($data, array('validate' => 'first'))) {
				$this->Block->BlockRegion->deleteAll(array('region' => ''));
				$this->flashMsg(__t('Block has been saved'), 'success');
				$this->redirect("/admin/block/manage/edit/{$this->Block->id}");
			} else {
				$this->flashMsg(__t('Block could not be saved. Please, try again.'), 'error');
			}
		}

		$themes = $this->__themesYaml();

		foreach ($themes as $theme => $yaml) {
			$_regions["{$yaml['info']['name']}@|@{$theme}"] = array();

			foreach ($yaml['regions'] as $name => $title) {
				$_regions["{$yaml['info']['name']}@|@{$theme}"]["{$name}"] = $title;
			}
		}

		$this->set('regions', $_regions);
		$this->set('roles', $this->Role->find('list'));
	}

	public function admin_delete($id) {
		$block = $this->Block->findById($id);

		if (!$block || ($block['Block']['module'] != 'block' && !$block['Block']['module'])) {
			$this->redirect('/admin');
		} else {
			if ($this->Block->delete($id)) {
				$this->flashMsg(__t('Block has been deleted'), 'success');
			} else {
				$this->flashMsg(__t('Block could not be deleted'), 'alert');
			}

			$this->redirect($this->referer());
		}
	}

	private function __themesCache($BlockRegion) {
		$o = array();

		foreach ($BlockRegion as $key => $r) {
			if (!empty($r['region'])) {
				$o[] = $r['theme'];
			}
		}
		$o = ':' . implode(":", array_unique($o)) . ':';

		return preg_replace('/\:{2,}/', ':', $o);
	}

	private function __themesYaml() {
		$return = array();
		$folder = new Folder;
		$folder->path = THEMES;
		$folders = $folder->read();
		$themes = $folders[0];
		$folder->path = APP . 'View' . DS . 'Themed' . DS;
		$folders = $folder->read();
		$themes = array_merge($themes, $folders[0]);

		foreach ($themes as $theme) {
			$theme_path = App::themePath($theme);

			if (file_exists($theme_path . "{$theme}.yaml")) {
				$yaml = Spyc::YAMLLoad($theme_path . "{$theme}.yaml");
				$return[$theme] = $yaml;
			}
		}

		return $return;
	}
}