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
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use QuickApps\Core\Plugin;
use QuickApps\Event\HookAwareTrait;
use \ArrayObject;

/**
 * Represents "blocks" database table.
 *
 * The following events related to block life cycle are triggered by this class:
 *
 * - Block.<handler>.beforeSave
 * - Block.<handler>.afterSave
 * - Block.<handler>.beforeDelete
 * - Block.<handler>.afterDelete
 *
 * @property \User\Model\Table\RolesTable $Roles
 * @property \Block\Model\Table\BlocksTable $Blocks
 * @property \Block\Model\Table\BlockRegionsTable $BlockRegions
 */
class BlocksTable extends Table
{

    use HookAwareTrait;

    /**
     * Get the Model callbacks this table is interested in.
     *
     * @return array
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        $events['Blocks.settings.validate'] = 'settingsValidate';
        $events['Blocks.settings.defaultValues'] = 'settingsDefaultValues';
        return $events;
    }

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

        $this->addBehavior('System.Serializable', [
            'columns' => ['locale', 'settings']
        ]);
    }

    /**
     * Gets a list of all blocks renderable in front-end theme.
     *
     * @return mixed
     */
    public function inFrontTheme()
    {
        return $this->_inTheme('front');
    }

    /**
     * Gets a list of all blocks renderable in front-end theme.
     *
     * @return mixed
     */
    public function inBackTheme()
    {
        return $this->_inTheme('back');
    }

    /**
     * Gets a list of all blocks that are NOT renderable.
     *
     * @return \Cake\Collection\CollectionInterface
     */
    public function unused()
    {
        $ids = [];
        foreach ([$this->inFrontTheme(), $this->inBackTheme()] as $bundle) {
            foreach ($bundle as $region => $blocks) {
                foreach ($blocks as $block) {
                    $ids[] = $block->get('id');
                }
            }
        }

        $notIn = array_unique($ids);
        $notIn = empty($notIn) ? ['0'] : $notIn;
        return $this->find()
            ->where([
                'OR' => [
                    'Blocks.id NOT IN' => $notIn,
                    'Blocks.status' => 0,
                ]
            ])
            ->all()
            ->filter(function ($block) {
                return $block->renderable();
            });
    }

    /**
     * Gets a list of all blocks renderable in the given theme type (frontend or
     * backend).
     *
     * @param string $type Possible values are 'front' or 'back'
     * @return array Blocks index by region name
     */
    protected function _inTheme($type = 'front')
    {
        $theme = option("{$type}_theme");
        $composer = Plugin::get($theme)->composer(true);
        $regions = $composer['extra']['regions'];
        $out = [];

        foreach ($regions as $slug => $name) {
            $blocks = $this->find()
                ->matching('BlockRegions', function ($q) use ($slug, $theme) {
                    return $q->where([
                        'BlockRegions.theme' => $theme,
                        'BlockRegions.region' => $slug,
                    ]);
                })
                ->where(['Blocks.status' => 1])
                ->all()
                ->filter(function ($block) {
                    return $block->renderable();
                })
                ->sortBy(function ($block) {
                    return $block->region->ordering;
                }, SORT_ASC);

            $out[$name] = $blocks;
        }

        return $out;
    }

    /**
     * Application rules.
     *
     * @param \Cake\ORM\RulesChecker $rules The rule checker
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // unique delta
        $rules->add($rules->isUnique(['delta', 'handler'], __d('block', 'Invalid delta, there is already a block with the same [delta, handler] combination.')));
        return $rules;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationWidget(Validator $validator)
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
            ->requirePresence('handler', 'create', __d('block', 'This field is required.'))
            ->add('handler', 'validHandler', [
                'rule' => 'notEmpty',
                'on' => 'create',
                'message' => __d('block', 'Invalid block handler'),
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
        return $this->validationWidget($validator)
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
     * Validates block settings before persisted in DB.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param array $data Information to be validated
     * @param \ArrayObject $options Options given to pathEntity()
     * @return void
     */
    public function settingsValidate(Event $event, array $data, ArrayObject $options)
    {
        if (!empty($options['entity']) && $options['entity']->has('handler')) {
            $block = $options['entity'];

            if ($block->handler !== 'Block') {
                $validator = $this->validator('default');
                $this->trigger("Block.{$block->handler}.settingsValidate", $data, $validator);
                $errors = $validator->errors((array)$data);
                foreach ($errors as $k => $v) {
                    $block->errors("settings:{$k}", $v);
                }
            }
        }
    }

    /**
     * Here we set default values for block's settings (used by Widget Blocks).
     *
     * Triggers the `Block.<handler>.settingsDefaults` event, event listeners
     * should catch the event and return an array as `key` => `value` with default
     * values.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $block The block where to put those values
     * @return array
     */
    public function settingsDefaultValues(Event $event, Entity $block)
    {
        if ($block->has('handler')) {
            return (array)$this->trigger("Block.{$block->handler}.settingsDefaults", $block)->result;
        }

        return [];
    }

    /**
     * Triggers the following events:
     *
     * - `Block.<handler>.beforeSave`
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block entity being saved
     * @param \ArrayObject $options Additional options given as an array
     * @return bool False if save operation should not continue, true otherwise
     */
    public function beforeSave(Event $event, Block $block, ArrayObject $options = null)
    {
        if ($block->isNew() && $block->get('handler') !== 'Block' && empty($block->delta)) {
            $block->calculateDelta();
        }

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
