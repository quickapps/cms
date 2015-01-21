<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Represents "entities_terms" database table.
 *
 * Used by TaxonomyField to hold a "belongsToMany" relationship between terms
 * and any entity. This allows to search any entity matching an associated term.
 */
class EntitiesTermsTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsTo('Terms', ['className' => 'Taxonomy.Terms', 'propertyName' => 'term']);
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
            ->requirePresence('entity_id', 'create')
            ->notEmpty('entity_id', __d('taxonomy', 'Illegal action, entity_id cannot be empty.'))
            ->requirePresence('term_id', 'create')
            ->notEmpty('term_id', __d('taxonomy', 'Illegal action, term_id cannot be empty.'))
            ->requirePresence('table_alias', 'create')
            ->notEmpty('table_alias', __d('taxonomy', 'Illegal action, table_alias cannot be empty.'));

        return $validator;
    }
}
