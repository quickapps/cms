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
namespace System\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use QuickApps\Event\HookAwareTrait;

/**
 * Main Hook Listener for System plugin.
 *
 */
class SystemHook implements EventListenerInterface
{

    use HookAwareTrait;

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
            'Block.System.display' => 'displayBlock',
        ];
    }

    /**
     * All blocks registered by "System" plugin are associated blocks
     * of some core's menus. So we redirect rendering task to Menu plugin's render.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block being rendered
     * @param array $options Array of options for BlockHelper::render() method
     * @return array
     */
    public function displayBlock(Event $event, $block, $options)
    {
        return $this->trigger(['Block.Menu.display', $event->subject()], $block, $options)->result;
    }
}
