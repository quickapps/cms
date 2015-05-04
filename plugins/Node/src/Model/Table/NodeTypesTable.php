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
namespace Node\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \ArrayObject;

/**
 * Represents "node_types" database table.
 *
 * @method void unbindSluggable()
 */
class NodeTypesTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
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
                    'message' => __d('node', 'You need to provide a content type name.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('node', 'Name need to be at least 3 characters long.'),
                ],
            ])
            ->requirePresence('slug')
            ->notEmpty('slug', __d('node', 'Machine-name cannot be left empty.'))
            ->add('slug', 'checkSlug', [
                'rule' => function ($value, $context) {
                    return (preg_match('/^[a-z0-9\-]{3,}$/', $value) === 1);
                },
                'message' => __d('node', 'Invalid machine-name.'),
            ])
            ->requirePresence('title_label')
            ->add('title_label', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('node', 'You need to provide a "Title Label".'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('node', '"Title Label" need to be at least 3 characters long.'),
                ],
            ]);

        return $validator;
    }

    /**
     * Regenerates snapshot after new content type is created.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The entity that was saved
     * @param \ArrayObject $options Array of options
     * @return void
     */
    public function afterSave(Event $event, Entity $entity, ArrayObject $options = null)
    {
        if ($entity->isNew()) {
            snapshot();
        }
    }
}
