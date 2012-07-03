<?php
/**
 * Role Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class Role extends UserAppModel {
	public $name = 'Role';
	public $useTable = "roles";
	public $order = array('Role.ordering' => 'ASC');
	public $validate = array(
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Role name can not be empty.'),
			'unique' => array('rule' => 'isUnique','message' => 'Role name already in use.')
		)
	);

	public function beforeDelete($cascade = true) {
		$Aro = ClassRegistry::init('Aro');
		$Permission = ClassRegistry::init('Permission');

		$Permission->deleteAll(array('aro_id' => $this->id));

		return $Aro->deleteAll(array('model' => 'User.Role', 'foreign_key' => $this->id));
	}

	public function afterSave($created) {
		$Aro = ClassRegistry::init('Aro');
		$data = array(
			'model' => 'User.Role',
			'foreign_key' => $this->id
		);

		$Aro->save($data);
	}
}