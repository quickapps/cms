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

use Block\Model\Entity\Block;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

/**
 * Block rendering dispatcher class.
 *
 * This class allows users to render single blocks in any template using the
 * `View::render()` method:
 *
 * ```php
 * $block = TableRegistry::get('Block.Blocks')->get($id);
 * echo $this->render($block);
 * ```
 *
 * However it is also possible to directly render the block object using its
 * `render()` method:
 *
 * ```php
 * $block = TableRegistry::get('Block.Blocks')->get($id);
 * echo $block->render();
 * ```
 */
class BlockHook implements EventListenerInterface
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
            'Render.Block\Model\Entity\Block' => 'renderBlock',
        ];
    }

    /**
     * Renders the given block.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block Block entity to be rendered
     * @param array $options Additional options, will be passed to the template
     *  element being rendered
     * @return string The rendered block
     */
    public function renderBlock(Event $event, Block $block, $options = [])
    {
        return $block->render();
    }
}
