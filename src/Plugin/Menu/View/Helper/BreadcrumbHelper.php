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
namespace Menu\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;
use Menu\Utility\Breadcrumb;

/**
 * BreadcrumbHelper.
 *
 * For rendering HTML breadcrumbs.
 */
class BreadcrumbHelper extends Helper {

/**
 * Helpers used by this helper class.
 *
 * @var array
 */
	public $helpers = ['Menu.Menu'];

/**
 * Method delegation.
 *
 * We try to dispatch unexisting method to `\Menu\Utility\Breadcrumb` class.
 *
 * @param string $method Name of the method to be invoked
 * @param array $args List of arguments passed to the function
 * @return mixed
 * @throws \Cake\Error\Exception When the method is unknown
 */
	public function __call($method, $args) {
		if (method_exists('\Menu\Utility\Breadcrumb', $method)) {
			return call_user_func_array(['\Menu\Utility\Breadcrumb', $method], $args);
		}

		throw new \Cake\Error\Exception(__d('menu', 'Method "%s" was not found.', $method));
	}

/**
 * Renders a breadcrumb list.
 *
 * This methods renders an `<ol>` HTML menu using `MenuHelper` class,
 * check this class for more information about valid options.
 *
 * @return string HTML
 * @see \Menu\View\Helper\MenuHelper::render()
 */
	public function render($options = []) {
		$items = $this->get();
		$options = Hash::merge($options, [
			'class' => 'breadcrumb',
			'templates' => [
				'root' => '<ol{{attrs}}>{{content}}</ol>',
			]
		]);

		return $this->Menu->render($items, $options);
	}

}
