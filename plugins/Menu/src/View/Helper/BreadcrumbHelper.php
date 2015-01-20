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

use Cake\Core\Exception\Exception;
use Cake\Utility\Hash;
use Menu\View\BreadcrumbRegistry;
use QuickApps\View\Helper;

/**
 * BreadcrumbHelper.
 *
 * For rendering HTML breadcrumbs.
 */
class BreadcrumbHelper extends Helper
{

/**
 * Helpers used by this helper class.
 *
 * @var array
 */
    public $helpers = ['Menu.Menu'];

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

        throw new Exception(__d('menu', 'Method "{0}" was not found.', $method));
    }

/**
 * Renders a breadcrumb menu list.
 *
 * This methods renders an `<ol>` HTML menu using `MenuHelper` class, check this
 * class for more information about valid options.
 *
 * @param array $options Array of options for `MenuHelper::render()` method
 * @return string HTML
 * @see \Menu\View\Helper\MenuHelper::render()
 */
    public function render($options = [])
    {
        $items = $this->getStack();
        $options = Hash::merge([
            'breadcrumbGuessing' => false,
            'class' => 'breadcrumb',
            'templates' => [
                'root' => '<ol{{attrs}}>{{content}}</ol>',
            ],
            'formatter' => function ($entity, $info) {
                $options = [];
                if ($info['index'] === $info['total']) {
                    $options['childAttrs'] = ['class' => 'active'];
                    $options['templates']['link'] = '{{content}}';
                }
                return $this->Menu->formatter($entity, $info, $options);
            }
        ], $options);
        return $this->Menu->render($items, $options);
    }

/**
 * Renders the breadcrumb if there at least one crumb.
 *
 * Simplifies the following situation:
 *
 *     if ($this->Breadcrumb->count()) {
 *         echo $this->Breadcrumb->render();
 *     }
 *
 * @param array $options Array of options for `render()` method
 * @return string HTML code, or an empty string if no crumbs are found
 * @see \Menu\View\Helper\BreadcrumbHelper::render()
 */
    public function renderIfNotEmpty($options = [])
    {
        if ($this->count()) {
            return $this->render($options);
        }

        return '';
    }
}
