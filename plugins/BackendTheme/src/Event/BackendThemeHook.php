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
namespace BackendTheme\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

/**
 * Main Hook Listener for Backend Theme.
 *
 */
class BackendThemeHook implements EventListenerInterface
{

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class
     * is registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Plugin.BackendTheme.settingsDefaults' => 'settingsDefaults',
        ];
    }

    /**
     * Defaults settings for Comment's settings form.
     *
     * @param Event $event The event that was triggered
     * @return array
     */
    public function settingsDefaults(Event $event)
    {
        return [
            'fixed_layout' => true,
            'boxed_layout' => false,
            'collapsed_sidebar' => false,
            'skin' => 'green',
        ];
    }
}
