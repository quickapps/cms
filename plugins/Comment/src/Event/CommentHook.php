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
namespace Comment\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

/**
 * Main Hook Listener for Comment plugin.
 *
 */
class CommentHook implements EventListenerInterface
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
            'Render.Comment\Model\Entity\Comment' => 'renderComment',
            'Plugin.Comment.settingsDefaults' => 'settingsDefaults',
        ];
    }

/**
 * Renders a single Comment.
 *
 * @param Event $event The event that was triggered
 * @param \Comment\Model\Entity\Comment $comment The comment entity to render
 * @param array $options Additional options given as an array
 * @return string HTML
 */
    public function renderComment(Event $event, $comment, $options = [])
    {
        $View = $event->subject;
        $html = $View->element('Comment.render_comment', compact('comment', 'options'));
        return $html;
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
            'visibility' => 0,
            'auto_approve' => false,
            'allow_anonymous' => false,
            'anonymous_name' => false,
            'anonymous_name_required' => true,
            'anonymous_email' => false,
            'anonymous_email_required' => true,
            'anonymous_web' => false,
            'anonymous_web_required' => true,
            'text_processing' => 'plain',
            'use_ayah' => false,
            'ayah_publisher_key' => '',
            'ayah_scoring_key' => '',
            'use_akismet' => false,
            'akismet_key' => '',
            'akismet_action' => 'mark',
        ];
    }
}
