<?php
/**
 * User Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class User extends UserAppModel {
	public $name = 'User';
	public $useTable = "users";
	public $actsAs = array('Field.Fieldable' => array('belongsTo' => 'User'));
	public $validate = array(
		'username' => array(
			'alphanumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Only letters and numbers allowed.'
			),
			'minlength' => array(
				'rule' => array('minLength', '3'),
				'message' => 'Minimum length of 3 characters.'
			),
			'maxlength' => array(
				'rule' => array('maxLength', '32'),
				'message' => 'Maximum length of 32 characters.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Username already in use.'
			)
		),
		'name' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'You must enter your real name.'
		),
		'email' => array(
			'email' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => 'email',
				'message' => 'Invalid email.',
				'last' => true
			),
			'unique' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => 'isUnique',
				'message' => 'Email already in use.'
			)
		),
		'password' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'comparePwd',
			'message' => 'Password mismatch or less than 6 characters.'
		)
	);

	public $hasAndBelongsToMany = array(
		'Role' => array(
			'className' => 'User.Role',
			'joinTable' => 'users_roles',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'role_id'
		)
	);

	public function beforeValidate($options = array()) {
		if (isset($this->data['User']['id'])) {
			$this->validate['password']['allowEmpty'] = true;
		}

		if (isset($this->data['User']['email'])) {
			$this->data['User']['email'] = strtolower($this->data['User']['email']);
		}

		return true;
	}

	public function beforeSave($options = array()) {
		App::uses('Security', 'Utility');
		App::uses('String', 'Utility');

		if (empty($this->data['User']['password'])) {
			// empty password => do not update
			unset($this->data['User']['password']);
		} else {
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
		}

		if (isset($this->data['User']['email'])) {
			$this->data['User']['email'] = strtolower($this->data['User']['email']);
		}

		$this->data['User']['key'] = String::uuid();

		return true;
	}

	public function comparePwd($check) {
		$check['password'] = trim($check['password']);

		if (!isset($this->data['User']['id']) && strlen($check['password']) < 6) {
			return false;
		}

		if (isset($this->data['User']['id']) && empty($check['password'])) {
			return true;
		}

		$r = ($check['password'] == $this->data['User']['password2'] && strlen($check['password']) >= 6);

		if (!$r) {
			$this->invalidate('password2', __t('Password mismatch.'));
		}

		return $r;
	}
}