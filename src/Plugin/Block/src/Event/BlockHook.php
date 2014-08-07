<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Hook;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListener;
use Cake\Event\EventManager;
use Cake\Utility\Hash;
use QuickApps\Utility\HookTrait;
use QuickApps\View\ViewModeTrait;

/**
 * Block rendering dispatcher.
 *
 * Dispatches `Render.Block\Model\Entity\Block` rendering-request from View.
 *
 *     $block = new \Block\Model\Entity\Block();
 *     $this->render($block);
 *     // triggers: `Render.Block\Model\Entity\Block`
 *
 * It also dispatches BlockHelper::block():
 *
 *     $block = new \Block\Model\Entity\Block();
 *     $this->Block->render($block);
 *     // triggers: `Block.Block.display`
 */
class BlockHook implements EventListener {

	use HookTrait;
	use ViewModeTrait;

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is registered
 * in an event manager, each individual method will be associated with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'Render.Block\Model\Entity\Block' => 'renderBlock',
			'Block.Block.display' => 'displayBlock',
		];
	}

/**
 * Renders the given block entity.
 *
 * @param \Cake\Event\Event $event
 * @param \Block\Model\Entity\Block $block Block entity to be rendered
 * @return string The rendered block
 */
	public function renderBlock(Event $event, $block, $options = []) {
		return $event->subject->Block->render($block, $options);
	}

/**
 * Renders the given block entity.
 *
 * @param \Cake\Event\Event $event
 * @param \Block\Model\Entity\Block $block Block entity to be rendered
 * @return string The rendered block
 */
	public function displayBlock(Event $event, $block, $options = []) {
		return $event->subject->_View->element('Block.render_block', ['block' => $block, 'options' => $options]);
	}

}
