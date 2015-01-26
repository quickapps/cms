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
namespace Locale\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Represents "languages" database table.
 *
 */
class LanguagesTable extends Table
{

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
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('locale', 'You need to provide a language name.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('locale', 'Language name need to be at least 3 characters long.'),
                ],
            ])
            ->requirePresence('code')
            ->add('code', 'unique', [
                'rule' => 'validateUnique',
                'message' => __d('locale', 'This language is already registered.'),
                'provider' => 'table',
            ]);

        return $validator;
    }

    /**
     * Regenerates system's snapshot.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $language The language entity that was saved
     * @return void
     */
    public function afterSave(Event $event, Entity $language)
    {
        snapshot();
    }

    /**
     * Regenerates system's snapshot.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $language The language entity that was saved
     * @return void
     */
    public function afterDelete(Event $event, Entity $language)
    {
        snapshot();
    }
}
