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

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Represents "roles" database table.
 *
 */
class RolesTable extends Table {

/**
 * Default validation rules set.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('id', 'checkID', [
				'rule' => function ($value, $context) {
					return !in_array(intval($value), [ROLE_ID_ADMINISTRATOR, ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS]);
				},
				'message' => __d('node', 'This role can not be modified or deleted!'),
			])
			->validatePresence('name')
			->add('name', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('node', 'You need to provide a role name.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('node', 'Role name need to be at least 3 characters long.'),
				],
			]);

		return $validator;
	}

}
