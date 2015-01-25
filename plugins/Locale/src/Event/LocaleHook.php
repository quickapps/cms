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
namespace Locale\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

/**
 * Main Hook Listener for Locale plugin.
 *
 */
class LocaleHook implements EventListenerInterface
{

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class is
     * registered in an event manager, each individual method will be associated with
     * the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Block.Locale.display' => 'renderBlock',
        ];
    }

    /**
     * Renders all blocks registered by Locale plugin.
     *
     * Locale plugin has one built-in blocks that comes with every QuickApps CMS
     * installation: "Language witcher" which allows users to change from one
     * language to another.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block being rendered
     * @param array $options Additional options as an array
     * @return string
     */
    public function renderBlock(Event $event, $block, $options = [])
    {
        return $event->subject()->element("Locale.{$block->delta}", compact('block', 'options'));
    }
}
