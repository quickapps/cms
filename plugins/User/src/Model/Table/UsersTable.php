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
namespace User\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Entity\User;

/**
 * Represents "users" database table.
 *
 */
class UsersTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsToMany('Roles', [
            'className' => 'User.Roles',
            'joinTable' => 'users_roles',
            'through' => 'UsersRoles',
            'propertyName' => 'roles',
        ]);
        $this->addBehavior('Timestamp', [
            'events' => [
                'Users.login' => [
                    'last_login' => 'always'
                ]
            ]
        ]);
        $this->addBehavior('Field.Fieldable');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        return $validator
            ->requirePresence('name')
            ->notEmpty('name', __d('user', 'You must provide a name.'))
            ->requirePresence('username', 'create')
            ->add('username', [
                'characters' => [
                    'rule' => function ($value, $context) {
                        return preg_match('/^[a-zA-Z0-9\_]{3,}$/', $value) === 1;
                    },
                    'provider' => 'table',
                    'message' => __d('user', 'Invalid username. Only letters, numbers and "_" symbol, and at least three characters long.'),
                ],
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __d('user', 'Username already in use.'),
                ],
            ])
            ->requirePresence('email')
            ->notEmpty('email', __d('user', 'e-Mail cannot be empty.'))
            ->add('email', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __d('user', 'e-Mail already in use.'),
                ]
            ])
            ->add('username', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __d('user', 'Username already in use.'),
                ]
            ])
            ->requirePresence('password', 'create')
            ->allowEmpty('password', 'update')
            ->add('password', [
                'compare' => [
                    'rule' => function ($value, $context) {
                        $value2 = isset($context['data']['password2']) ? $context['data']['password2'] : false;
                        return (new DefaultPasswordHasher)->check($value2, $value);
                    },
                    'message' => __d('user', 'Password mismatch.'),
                ],
                'length' => [
                    'rule' => function ($value, $context) {
                        $raw = isset($context['data']['password2']) ? $context['data']['password2'] : '';
                        return strlen($raw) >= 6;
                    },
                    'message' => __d('user', 'Password must be at least 6 characters long.'),
                ]
            ])
            ->allowEmpty('web')
            ->add('web', 'validUrl', [
                'rule' => 'url',
                'message' => __d('user', 'Invalid URL.'),
            ]);
    }

    /**
     * If not password is sent means user is not changing it.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \User\Model\Entity\User $user User entity being saved
     * @return void
     */
    public function beforeSave(Event $event, User $user)
    {
        if (!$user->isNew() && $user->has('password') && empty($user->password)) {
            $user->unsetProperty('password');
            $user->dirty('password', false);
        }
    }

    /**
     * Generates a unique token for the given user. The generated token is
     * automatically persisted on DB.
     *
     * Tokens are unique and follows the pattern below:
     *
     *     <user_id>-<32-random-letters-and-numbers>
     *
     * @param \User\Model\Entity\User $user The user for which generate the token
     * @return \User\Model\Entity\User The user entity with a the new token property
     * @throws \Cake\Error\FatalErrorException When an invalid user entity was given
     */
    public function updateToken(User $user)
    {
        if (!$user->has('id')) {
            throw new FatalErrorException(__d('user', 'UsersTable::updateToken(), no ID was found for the given entity.'));
        }

        $user->set('token', $user->id . '-' . md5(uniqid($user->id, true)));
        $this->save($user, ['validate' => false]);
        return $user;
    }

    /**
     * Counts the number of administrators ins the system.
     *
     * @return int
     */
    public function countAdministrators()
    {
        return $this->find()
            ->matching('Roles', function ($q) {
                return $q->where(['Roles.id' => ROLE_ID_ADMINISTRATOR]);
            })
            ->count();
    }
}
