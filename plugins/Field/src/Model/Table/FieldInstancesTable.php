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
namespace Field\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use CMS\View\ViewModeAwareTrait;
use Field\Model\Entity\FieldInstance;
use \ArrayObject;

/**
 * Represents "field_instances" database table.
 */
class FieldInstancesTable extends Table
{

    use ViewModeAwareTrait;

    /**
     * Used to deleted associated "belongsTo" EavAttributes.
     *
     * @var \Cake\Datasource\EntityInterface|null
     */
    protected $_deleted = null;

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Serializable', [
            'columns' => ['settings', 'view_modes']
        ]);

        $this->belongsTo('EavAttribute', [
            'className' => 'Eav.EavAttributes',
            'foreignKey' => 'eav_attribute_id',
            'propertyName' => 'eav_attribute',
            'dependent' => true,
        ]);
    }

    /**
     * Application rules.
     *
     * @param \Cake\ORM\RulesChecker $rules The rule checker
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // check max instances limit
        $rules->addCreate(function ($instance, $options) {
            $info = (array)$instance->info();
            if (isset($info['maxInstances']) && $info['maxInstances'] > 0) {
                if (!$instance->get('eav_attribute')) {
                    return false;
                }

                $count = $this
                    ->find()
                    ->select(['FieldInstances.id', 'FieldInstances.handler', 'EavAttribute.id', 'EavAttribute.table_alias'])
                    ->contain(['EavAttribute'])
                    ->where([
                        'EavAttribute.table_alias' => $instance->get('eav_attribute')->get('table_alias'),
                        'FieldInstances.handler' => $instance->get('handler'),
                    ])
                    ->count();
                return ($count <= (intval($info['maxInstances']) - 1));
            }
            return true;
        }, 'maxInstances', [
            'errorField' => 'label',
            'message' => __d('field', 'No more instances of this field can be attached, limit reached.'),
        ]);
        return $rules;
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
            ->notEmpty('handler', __d('field', 'Invalid field type.'))
            ->add('label', [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('field', 'You need to provide a label.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('field', 'Label need to be at least 3 characters long'),
                ],
            ]);

        return $validator;
    }

    /**
     * Instance's settings validator.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationSettings(Validator $validator)
    {
        return $validator;
    }

    /**
     * Instance's view mode validator.
     *
     * This rules are apply to a single view mode.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationViewMode(Validator $validator)
    {
        return $validator;
    }

    /**
     * Here we set default values for each view mode if they were not defined before.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Query $query The query object
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $viewModes = $this->viewModes();
        $query->formatResults(function ($results) use ($viewModes) {
            return $results->map(function ($instance) use ($viewModes) {
                if (!is_object($instance)) {
                    return $instance;
                }

                foreach ($viewModes as $viewMode) {
                    $instanceViewModes = $instance->view_modes;
                    $viewModeDefaults = array_merge([
                        'label_visibility' => 'above',
                        'shortcodes' => false,
                        'hidden' => false,
                        'ordering' => 0,
                    ], (array)$instance->defaultViewModeSettings($viewMode));

                    if (!isset($instanceViewModes[$viewMode])) {
                        $instanceViewModes[$viewMode] = [];
                    }

                    $instanceViewModes[$viewMode] = array_merge($viewModeDefaults, $instanceViewModes[$viewMode]);
                    $instance->set('view_modes', $instanceViewModes);
                }

                $settingsDefaults = (array)$instance->defaultSettings();
                if (!empty($settingsDefaults)) {
                    $instanceSettings = $instance->get('settings');
                    foreach ($settingsDefaults as $k => $v) {
                        if (!isset($instanceSettings[$k])) {
                            $instanceSettings[$k] = $v;
                        }
                    }
                    $instance->set('settings', $instanceSettings);
                }

                return $instance;
            });
        });
    }

    /**
     * Triggers the callback "beforeAttach" if a new instance is being created.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be saved
     * @param \ArrayObject  $options The options passed to the save method
     * @return bool False if save operation should not continue, true otherwise
     */
    public function beforeSave(Event $event, FieldInstance $instance, ArrayObject $options = null)
    {
        $result = $instance->beforeAttach();
        if ($result === false) {
            return false;
        }
        return true;
    }

    /**
     * Triggers the callback "afterAttach" if a new instance was created.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that was saved
     * @param \ArrayObject $options the options passed to the save method
     * @return void
     */
    public function afterSave(Event $event, FieldInstance $instance, ArrayObject $options = null)
    {
        if ($instance->isNew()) {
            $instance->afterAttach();
        }
    }

    /**
     * Triggers the callback "beforeDetach".
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be deleted
     * @param \ArrayObject $options the options passed to the delete method
     * @return bool False if delete operation should not continue, true otherwise
     */
    public function beforeDelete(Event $event, FieldInstance $instance, ArrayObject $options = null)
    {
        $result = $instance->beforeDetach();
        if ($result === false) {
            return false;
        }

        $this->_deleted = $this->get($instance->get('id'), ['contain' => ['EavAttribute']]);
        return true;
    }

    /**
     * Triggers the callback "afterDetach", it also deletes all associated records
     * in the "field_values" table.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that was deleted
     * @param \ArrayObject $options the options passed to the delete method
     * @return void
     */
    public function afterDelete(Event $event, FieldInstance $instance, ArrayObject $options = null)
    {
        if (!empty($this->_deleted)) {
            TableRegistry::get('Eav.EavAttributes')->delete($this->_deleted->get('eav_attribute'));
            $instance->afterDetach();
            $this->_deleted = null;
        }
    }
}
