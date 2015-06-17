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

use Cake\Cache\Cache;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Eav\Model\Behavior\EavToolbox;
use \ArrayObject;

/**
 * Represents EAV "eav_attributes" database table.
 */
class EavAttributesTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        if (class_exists('CMS\\Model\\Behavior\\SerializableBehavior')) {
            $this->addBehavior('CMS.Serializable', [
                'columns' => ['extra']
            ]);
        }

        $this->hasMany('EavValues', [
            'className' => 'Eav.EavValues',
            'foreignKey' => 'eav_attribute_id',
            'dependent' => true,
        ]);
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
            ->add('name', [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('eav', 'You need to provide a machine-name.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('eav', 'Machine-name need to be at least 3 characters long.'),
                ],
                'regExp' => [
                    'rule' => function ($value, $context) {
                        return preg_match('/^[a-z\d\-]+$/', $value) > 0;
                    },
                    'message' => __d('eav', 'Only lowercase letters, numbers and "-" symbol are allowed.'),
                ],
                'unique' => [
                    'rule' => ['validateUnique', ['scope' => 'table_alias']],
                    'provider' => 'table',
                    'message' => __d('eav', 'Machine-name already in use.'),
                ],
            ])
            ->notEmpty('table_alias', __d('eav', 'Invalid table alias.'))
            ->requirePresence('type', 'create', __d('eav', 'Invalid data type.'))
            ->add('type', 'valid_type', [
                'rule' => function ($value, $context) {
                    return in_array($value, EavToolbox::$types);
                },
                'message' => __d('eav', 'Invalid data type, valid options are: {0}', implode(', ', EavToolbox::$types))
            ]);

        return $validator;
    }

    /**
     * Clears any relevant stored cache after an EAV attribute has changed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Additional options given as an array
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options = null)
    {
        $this->_clearCache();
    }

    /**
     * Clears any relevant stored cache after an EAV attribute is removed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Additional options given as an array
     * @return void
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options = null)
    {
        $this->_clearCache();
    }

    /**
     * Clears every EAV cache.
     *
     * @return void
     */
    protected function _clearCache()
    {
        Cache::clear(false, 'eav_table_attrs');
    }
}
