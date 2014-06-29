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

use Cake\Event\Event;
use Cake\Event\EventListener;

/**
 * Main Hook Listener for Comment plugin.
 *
 */
class CommentHook implements EventListener {

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is registered
 * in an event manager, each individual method will be associated with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'ObjectRender.Comment\Model\Entity\Comment' => 'renderComment'
		];
	}

/**
 * Renders a single Comment.
 *
 * @param Event $event
 * @param Comment\Model\Entity\Comment $comment
 * @param array $options
 * @return string HTML
 */
	public function renderComment(Event $event, $comment, $options = []) {
		$View = $event->subject;
		$html = $View->element('Comment.render_comment', compact('comment', 'options'));

		return $html;
	}

}
