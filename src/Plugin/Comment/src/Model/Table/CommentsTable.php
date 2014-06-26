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
namespace Comment\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Represents "comments" database table.
 *
 */
class CommentsTable extends Table {

/**
 * Initialize a table instance. Called after the constructor.
 *
 * {@inheritdoc}
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->belongsTo('Users');
	}

/**
 * Basic validation set of rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationBasic(\Cake\Validation\Validator $validator) {
		$validator
			->add('subject', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('comment', 'You need to provide a title'),
				],
				'length' => [
					'rule' => ['minLength', 5],
					'message' => 'Comment subject need to be at least 5 characters long',
				]
			])
			->add('body', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('comment', 'Your comment message cannot be empty')
				],
				'length' => [
					'rule' => ['minLength', 5],
					'message' => 'Comment message need to be at least 5 characters long',
				]
			])
			->allowEmpty('parent_id')
			->add('parent_id', 'checkParentId', [
				'rule' => function ($value, $context) {
					if (!empty($value)) {
						$conditions = [
							'id' => $value,
							'entity_id' => $context['providers']['entity']->entity_id,
							'table_alias' => $context['providers']['entity']->table_alias,
						];

						return TableRegistry::get('Comment.Comments')->find()
							->where($conditions)
							->count() > 0;
					} else {
						$context['providers']['entity']->parent_id = null;
					}

					return true;
				},
				'message' => __d('comment', 'Invalid parent comment!.')
			])
			->allowEmpty('user_id')
			->add('user_id', 'checkUserId', [
				'rule' => function ($value, $context) {
					if (!empty($value)) {
						$valid = TableRegistry::get('User.Users')->find()
							->where(['id' => $value])
							->count() === 1;

						if ($valid) {
							$context['providers']['entity']->set('author_name', null);
							$context['providers']['entity']->set('author_email', null);
							$context['providers']['entity']->set('author_web', null);
						}

						return $valid;
					}

					return true;
				},
				'message' => __d('comment', 'Invalid author.')
			]);

		return $validator;
	}

/**
 * Wrapper to validationBasic.
 *
 * As CakePHP's uses `default` validator. We wrap around `Basic` validator
 * when no validation set is specified.
 *
 * @param  \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(\Cake\Validation\Validator $validator) {
		return $this->validationBasic($validator);
	}

/**
 * Validation rules applied to anonymous users.
 *
 * @param  \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationAnonymous(\Cake\Validation\Validator $validator) {
		$validator = $this->validationBasic($validator);

		return $validator;
	}
}
