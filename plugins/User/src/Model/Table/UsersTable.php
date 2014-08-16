<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Database\Schema\Table as Schema;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Security;

/**
 * Represents "users" database table.
 *
 */
class UsersTable extends Table {

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->belongsToMany('Roles', [
			'className' => 'User.Roles',
			'joinTable' => 'users_roles',
			'through' => 'UsersRoles',
			'propertyName' => 'roles',
		]);
		$this->addBehavior('Timestamp');
		$this->addBehavior('Field.Fieldable');
	}

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		return $validator
			->validatePresence('username', 'create')
			->add('username', [
				'characters' => [
					'rule' => function ($value, $context) {
						return preg_match('/^[a-zA-Z0-9\_]{3,}$/', $value) === 1;
					},
					'provider' => 'table',
					'message' => __d('user', 'Invalid username. Only letters, numbers and "_" symbol, and at least three characters long.'),
				],
				'unique' => [
					'rule' => 'validateUnique',
					'provider' => 'table',
					'message' => __d('user', 'Username already in use.'),
				]
			])
			->validatePresence('email')
			->notEmpty('email')
			->add('email', [
				'unique' => [
					'rule' => 'validateUnique',
					'provider' => 'table',
					'message' => __d('user', 'e-Mail already in use.'),
				]
			])
			->add('username', [
				'unique' => [
					'rule' => 'validateUnique',
					'provider' => 'table',
					'message' => __d('user', 'Username already in use.'),
				]
			])
			->validatePresence('password', 'create')
			->allowEmpty('password', 'update')
			->add('password', [
				'compare' => [
					'rule' => function ($value, $context) {
						$value2 = isset($context['data']['password2']) ? $context['data']['password2'] : false;
						return $value === $value2;
					},
					'message' => __d('user', 'Password mismatch.'),
				],
				'length' => [
					'rule' => ['minLength', 6],
					'message' => __d('user', 'Password must be at least 6 characters long.'),
				]
			])
			->allowEmpty('web')
			->add('web', 'validUrl', [
				'rule' => 'url',
				'message' => __d('user', 'Invalid URL.'),
			]);
	}

	public function beforeSave($event, $user) {
		if (!$user->isNew() && $user->has('password') && empty($user->password)) {
			$user->unsetProperty('password');
			$user->accessible('password', false);
			$user->dirty('password', false);
		}

		if ($user->has('password')) {
			$user->set('password', (new DefaultPasswordHasher)->hash($user->password));
		}
	}

}
