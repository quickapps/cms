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
namespace Taxonomy\Event;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

/**
 * Main Hook Listener for Taxonomy plugin.
 *
 */
class TaxonomyHook implements EventListenerInterface
{

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class is
     * registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Block.Taxonomy.display' => 'renderBlock',
            'Block.Taxonomy.settings' => 'settingsBlock',
            'Block.Taxonomy.afterSave' => 'afterSaveBlock',
        ];
    }

    /**
     * Renders all blocks registered by Taxonomy plugin.
     *
     * Taxonomy plugin has one built-in block that comes with every QuickAppsCMS
     * installation: "Categories" which allows to create HTML categories lists.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block being rendered
     * @param array $options Additional options as an array
     * @return string
     */
    public function renderBlock(Event $event, $block, $options = [])
    {
        return $event->subject()->element("Taxonomy.{$block->delta}_render", compact('block', 'options'));
    }

    /**
     * Renders block's settings form elements.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block
     * @param array $options Additional options as an array
     * @return string
     */
    public function settingsBlock(Event $event, $block, $options = [])
    {
        return $event->subject()->element("Taxonomy.{$block->delta}_settings", compact('block', 'options'));
    }

    /**
     * Clear counters cache after block settings changes.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block that was saved
     * @param array $options Additional options as an array
     * @return string
     */
    public function afterSaveBlock(Event $event, $block, $options = [])
    {
        Cache::clear(false, 'terms_count');
    }
}
