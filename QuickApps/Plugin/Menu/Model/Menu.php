<?php
/**
 * Menu Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Menu.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Menu extends MenuAppModel {
	public $name = 'Menu';
	public $useTable = "menus";
	public $primaryKey = 'id';
	public $validate = array(
		'title' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Menu title can not be empty.'),
	);

	public $belongsTo = array(
		'Block' => array(
			'className' => 'Block.Block',
			'foreignKey' => 'id',
			'associationForeignKey' => 'delta',
			'conditions' => array('Block.module' => 'Menu')
		)
	);

	public function afterFind($results, $primary = false) {
		if (isset($results['id']) && $this->recursive) {
			$results['MenuLink'] = ClassRegistry::init('Menu.MenuLink')->find('threaded',
				array(
					'conditions' => array(
						'MenuLink.menu_id' => $results['id']
					),
					'order' => array('lft' => 'ASC'),
					'recursive' => -1
				)
			);
		}

		return $results;
	}

	public function beforeDelete($cascade = true) {
		// delete block
		$this->Block->deleteAll(
			array(
				'Block.delta' => $this->id,
				'Block.module' => 'Menu'
			)
		);

		// links delete
		$this->MenuLink = ClassRegistry::init('Menu.MenuLink');
		$this->MenuLink->Behaviors->detach('Tree');
		$this->MenuLink->Behaviors->attach('Tree',
			array(
				'parent' => 'parent_id',
				'left' => 'lft',
				'right' => 'rght',
				'scope' => "MenuLink.menu_id = {$this->id}"
			)
		);
		$this->MenuLink->deleteAll(
			array(
				'MenuLink.menu_id' => $this->id
			)
		);

		return true;
	}

	public function beforeSave($options = array()) {
		if (!isset($this->data['Menu']['id'])) {
			/* menu slug */
			$id = Inflector::slug($this->data['Menu']['title'], '-');
			$i = 1;
			$_id = $id;
			$c = '';

			while ($this->find('count', array('conditions' => array('Menu.id' => $_id))) > 0) {
				$c = '-' . $i;
				$_id = $id . $c;
				$i++;
			}

			$this->data['Menu']['id'] = strtolower($id . $c);
			/* end menu slug */

			// Create block
			$bdata['Block'] = array(
				'module' => 'Menu',
				'delta' => $this->data['Menu']['id'],
				'themes_cache' => '',
				'ordering' => 0,
				'status' => 0,
				'visibility' => 0,
				'pages' => '',
				'title' => $this->data['Menu']['title']
			);
			$this->Block->save($bdata, false);
		}

		return true;
	}
}