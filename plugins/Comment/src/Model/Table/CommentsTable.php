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
namespace Comment\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Comment\Controller\Component\CommentComponent;
use QuickApps\Core\Plugin;

/**
 * Represents "comments" database table.
 *
 */
class CommentsTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * {@inheritDoc}
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', [
            'className' => 'User.Users',
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Basic validation set of rules.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('subject', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('comment', 'You need to provide a comment subject.'),
                ],
                'length' => [
                    'rule' => ['minLength', 5],
                    'message' => 'Comment subject need to be at least 5 characters long',
                ]
            ])
            ->add('body', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('comment', 'Your comment message cannot be empty')
                ],
                'length' => [
                    'rule' => ['minLength', 5],
                    'message' => 'Comment message need to be at least 5 characters long',
                ]
            ])
            ->allowEmpty('user_id')
            ->allowEmpty('parent_id')
            ->add('parent_id', 'checkParentId', [
                'rule' => function ($value, $context) {
                    if (!empty($value)) {
                        // make sure it's a valid parent: exists and belongs to the
                        // the same bundle (table)
                        $conditions = [
                            'id' => $value,
                            'entity_id' => $context['data']['entity_id'],
                            'table_alias' => $context['data']['table_alias'],
                        ];

                        return TableRegistry::get('Comment.Comments')->find()
                            ->where($conditions)
                            ->count() > 0;
                    } else {
                        $context['data']['parent_id'] = null;
                    }

                    return true;
                },
                'message' => __d('comment', 'Invalid parent comment!.'),
                'provider' => 'table',
            ]);

        return $validator;
    }

    /**
     * Validation rules when editing a comment in backend.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationAnonymous(Validator $validator)
    {
        $settings = Plugin::settings('Comment');
        $validator = $this->validationDefault($validator);

        if ($settings['allow_anonymous']) {
            if ($settings['anonymous_name']) {
                $validator
                    ->requirePresence('author_name')
                    ->add('author_name', 'nameLength', [
                        'rule' => ['minLength', 3],
                        'message' => __d('comment', 'Your name need to be at least 3 characters long.'),
                    ]);

                if ($settings['anonymous_name_required']) {
                    $validator->notEmpty('author_name', __d('comment', 'You must provide your name.'));
                } else {
                    $validator->allowEmpty('author_name');
                }
            }

            if ($settings['anonymous_email']) {
                $validator
                    ->requirePresence('author_email')
                    ->add('author_email', 'validEmail', [
                        'rule' => 'email',
                        'message' => __d('comment', 'e-Mail must be valid.'),
                    ]);

                if ($settings['anonymous_email_required']) {
                    $validator->notEmpty('author_email', __d('comment', 'You must provide an email.'));
                } else {
                    $validator->allowEmpty('anonymous_email');
                }
            }

            if ($settings['anonymous_web']) {
                $validator
                    ->requirePresence('author_web')
                    ->add('author_web', 'validURL', [
                        'rule' => 'url',
                        'message' => __d('comment', 'Website must be a valid URL.'),
                    ]);

                if ($settings['anonymous_web_required']) {
                    $validator->notEmpty('author_web', __d('comment', 'You must provide a website URL.'));
                } else {
                    $validator->allowEmpty('author_web');
                }
            }
        }

        return $validator;
    }
}
