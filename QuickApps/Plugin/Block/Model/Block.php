<?php
/**
 * Block Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Block extends BlockAppModel {
	public $name = 'Block';
	public $useTable = 'blocks';
	public $primaryKey = 'id';
	public $actsAs = array('Serialized' => array('locale', 'settings', 'params'));

	public $hasOne = array(
		'BlockCustom' => array(
			'className' => 'Block.BlockCustom',
			'dependent' => true
		)
	);

	public $hasMany = array(
		'BlockRegion' => array(
			'className' => 'Block.BlockRegion',
			'dependent' => true
		)
	);

	public $belongsTo = array(
		'Menu' => array(
			'className' => 'Menu.Menu',
			'foreignKey' => 'delta',
			'conditions' => 'Block.module = "Menu"',
			'dependent' => false
		)
	);

	public $hasAndBelongsToMany = array(
		'Role' => array(
			'joinTable' => 'block_roles',
			'className' => 'User.Role',
			'foreignKey' => 'block_id',
			'associationForeignKey' => 'user_role_id',
			'unique' => true,
			'dependent' => false
		)
	);

	public function beforeSave($options = array()) {
		// get new delta
		if (!isset($this->data['Block']['id'])) {
			// new record
			if ($this->data['Block']['module'] == 'Menu' || isset($this->data['Block']['delta'])) {
				return true;
			}

			$max_delta = $this->find('first',
				array(
					'conditions' => array('Block.module' => 'Block'),
					'fields' => array('delta'),
					'order' => array('delta' => 'DESC')
				)
			);
			$max_delta = !empty($max_delta) ? $max_delta['Block']['delta'] + 1 : 1;
			$this->data['Block']['delta'] = $max_delta;
		}

		return true;
	}

	public function afterSave($created) {
		$this->clearCache();
	}

	public function afterDelete() {
		$this->clearCache();
	}

	public function clearCache() {
		clearCache('blocks', '', '');
	}
}