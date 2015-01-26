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
namespace Block\Event;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

/**
 * Main Hook Listener for System plugin.
 *
 */
class BlockHooktag implements EventListenerInterface
{

    /**
     * Returns a list of events this Event Listener is implementing. When the class
     * is registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Hooktag.block' => 'hooktagBlock',
        ];
    }

    /**
     * Implements the "block" hooktag.
     *
     *     [block 1 /]
     *
     * @param \Cake\Event\Event $event The event that was fired
     * @param array $atts An associative array of attributes, or an empty string if
     *  no attributes are given
     * @param string $content The enclosed content (if the hooktag is used in its
     *  enclosing form)
     * @param string $tag The hooktag tag
     * @return string
     */
    public function hooktagBlock(Event $event, array $atts, $content, $tag)
    {
        $out = '';
        if (isset($atts[0]) && intval($atts[0]) > 0) {
            try {
                $id = intval($atts[0]);
                $block = TableRegistry::get('Block.Blocks')->get($id);
                $event->subject()->loadHelper('Block.Block');
                $out = $event->subject()->Block->render($block);
            } catch (\Exception $ex) {
                $out = !Configure::read('debug') ?: "<!-- block #{$id} not found -->";
            }
        }
        return $out;
    }
}
