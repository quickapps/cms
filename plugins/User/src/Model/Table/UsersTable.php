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

use Cake\Database\Schema\Table as Schema;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Represents "users" database table.
 *
 */
class UsersTable extends Table {

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		return $validator
			->add('email', [
				'unique' => [
					'rule' => ['rule' => 'validateUnique', 'provider' => 'table'],
					'message' => __d('block', 'e-Mail already in use.'),
				]
			])
			->add('username', [
				'unique' => [
					'rule' => ['rule' => 'validateUnique', 'provider' => 'table'],
					'message' => __d('block', 'Username already in use.'),
				]
			]);
	}

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		/*
		$this->belongsToMany('Roles', [
			'className' => 'User.Roles',
		]);
*/
		$this->addBehavior('Timestamp');
		$this->addBehavior('Field.Fieldable');
	}


}
