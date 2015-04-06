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
namespace Eav\Model\Table;

use Cake\ORM\Table;

/**
 * Represents EAV "eav_values" database table.
 */
class EavValuesTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Serializable', [
            'columns' => ['extra']
        ]);

        $this->belongsTo('EavAttribute', [
            'className' => 'Eav.EavAttributes',
            'foreignKey' => 'eav_attribute_id',
            'propertyName' => 'eav_attribute',
            'dependent' => false,
        ]);
    }
}
