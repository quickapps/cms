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

use Cake\ORM\Entity;
use Cake\View\Helper;
use QuickApps\Utility\HookTrait;

/**
 * Form helper library.
 *
 * Overwrites CakePHP's Form Helper and adds alter hooks to every method,
 * so plugins may alter form elements rendering cycle.
 */
class CommentFormHelper extends Helper {

	use HookTrait;

/**
 * Renders a comment form for the given entity.
 * 
 * @param \Cake\ORM\Entity $entity Any valid entity
 * @param array $options Array of options
 * @return string
 */
	public function create(Entity $entity, array $options) {
		$this->alter('CommentFormHelper.create', $entity, $options);
		$options['entity'] = $entity;
		$pass = ['options' => $options] + $this->_View->viewVars;
		return $this->_View->element('Comment.render_comments', $pass);
	}

}
