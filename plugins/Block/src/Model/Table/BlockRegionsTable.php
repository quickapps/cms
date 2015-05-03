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

use Block\Model\Entity\BlockRegion;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \ArrayObject;

/**
 * Represents "block_regions" database table.
 *
 */
class BlockRegionsTable extends Table
{

    /**
     * Initialize method.
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsTo('Blocks', [
            'className' => 'Block.Blocks',
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
        // unique block_id
        $rules->add($rules->isUnique(['block_id', 'theme'], __d('block', 'This block is already assigned to this theme.')));
        return $rules;
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
            ->add('theme', 'validTheme', [
                'rule' => function ($value, $context) {
                    $exists = plugin()
                        ->filter(function ($plugin) use ($value) {
                            return $plugin->isTheme && $plugin->name === $value;
                        })
                        ->first();
                    return !empty($exists);
                },
                'message' => __d('block', 'Invalid theme for region.'),
            ]);
    }

    /**
     * Clears blocks cache after assignment changes.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\BlockRegion $blockRegion The block entity that was deleted
     * @param \ArrayObject $options Additional options given as an array
     * @return void
     */
    public function afterSave(Event $event, BlockRegion $blockRegion, ArrayObject $options = null)
    {
        $this->clearCache();
    }

    /**
     * Clears blocks cache after assignment is removed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\BlockRegion $blockRegion The block entity that was deleted
     * @param \ArrayObject $options Additional options given as an array
     * @return void
     */
    public function afterDelete(Event $event, BlockRegion $blockRegion, ArrayObject $options = null)
    {
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
