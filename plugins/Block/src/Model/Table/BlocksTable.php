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
namespace Block\Model\Table;

use Block\Model\Entity\Block;
use Cake\Cache\Cache;
use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use QuickApps\Event\HookAwareTrait;
use \ArrayObject;

/**
 * Represents "blocks" database table.
 *
 */
class BlocksTable extends Table
{

    use HookAwareTrait;

    /**
     * Initialize method.
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->hasMany('BlockRegions', [
            'className' => 'Block.BlockRegions',
            'foreignKey' => 'block_id',
            'dependent' => true,
            'propertyName' => 'region',
        ]);
        $this->hasMany('Copies', [
            'className' => 'Block.Blocks',
            'foreignKey' => 'copy_id',
            'dependent' => true,
            'propertyName' => 'copies',
            'cascadeCallbacks' => true,
        ]);
        $this->belongsToMany('User.Roles', [
            'className' => 'User.Roles',
            'foreignKey' => 'block_id',
            'joinTable' => 'blocks_roles',
            'dependent' => false,
            'propertyName' => 'roles',
        ]);
    }

    /**
     * Alter the schema used by this table.
     *
     * @param \Cake\Database\Schema\Table $table The table definition fetched from database
     * @return \Cake\Database\Schema\Table the altered schema
     */
    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('locale', 'serialized');
        $table->columnType('settings', 'serialized');
        return $table;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        return $validator
            ->requirePresence('title')
            ->add('title', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('block', 'You need to provide a title.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('block', 'Title need to be at least 3 characters long.'),
                ],
            ])
            ->requirePresence('description')
            ->add('description', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('block', 'You need to provide a description.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('block', 'Description need to be at least 3 characters long.'),
                ],
            ])
            ->add('visibility', 'validVisibility', [
                'rule' => function ($value, $context) {
                    return in_array($value, ['except', 'only', 'php']);
                },
                'message' => __d('block', 'Invalid visibility.'),
            ])
            ->allowEmpty('pages')
            ->add('pages', 'validPHP', [
                'rule' => function ($value, $context) {
                    if (!empty($context['data']['visibility']) && $context['data']['visibility'] === 'php') {
                        return strpos($value, '<?php') !== false && strpos($value, '?>') !== false;
                    }
                    return true;
                },
                'message' => __d('block', 'Invalid PHP code, make sure that tags "<?php" & "?>" are present.')
            ])
            ->add('delta', [
                'unique' => [
                    'rule' => ['validateUnique', ['scope' => 'handler']],
                    'message' => __d('block', 'Invalid delta, there is already a block with the same [delta, handler] combination.'),
                    'provider' => 'table',
                ]
            ])
            ->requirePresence('handler', 'create')
            ->add('handler', 'validHandler', [
                'rule' => 'notEmpty',
                'on' => 'create',
                'message' => __d('menu', 'Invalid menu handler'),
            ]);
    }

    /**
     * Validation rules for custom blocks.
     *
     * Plugins may define their own blocks, in these cases the "body" value is
     * optional. But blocks created by users (on the Blocks administration page)
     * are required to have a valid "body".
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationCustom(Validator $validator)
    {
        return $this->validationDefault($validator)
            ->requirePresence('body')
            ->add('body', [
                'notEmpty' => [
                    'rule' => 'notEmpty',
                    'message' => __d('block', "You need to provide a content for block's body."),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('block', "Block's body need to be at least 3 characters long."),
                ],
            ]);
    }

    /**
     * Triggers the "Block.<handler>.beforeValidate" hook, so plugins may do
     * any logic their require.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity being validated
     * @param \ArrayObject $options Additional options given as an array
     * @param \Cake\Validation\Validator $validator The validator object
     * @return bool False if save operation should not continue, true otherwise
     */
    public function beforeValidate(Event $event, Block $block, ArrayObject $options, Validator $validator)
    {
        $blockEvent = $this->trigger(["Block.{$block->handler}.beforeValidate", $event->subject()], $block, $options, $validator);
        if ($blockEvent->isStopped() || $blockEvent->result === false) {
            return false;
        }
        return true;
    }

    /**
     * Triggers the "Block.<handler>.afterValidate" hook, so plugins may do
     * any logic their require.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity that was validated
     * @param \ArrayObject $options Additional options given as an array
     * @param \Cake\Validation\Validator $validator The validator object
     * @return void
     */
    public function afterValidate(Event $event, Block $block, ArrayObject $options, Validator $validator)
    {
        $this->trigger(["Block.{$block->handler}.afterValidate", $event->subject()], $block, $options, $validator);
    }

    /**
     * Triggers the "Block.<handler>.beforeSave" hook, so plugins may do
     * any logic their require.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity being saved
     * @param \ArrayObject $options Additional options given as an array
     * @return bool False if save operation should not continue, true otherwise
     */
    public function beforeSave(Event $event, Block $block, ArrayObject $options = null)
    {
        $blockEvent = $this->trigger(["Block.{$block->handler}.beforeSave", $event->subject()], $block, $options);
        if ($blockEvent->isStopped() || $blockEvent->result === false) {
            return false;
        }
        return true;
    }

    /**
     * Triggers the "Block.<handler>.afterSave" hook, so plugins may do
     * any logic their require.
     *
     * All cached blocks are automatically removed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity that was saved
     * @param \ArrayObject $options Additional options given as an array
     * @return void
     */
    public function afterSave(Event $event, Block $block, ArrayObject $options = null)
    {
        $this->trigger(["Block.{$block->handler}.afterSave", $event->subject()], $block, $options);
        $this->clearCache();
    }

    /**
     * Triggers the "Block.<handler>.beforeDelete" hook, so plugins may do
     * any logic their require.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity being deleted
     * @param \ArrayObject $options Additional options given as an array
     * @return bool False if delete operation should not continue, true otherwise
     */
    public function beforeDelete(Event $event, Block $block, ArrayObject $options = null)
    {
        $blockEvent = $this->trigger(["Block.{$block->handler}.beforeDelete", $event->subject()], $block, $options);
        if ($blockEvent->isStopped() || $blockEvent->result === false) {
            return false;
        }
        return true;
    }

    /**
     * Triggers the "Block.<handler>.afterDelete" hook, so plugins may do
     * any logic their require.
     *
     * All cached blocks are automatically removed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity that was deleted
     * @param \ArrayObject $options Additional options given as an array
     * @return void
     */
    public function afterDelete(Event $event, Block $block, ArrayObject $options = null)
    {
        $this->trigger(["Block.{$block->handler}.afterDelete", $event->subject()], $block, $options);
        $this->clearCache();
    }

    /**
     * Clear blocks cache for all themes and all regions.
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::clearGroup('views', 'blocks');
    }
}
