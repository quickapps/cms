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
namespace NukedApp\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

/**
 * Responds to installation events, process should be stopped as this plugin
 * is nuked (damaged event listeners).
 *
 */
class NukedAppHook implements EventListenerInterface
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
            'Plugin.NukedApp.beforeInstall' => 'beforeInstall',
        ];
    }

    /**
     * Triggered before plugin installation process starts.
     *
     * @return false Indicates the process to stop
     */
    public function beforeInstall(Event $event)
    {
        $shell = $event->subject();
        $shell->err('This plugin cannot be installed as it is NUKED');
        return false;
    }
}
