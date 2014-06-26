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
namespace Menu\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Menu\Utility\Breadcrumb;

/**
 * Breadcrumb component.
 *
 * This component automatically attaches `BreadcrumbHelper` helper.
 */
class BreadcrumbComponent extends Component {

/**
 * The controller this component is attached to.
 *
 * @var \Cake\Controller\Controller
 */
	protected $_controller;

/**
 * Initializes BreadcrumbComponent for use in the controller.
 *
 * @param Event $event The initialize even.
 * @return void
 */
	public function initialize(Event $event) {
		$this->_controller = $event->subject();
		$this->_controller->helpers['Breadcrumb'] = ['className' => 'Menu\View\Helper\BreadcrumbHelper'];
	}

/**
 * Adds a new crumb to the stack.
 *
 * You can use this method without any argument, if you do it will automatically
 * try to guess the full breadcrumb path based on current URL (if current URL matches any URL
 * in any of your menus links).
 *
 * @param array|string $crumbs Single crumb or an array of multiple crumbs to push at once
 * @param string|null $url If both $crumbs and $url are string values they will be used as `title` and `URL` respectively
 * @return boolean True on success, false otherwise
 * @see \Menu\Utility\Breadcrumb::push()
 */
	public function push($crumbs = [], $url = null) {
		if (empty($crumbs) && empty($url)) {
			$MenuLinks = TableRegistry::get('Menu.MenuLinks')->addBehavior('Tree');
			// TODO: find possible matches for auto-breadcrumb
			$possibleMatches = [];

			$found = $MenuLinks->find('first')
				->where(['MenuLinks.url IN' => $possibleMatches]);

			if ($found) {
				$crumbs = $MenuLinks->find('path', ['for' => $found->id]);
			}
		}

		return Breadcrumb::push($crumbs, $url);
	}

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

}
