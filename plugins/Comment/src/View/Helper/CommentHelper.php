<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Comment\View\Helper;

use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Entity;
use QuickApps\Core\HookTrait;
use QuickApps\Core\Plugin;
use QuickApps\View\Helper;

/**
 * CommentForm helper library.
 *
 * Renders comments for a given entity.
 */
class CommentHelper extends Helper {

	use HookTrait;

/**
 * Renders a comments section for given entity.
 *
 * Entity's comments must be in the `comments` property. It is automatically filled when
 * using `CommentableBBehavior`.
 * 
 * @param \Cake\ORM\Entity $entity Any valid entity
 * @return string
 */
	public function render(Entity $entity) {
		if (!isset($this->_View->viewVars['__commentComponentLoaded__'])) {
			throw new InternalErrorException(__d('comment', 'Illegal usage of \Comment\View\Helper\CommentHelper.'));
		}

		$this->alter('CommentHelper.render', $entity);
		$out = $this->hook('CommentHelper.beforeRender')->result;

		if ($this->config('visibility') > 0) {
			$this->config('entity', $entity);
			$out .= $this->_View->element('Comment.render_comments');

			if ($this->config('visibility') === 1) {
				$out .= $this->_View->element('Comment.render_comments_form');
			}
		}

		$out .= $this->hook('CommentHelper.afterRender')->result;
		return $out;
	}

/**
 * Shortcut for generate form-input's options.
 *
 * It take cares of adding an asterisk "*" to each required filed label,
 * it also adds the "required" attribute.
 * 
 * @param string $input Input name (author_name, author_email, author_web, subject or body)
 * @return array
 */
	public function optionsForInput($input) {
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
			if (
				in_array($input, ['author_name', 'author_email', 'author_web']) &&
				$this->config($input . '_required')
			) {
				$options[$input]['required'] = 'required';
			}

			return $options[$input];
		}

		return [];
	}

/**
 * Renders "Are You Human" form element.
 *
 * @return string HTML
 */
	public function captcha() {
		$out = '';

		if ($this->config('use_ayah') &&
			$this->config('ayah_publisher_key') &&
			$this->config('ayah_scoring_key')
		) {
			require_once Plugin::classPath('Comment') . 'Lib/ayah.php';
			$ayah = new \AYAH();
			$out = $this->_View->element('Comment.captcha_ayah', ['ayah' => $ayah]);
		}

		return $out;
	}

}
