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
namespace QuickApps\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller as CakeController;
use Cake\I18n\I18n;
use QuickApps\Error\SiteUnderMaintenanceException;
use QuickApps\Event\HookAwareTrait;
use QuickApps\View\ViewModeAwareTrait;
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

    use HookAwareTrait;
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
    public $viewClass = 'QuickApps\View\View';

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
        $this->prepareLanguage();
        $location = $this->response->location();
        if (empty($location)) {
            $this->switchViewMode('default');
            $this->prepareTheme();
            $this->checkMaintenanceMode();
        }
    }

    /**
     * Method to check that an action is accessible from a URL.
     *
     * Override this method to change which controller methods can be reached.
     * The default implementation disallows access to all methods defined on
     * Cake\Controller\Controller or QuickApps\Controller\Controller, and allows all
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
            $method->getDeclaringClass()->name === 'QuickApps\Controller\Controller'
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
     * Prepares the default language to use.
     *
     * This methods apply the following filters looking for language to use:
     *
     * - GET parameter: If `locale` GET parameter is present in current request,
     *   and if it is a valid language code, it will be used as current language
     *   and also will be persisted on `locale` session for further use.
     * - URL: If current URL is prefixed with a valid language code and
     *   `url_locale_prefix` option is enabled, URL's language code will be used.
     * - Locale session: If `locale` session exists it will be used.
     * - User session: If user is logged in and has selected a valid preferred
     *   language it will be used.
     * - Default: Site's language will be used otherwise.
     *
     * ---
     *
     * If `url_locale_prefix` option is enabled, and current request's URL has not
     * language prefix on it, user will be redirected to a locale-prefixed version
     * of the requested URL (using the language code selected as explained above).
     *
     * For example:
     *
     *     /article/demo-article.html
     *
     * Might redirects to:
     *
     *     /en-us/article/demo-article.html
     *
     * @return void
     */
    public function prepareLanguage()
    {
        $locales = array_keys(quickapps('languages'));
        $localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';
        $normalizedURL = str_replace('//', '/', "/{$this->request->url}"); // starts with "/""

        if (!empty($this->request->query['locale']) && in_array($this->request->query['locale'], $locales)) {
            $this->request->session()->write('locale', $this->request->query['locale']);
            $code = $this->request->session()->read('locale');
        } elseif (option('url_locale_prefix') && preg_match("/\/{$localesPattern}\//", $normalizedURL, $matches)) {
            $code = $matches[1];
        } elseif ($this->request->session()->check('locale') && in_array($this->request->session()->read('locale'), $locales)) {
            $code = $this->request->session()->read('locale');
        } elseif ($this->request->is('userLoggedIn') && in_array(user()->locale, $locales)) {
            $code = user()->locale;
        } elseif (in_array(option('default_language'), $locales)) {
            $code = option('default_language');
        } else {
            $code = CORE_LOCALE;
        }

        I18n::locale(normalizeLocale($code));
        if (option('url_locale_prefix') &&
            !$this->request->is('home') &&
            !preg_match("/\/{$localesPattern}\//", $normalizedURL)
        ) {
            $url = '/' . I18n::locale() . $normalizedURL;
            $this->redirect($url, 200);
        }
    }

    /**
     * Sets the theme to use.
     *
     * @return void
     */
    public function prepareTheme()
    {
        $this->layout = 'default';
        if (!empty($this->request->params['prefix']) && strtolower($this->request->params['prefix']) === 'admin') {
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
     * @throws QuickApps\Error\SiteUnderMaintenanceException When site is under
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
