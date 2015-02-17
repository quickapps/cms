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
namespace Menu\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Menu\View\BreadcrumbRegistry;
use QuickApps\Core\StaticCacheTrait;

/**
 * Breadcrumb component.
 *
 * This component automatically attaches `BreadcrumbHelper` helper.
 */
class BreadcrumbComponent extends Component
{

    use StaticCacheTrait;

    /**
     * The controller this component is attached to.
     *
     * @var \Cake\Controller\Controller
     */
    protected $_controller;

    /**
     * Initializes BreadcrumbComponent for use in the controller.
     *
     * @param Event $event The event that was triggered
     * @return void
     */
    public function beforeFilter(Event $event)
    {
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
     * ```php
     * $this->Breadcrumb->push();
     * ```
     *
     * Also, you can can pass a string as first argument representing an URL, if you
     * do it will try to find that URL in in any of your menus, and then generate its
     * corresponding breadcrumb.
     *
     * ```php
     * $this->Breadcrumb->push('/admin/some/url');
     * ```
     *
     * @param array|string|null $crumbs Single crumb or an array of multiple crumbs to push
     *  at once
     * @param mixed $url If both $crumbs and $url are string values they will be
     *  used as `title` and `URL` respectively
     * @return \Menu\Controller\Component\BreadcrumbComponent This instance (for chaining)
     * @see \Menu\View\BreadcrumbRegistry::push()
     */
    public function push($crumbs = null, $url = null)
    {
        if ($crumbs === null && $url === null) {
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

        BreadcrumbRegistry::push($crumbs, $url);
        return $this;
    }

    /**
     * Returns possible URL combinations for the given URL or current request's URL.
     *
     * ### Example:
     *
     * For the given URL, `/admin/node/manage/index/arg1/arg2?get1=v1&get2=v2`
     * where:
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
     * ```php
     * [
     *     "/admin/node/node/index/arg1/arg2?get1=v1&get2=v2",
     *     "/admin/node/node/arg1/arg2",
     *     "/admin/node/arg1/arg2",
     *     "/admin/node/arg1",
     *     "/admin/node",
     * ]
     * ```
     *
     * @param string|null $url The URL to chunk as string value, set to null
     *  will use current request URL.
     * @return array
     */
    protected function _urlChunk($url = null)
    {
        $request = $this->_controller->request;
        $url = !$url ? '/' . $request->url : $url;
        $cacheKey = 'urlChunk_' . md5($url);
        $cache = static::cache($cacheKey);

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

        return static::cache($cacheKey, array_unique($out));
    }

    /**
     * Method delegation.
     *
     * We try to dispatch unexisting method to `\Menu\View\BreadcrumbRegistry` class.
     *
     * @param string $method Name of the method to be invoked
     * @param array $args List of arguments passed to the function
     * @return mixed
     * @throws \Cake\Core\Exception\Exception When the method is unknown
     */
    public function __call($method, $args)
    {
        if (method_exists('\Menu\View\BreadcrumbRegistry', $method)) {
            return call_user_func_array(['\Menu\View\BreadcrumbRegistry', $method], $args);
        }

        throw new \Cake\Core\Exception\Exception(__d('menu', 'Method "{0}" was not found.', $method));
    }
}
