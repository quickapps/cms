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
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
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
 * try to guess the full breadcrumb path based on current URL (if current URL
 * matches any URL in any of your menus links).
 *
 *     $this->Breadcrumb->push();
 *
 * Also, you can can pass a string as first argument representing an URL, if you
 * do it will try to find that URL in in any of your menus, and then generate its
 * corresponding breadcrumb.
 *
 *     $this->Breadcrumb->push('/admin/some/url');
 *
 * @param array|string $crumbs Single crumb or an array of multiple crumbs
 * to push at once
 * @param mixed $url If both $crumbs and $url are string values they will be
 * used as `title` and `URL` respectively
 * @return bool True on success, false otherwise
 * @see \Menu\Utility\Breadcrumb::push()
 */
	public function push($crumbs = [], $url = null) {
		if ($crumbs === [] && $url === null) {
			$MenuLinks = TableRegistry::get('Menu.MenuLinks');
			$MenuLinks->removeBehavior('Tree');
			$possibleMatches = $this->_urlChunk();
			$found = $MenuLinks
				->find()
				->select(['id', 'menu_id'])
				->where(['MenuLinks.url IN' => $possibleMatches])
				->first();

			$crumbs = [];
			if ($found) {
				$MenuLinks->addBehavior('Tree', ['scope' => ['menu_id' => $found->menu_id]]);
				$crumbs = $MenuLinks->find('path', ['for' => $found->id])->toArray();
			}
		} elseif (is_string($crumbs) && strpos($crumbs, '/') !== false && $url === null) {
			$MenuLinks = TableRegistry::get('Menu.MenuLinks');
			$MenuLinks->removeBehavior('Tree');
			$found = $MenuLinks
				->find()
				->select(['id', 'menu_id'])
				->where(['MenuLinks.url IN' => $crumbs])
				->first();

			$crumbs = [];
			if ($found) {
				$MenuLinks->addBehavior('Tree', ['scope' => ['menu_id' => $found->menu_id]]);
				$crumbs = $MenuLinks->find('path', ['for' => $found->id])->toArray();
			}
		}

		return Breadcrumb::push($crumbs, $url);
	}

/**
 * Returns possible URL combinations for the given URL or current request's URL.
 *
 * ### Example:
 *
 * For the given URL, `/admin/node/manage/index/arg1/arg2?get1=v1&get2=v2` where:
 *
 * - `/admin`: Prefix.
 * - `/node`: Plugin name.
 * - `/manage`: Controller name.
 * - `/index`: Controller's action.
 * - `/arg1` and `/arg2`: Action's arguments.
 * - `get1` and `get2`: GET arguments.
 *
 * The following array will be returned by this method:
 *
 *     [
 *         "/admin/node/node/index/arg1/arg2?get1=v1&get2=v2",
 *         "/admin/node/node/arg1/arg2",
 *         "/admin/node/arg1/arg2",
 *         "/admin/node/arg1",
 *         "/admin/node",
 *     ]
 *
 * @param string|boolean $url The URL to chunk as string value, set to false will
 * use current request URL.
 * @return array
 */
	protected function _urlChunk($url = false) {
		$request = $this->_controller->request;
		$url = $url === false ? '/' . $request->url : $url;
		$cacheKey = 'urlChunk_' . md5($url);
		$cache = static::_cache($cacheKey);

		if ($cache !== null) {
			return $cache;
		}

		$parsedURL = Router::parse($url);
		$out = [$url];
		$passArguments = [];

		if (!empty($parsedURL['?'])) {
			unset($parsedURL['?']);
		}

		if (!empty($parsedURL['pass'])) {
			$passArguments = $parsedURL['pass'];
			$parsedURL['pass'] = null;
			$parsedURL = array_merge($parsedURL, $passArguments);
		}

		// "/controller_name/index" -> "/controller"
		if ($parsedURL['action'] === 'index') {
			$parsedURL['action'] = null;
			$out[] = Router::url($parsedURL);
		}

		// "/plugin_name/plugin_name/action_name" -> "/plugin_name/action_name"
		if (!empty($parsedURL['plugin']) && strtolower($parsedURL['controller']) === strtolower($parsedURL['plugin'])) {
			$parsedURL['plugin'] = null;
			$out[] = Router::url($parsedURL);
		}

		if (!empty($passArguments)) {
			$passArguments = array_reverse($passArguments);
			foreach ($passArguments as $pass) {
				unset($parsedURL[array_search($pass, $parsedURL)]);
				$out[] = Router::url($parsedURL);
			}
		}

		$out = array_map(function ($value) use ($request) {
			if (str_starts_with($value, $request->base)) {
				return str_replace_once($request->base, '', $value);
			}
			return $value;
		}, $out);

		return static::_cache($cacheKey, array_unique($out));
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

		throw new \Cake\Error\Exception(__d('menu', 'Method "{0}" was not found.', $method));
	}

}
