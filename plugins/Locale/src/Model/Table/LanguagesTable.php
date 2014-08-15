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
namespace Node\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Represents "languages" database table.
 *
 */
class NodesTable extends Table {

/**
 * Default validation rules set.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('name', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('node', 'You need to provide a language name.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('node', 'Language name need to be at least 3 characters long.'),
				],
			]);

		return $validator;
	}

}
