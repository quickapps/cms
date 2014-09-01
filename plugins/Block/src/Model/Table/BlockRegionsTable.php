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

use Cake\ORM\Table;
use Cake\Validation\Validator;
use QuickApps\Core\Plugin;

/**
 * Represents "block_regions" database table.
 *
 */
class BlockRegionsTable extends Table {

/**
 * Initialize method.
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->belongsTo('Blocks', [
			'className' => 'Block.Blocks',
		]);
	}

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator The validator object
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		return $validator
			->add('theme', 'validTheme', [
				'rule' => function ($value, $context) {
					$exists = Plugin::collection(false)
						->match(['name' => $value])
						->first();
					return !empty($exists);
				},
				'message' => __d('block', 'Invalid theme for region.'),
			])
			->add('block_id', 'unique', [
				'rule' => ['validateUnique', ['scope' => 'theme']],
				'message' => __d('block', 'This block is already assigned to this theme.'),
				'provider' => 'table',
			]);
	}

}
