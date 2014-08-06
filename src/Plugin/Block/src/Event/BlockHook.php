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
 * Dispatches `ObjectRender.Block\Model\Entity\Block` rendering-request from View.
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
			'ObjectRender.Block\Model\Entity\Block' => [
				'callable' => 'renderBlock',
				'priority' => -1
			],
		];
	}

/**
 * Renders the given block entity.
 *
 * @param Cake\Event\Event $event
 * @param Block\Model\Entity\Block $block Block entity to be rendered
 * @param array $options Additional array of options
 * @return string The rendered block
 */
	public function renderBlock(Event $event, $block, $options = []) {
		return '';
	}

}
