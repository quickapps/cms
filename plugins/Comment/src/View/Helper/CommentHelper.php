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
namespace Comment\View\Helper;

use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Entity;
use Captcha\CaptchaManager;
use CMS\Event\EventDispatcherTrait;
use CMS\View\Helper;

/**
 * CommentForm helper library.
 *
 * Renders comments for a given entity.
 */
class CommentHelper extends Helper
{

    use EventDispatcherTrait;

    /**
     * Renders a comments section for given entity.
     *
     * Entity's comments must be in the `comments` property. It is automatically
     * filled when using `CommentableBehavior`.
     *
     * The following events will be triggered:
     *
     * - `CommentHelper.beforeRender`: Triggered before default rendering operation
     *   starts. By stopping this event, you can return the final value of the
     *   rendering operation.
     *
     * - `CommentHelper.afterRender`: Triggered after default rendering operation is
     *   completed. Listeners will receive the rendered output. By stopping this
     *   event, you can return the final value of the rendering operation.
     *
     * @param \Cake\ORM\Entity $entity Any valid entity
     * @return string
     * @throws \Cake\Network\Exception\InternalErrorException When comment
     *  component was not loaded
     */
    public function render(Entity $entity)
    {
        if (!isset($this->_View->viewVars['__commentComponentLoaded__'])) {
            throw new InternalErrorException(__d('comment', 'Illegal usage of \Comment\View\Helper\CommentHelper.'));
        }

        $this->config('entity', $entity);
        $event = $this->trigger(['CommentHelper.beforeRender', $this->_View]);
        if ($event->isStopped()) {
            return $event->result;
        }

        $out = '';
        if ($this->config('visibility') > 0) {
            $out .= $this->_View->element('Comment.render_comments_list');
            if ($this->config('visibility') === 1) {
                $out .= $this->_View->element('Comment.render_comments_form');
            }
        }

        $event = $this->trigger(['CommentHelper.afterRender', $this->_View], $out);
        if ($event->isStopped()) {
            return $event->result;
        }

        return $out;
    }

    /**
     * Shortcut for generate form-input's options.
     *
     * It take cares of adding an asterisk "*" to each required filed label, it also
     * adds the "required" attribute.
     *
     * @param string $input Input name (author_name, author_email, author_web, subject or body)
     * @return array
     */
    public function optionsForInput($input)
    {
        $options = [
            'author_name' => [
                'label' => ($this->config('anonymous_name_required') ? __d('comment', 'Name (required)') : __d('comment', 'Name'))
            ],
            'author_email' => [
                'label' => ($this->config('anonymous_email_required') ? __d('comment', 'e-Mail (required)') : __d('comment', 'e-Mail'))
            ],
            'author_web' => [
                'label' => ($this->config('anonymous_web_required') ? __d('comment', 'Website (required)') : __d('comment', 'Website'))
            ],
            'subject' => [
                'label' => __d('comment', 'Subject (required)'),
                'required',
            ],
            'body' => [
                'type' => 'textarea',
                'label' => __d('comment', 'Message (required)'),
                'required',
            ],
        ];

        if (isset($options[$input])) {
            if (in_array($input, ['author_name', 'author_email', 'author_web']) &&
                $this->config($input . '_required')
            ) {
                $options[$input]['required'] = 'required';
            }

            return $options[$input];
        }

        return [];
    }

    /**
     * Renders CAPTCHA form element.
     *
     * @return string HTML
     */
    public function captcha()
    {
        if ($this->config('use_captcha')) {
            return CaptchaManager::adapter()->render($this->_View);
        }
        return '';
    }
}
