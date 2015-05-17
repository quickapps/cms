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
namespace CMS\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller as CakeController;
use CMS\Error\SiteUnderMaintenanceException;
use CMS\Event\EventDispatcherTrait;
use CMS\View\ViewModeAwareTrait;
use ReflectionException;
use ReflectionMethod;

/**
 * Main controller class for organization of business logic.
 *
 * Provides basic QuickAppsCMS functionality, such as themes handling,
 * user authorization, and more.
 */
class Controller extends CakeController
{

    use EventDispatcherTrait;
    use ViewModeAwareTrait;

    /**
     * In use theme name.
     *
     * @var string
     */
    public $theme;

    /**
     * Name of the layout that should be used by current theme.
     *
     * @var string
     */
    public $layout;

    /**
     * The name of the View class controllers sends output to.
     *
     * @var string
     */
    public $viewClass = 'CMS\View\View';

    /**
     * An array containing the names of components controllers uses.
     *
     * @var array
     */
    public $components = [
        'Auth' => [
            'className' => 'User.Auth',
            'authenticate' => [
                AuthComponent::ALL => [
                    'username' => 'username',
                    'password' => 'password',
                    'userModel' => 'User.Users',
                    'scope' => ['Users.status' => 1],
                    'contain' => ['Roles'],
                    'passwordHasher' => 'Default',
                ],
                'User.Form',
                'User.Anonymous',
            ],
            'authorize' => ['User.Cached'],
            'loginAction' => ['plugin' => 'User', 'controller' => 'gateway', 'action' => 'login'],
            'logoutRedirect' => '/',
            'unauthorizedRedirect' => ['plugin' => 'User', 'controller' => 'gateway', 'action' => 'unauthorized'],
        ],
        'Menu.Breadcrumb',
        'Flash',
    ];

    /**
     * Constructor.
     *
     * @param \Cake\Network\Request $request Request object for this controller.
     *  Can be null for testing, but expect that features that use the request
     *  parameters will not work.
     * @param \Cake\Network\Response $response Response object for this controller.
     */
    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);
        $location = $this->response->location();
        if (empty($location)) {
            $this->viewMode('default');
            $this->prepareTheme();
            $this->checkMaintenanceMode();
        }
        $this->response->header('Content-Language', language('code'));
        $this->response->header('X-Generator', sprintf('QuickAppsCMS %s (http://quickappscms.org)', quickapps('version')));
    }

    /**
     * Method to check that an action is accessible from a URL.
     *
     * Override this method to change which controller methods can be reached.
     * The default implementation disallows access to all methods defined on
     * Cake\Controller\Controller or CMS\Controller\Controller, and allows all
     * public methods on all subclasses of this class.
     *
     * @param string $action The action to check.
     * @return bool Whether or not the method is accessible from a URL.
     */
    public function isAction($action)
    {
        try {
            $method = new ReflectionMethod($this, $action);
        } catch (\ReflectionException $e) {
            return false;
        }
        if (!$method->isPublic()) {
            return false;
        }
        if ($method->getDeclaringClass()->name === 'Cake\Controller\Controller' ||
            $method->getDeclaringClass()->name === 'CMS\Controller\Controller'
        ) {
            return false;
        }
        return true;
    }

    /**
     * Shortcut for Controller::set('title_for_layout', ...)
     *
     * @param string $titleForLayout The title to use on layout's title tag
     * @return void
     */
    public function title($titleForLayout)
    {
        $this->set('title_for_layout', $titleForLayout);
    }

    /**
     * Shortcut for Controller::set('description_for_layout', ...)
     *
     * @param string $descriptionForLayout The description to use as
     *  meta-description on layout's head tag
     * @return void
     */
    public function description($descriptionForLayout)
    {
        $this->set('description_for_layout', $descriptionForLayout);
    }

    /**
     * Sets the theme to use.
     *
     * @return void
     */
    public function prepareTheme()
    {
        $this->layout = 'default';
        if (!empty($this->request->params['prefix']) &&
            strtolower($this->request->params['prefix']) === 'admin'
        ) {
            $this->theme = option('back_theme');
        } else {
            $this->theme = option('front_theme');
        }

        if ($this->request->isAjax()) {
            $this->layout = 'ajax';
        }

        if ($this->request->isHome()) {
            $this->layout = 'home';
        }

        if ($this->request->isDashboard()) {
            $this->layout = 'dashboard';
        }
    }

    /**
     * Checks if maintenance is enabled, and renders the corresponding maintenance
     * message.
     *
     * Login & logout sections of the site still working even on maintenance mode,
     * administrators can access the whole site as well.
     *
     * @return void
     * @throws CMS\Error\SiteUnderMaintenanceException When site is under
     *  maintenance mode
     */
    public function checkMaintenanceMode()
    {
        if (option('site_maintenance') &&
            !$this->request->isUserAdmin() &&
            !in_array("{$this->request->plugin}:{$this->request->controller}:{$this->request->action}", ['User:gateway:login', 'User:gateway:logout'])
        ) {
            $allowedIps = (array)array_filter(array_map('trim', explode(',', option('site_maintenance_ip'))));
            if (!in_array(env('REMOTE_ADDR'), $allowedIps)) {
                throw new SiteUnderMaintenanceException(option('site_maintenance_message'));
            }
        }
    }
}
