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
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use QuickApps\Core\Plugin;
use User\Model\Entity\User;

/**
 * Represents "users" database table.
 *
 * @method void addSearchOperator(string $name, mixed $handler, array $options = [])
 * @method bool touch(\Cake\Datasource\EntityInterface $entity, string $eventName = 'Model.beforeSave')
 * @method void unbindFieldable()
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
        $this->addBehavior('Search.Searchable', [
            'fields' => function ($user) {
                $words = '';
                $words .= empty($user->name) ?: " {$user->name}";
                $words .= empty($user->username) ?: " {$user->username}";
                $words .= empty($user->email) ?: " {$user->email}";
                $words .= empty($user->web) ?: " {$user->web}";

                if (!empty($user->_fields)) {
                    foreach ($user->_fields as $vf) {
                        $words .= ' ' . trim($vf->value);
                    }
                }
                return $words;
            }
        ]);
        $this->addBehavior('Field.Fieldable');

        $this->addSearchOperator('created', 'Search.Date', ['field' => 'created']);
        $this->addSearchOperator('limit', 'Search.Limit');
        $this->addSearchOperator('email', 'Search.Generic', ['field' => 'email', 'conjunction' => 'auto']);
        $this->addSearchOperator('order', 'Search.Order', ['fields' => ['name', 'username', 'email', 'web']]);
    }

    /**
     * Application rules.
     *
     * @param \Cake\ORM\RulesChecker $rules The rule checker
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // unique mail
        $rules->add($rules->isUnique(['email'], __d('user', 'e-mail already in use.')));

        // unique username
        $rules->add($rules->isUnique(['username'], __d('user', 'Username already in use.')));
        return $rules;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
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
            ])
            ->requirePresence('email')
            ->notEmpty('email', __d('user', 'e-mail cannot be empty.'))
            ->requirePresence('password', 'create')
            ->allowEmpty('password', 'update')
            ->add('password', [
                'compare' => [
                    'rule' => function ($value, $context) {
                        $value2 = isset($context['data']['password2']) ? $context['data']['password2'] : false;
                        return (new DefaultPasswordHasher)->check($value2, $value) || $value == $value2;
                    },
                    'message' => __d('user', 'Password mismatch.'),
                ]
            ])
            ->allowEmpty('web')
            ->add('web', 'validUrl', [
                'rule' => 'url',
                'message' => __d('user', 'Invalid URL.'),
            ]);

        return $this->_applyPasswordPolicies($validator);
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
        $this->save($user);
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

    /**
     * Alters validator object and applies password constraints.
     *
     * @param \Cake\Validation\Validator $validator Validator object
     * @return \Cake\Validation\Validator
     */
    protected function _applyPasswordPolicies(Validator $validator)
    {
        $rules = [];
        if (Plugin::settings('User', 'password_min_length')) {
            $len = intval(Plugin::settings('User', 'password_min_length'));
            $rules['length'] = [
                'rule' => function ($value, $context) use ($len) {
                    return mb_strlen($this->_getRawPassword($context)) >= $len;
                },
                'message' => __d('user', 'Password must be at least {0} characters long.', $len),
            ];
        }

        if (Plugin::settings('User', 'password_uppercase')) {
            $rules['uppercase'] = [
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/[A-Z]/', $this->_getRawPassword($context));
                },
                'message' => __d('user', 'Password must contain at least one uppercase character (A-Z).'),
            ];
        }

        if (Plugin::settings('User', 'password_lowercase')) {
            $rules['lowercase'] = [
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/[a-z]/', $this->_getRawPassword($context));
                },
                'message' => __d('user', 'Password must contain at least one lowercase character (a-z).'),
            ];
        }

        if (Plugin::settings('User', 'password_number')) {
            $rules['number'] = [
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/[0-9]/', $this->_getRawPassword($context));
                },
                'message' => __d('user', 'Password must contain at least one numeric character (1-9).'),
            ];
        }

        if (Plugin::settings('User', 'password_non_alphanumeric')) {
            $rules['non_alphanumeric'] = [
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/[^0-9a-z]/i', $this->_getRawPassword($context));
                },
                'message' => __d('user', 'Password must contain at least one non-alphanumeric character (e.g. #%?).'),
            ];
        }

        $validatoradd('password', $rules);
        return $validator;
    }

    /**
     * Tries to get raw password from the given context.
     *
     * @param array $context Validation rule's context
     * @return string Raw password
     */
    protected function _getRawPassword($context)
    {
        if (isset($context['data']['password2'])) {
            return $context['data']['password2'];
        }

        return '';
    }
}
