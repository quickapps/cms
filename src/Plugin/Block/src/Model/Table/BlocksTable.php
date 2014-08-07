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
namespace Block\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Represents "blocks" database table.
 *
 */
class BlocksTable extends Table {

/**
 * Initialize method.
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->hasMany('BlockRegions', [
			'className' => 'Block.BlockRegions',
			'dependent' => true,
		]);
		$this->belongsToMany('User.Roles');
	}

/**
 * Alter the schema used by this table.
 *
 * @param \Cake\Database\Schema\Table $table The table definition fetched from database
 * @return \Cake\Database\Schema\Table the altered schema
 */
	protected function _initializeSchema(Schema $table) {
		$table->columnType('locale', 'serialized');
		$table->columnType('settings', 'serialized');
		return $table;
	}

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		return $validator
			->validatePresence('title')
			->add('title', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('block', 'You need to provide a title.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('block', 'Title need to be at least 3 characters long.'),
				],
			])
			->validatePresence('description')
			->add('description', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('block', 'You need to provide a description.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('block', 'Description need to be at least 3 characters long.'),
				],
			])
			->add('visibility', 'validVisibility', [
				'rule' => function ($value, $context) {
					return in_array($value, ['except', 'only', 'php']);
				},
				'message' => __d('block', 'Invalid visibility.'),
			])
			->add('delta', [
				'unique' => [
					'rule' => ['validateUnique', ['scope' => 'handler']],
					'message' => __d('block', 'Invalid delta, there is already a block with the same <delta, handler> combination.'),
					'provider' => 'table',
				]
			]);
	}

/**
 * Validation rules for custom blocks.
 *
 * Plugins may define their own blocks, in these cases the "body" value is optional.
 * But blocks created by users (on the Blocks administration page) are required to have a valid "body".
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationCustom(Validator $validator) {
		return $this->validationDefault($validator)
			->validatePresence('body')
			->add('body', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('block', "You need to provide a content for block's body."),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('block', "Block's body need to be at least 3 characters long."),
				],
			]);
	}

}
