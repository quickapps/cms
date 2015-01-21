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
namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Represents "terms" database table.
 *
 */
class TermsTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsTo('Vocabularies', [
            'className' => 'Taxonomy.Vocabularies',
            'propertyName' => 'vocabulary'
        ]);
        $this->hasMany('EntitiesTerms', [
            'className' => 'Taxonomy.EntitiesTerms',
            'propertyName' => 'entities_cache',
            'dependent' => true,
        ]);
        $this->addBehavior('Timestamp');
        $this->addBehavior('Sluggable', ['label' => 'name', 'on' => 'both']);
    }

    /**
     * Default validation rules set.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->add('name', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('node', 'You need to provide a name.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('node', 'Name need to be at least 3 characters long.'),
                ],
            ]);

        return $validator;
    }
}
