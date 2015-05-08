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
namespace Content\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \ArrayObject;

/**
 * Represents "content_types" database table.
 *
 * @property \User\Model\Table\RolesTable $Roles
 * @property \User\Model\Table\ContentTypePermissions $ContentTypePermissions
 * @method void unbindSluggable()
 */
class ContentTypesTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsToMany('User.Roles', [
            'propertyName' => 'permissions',
            'through' => 'Content.ContentTypePermissions',
        ]);
        $this->addBehavior('Sluggable', [
            'label' => 'name',
            'slug' => 'slug',
            'on' => 'insert',
        ]);
        $this->addBehavior('Serializable', [
            'columns' => ['settings', 'defaults']
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function patchEntity(EntityInterface $type, array $data, array $options = [])
    {
        $return = parent::patchEntity($type, $data);
        if (!empty($data['permissions'])) {
            $roles = [];
            foreach ($data['permissions'] as $rule => $ids) {
                foreach ($ids as $roleId) {
                    if (!empty($roleId)) {
                        $role = $this->Roles->get($roleId);
                        $role->set('_joinData', $this->ContentTypePermissions->newEntity(['action' => $rule]));
                        $roles[] = $role;
                    }
                }
            }
            $return->set('permissions', $roles);
        }
        return $return;
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
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('content', 'You need to provide a content type name.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('content', 'Name need to be at least 3 characters long.'),
                ],
            ])
            ->requirePresence('slug', 'create')
            ->notEmpty('slug', __d('content', 'Machine-name cannot be left empty.'), 'create')
            ->add('slug', 'checkSlug', [
                'rule' => function ($value, $context) {
                    return (preg_match('/^[a-z0-9\-]{3,}$/', $value) === 1);
                },
                'message' => __d('content', 'Invalid machine-name.'),
            ])
            ->requirePresence('title_label')
            ->add('title_label', [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('content', 'You need to provide a "Title Label".'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('content', '"Title Label" need to be at least 3 characters long.'),
                ],
            ]);

        return $validator;
    }

    /**
     * Regenerates snapshot after new content type is created.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Array of options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options = null)
    {
        if ($entity->isNew()) {
            snapshot();
        }
    }
}
