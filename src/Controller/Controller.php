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
namespace QuickApps\Controller;

use Cake\Controller\Controller as CakeCotroller;
use Cake\Core\Configure;
use QuickApps\Utility\AlertTrait;
use QuickApps\Utility\DetectorTrait;
use QuickApps\Utility\HookTrait;
use QuickApps\View\ViewModeTrait;

/**
 * Main controller class for organization of business logic.
 *
 * Provides basic QuickAppsCMS functionality, such as themes handling,
 * user authorization, and more.
 */
class Controller extends CakeCotroller {

	use AlertTrait;
	use DetectorTrait;
	use HookTrait;
	use ViewModeTrait;

/**
 * In use theme name.
 *
 * @var string
 */
	public $theme = 'FrontendTheme';

/**
 * Name of the layout that should be used by current theme.
 *
 * @var string
 */
	public $layout = 'default';

/**
 * The name of the View class controllers sends output to.
 *
 * @var string
 */
	public $viewClass = 'QuickApps\View\View';

/**
 * An array containing the names of helpers controllers uses.
 *
 * @var array
 */
	public $helpers = [
		'Html' => ['className' => 'QuickApps\View\Helper\HtmlHelper'],
		'Form' => ['className' => 'QuickApps\View\Helper\FormHelper'],
		'Menu' => ['className' => 'Menu\View\Helper\MenuHelper'],
	];

/**
 * An array containing the names of components controllers uses.
 *
 * @var array
 */
	public $components = [
		'Menu.Breadcrumb',
		'Session',
	];

/**
 * Constructor.
 *
 * @param \Cake\Network\Request $request Request object for this controller. Can be null for testing,
 *  but expect that features that use the request parameters will not work.
 * @param \Cake\Network\Response $response Response object for this controller.
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->switchViewMode('default');
		$this->_prepareLanguage();
		$this->_prepareTheme();
		
		// TODO: remove this lines
		$this->request->session()->write('user', [
			'id' => 1,
			'name' => 'Chris',
			'username' => 'admin',
			'email' => 'chris@quickapps.es',
			'locale' => 'es',
			// TODO: stores in session "user.roles" both role ID and role Slugs. e.g. [1 => 'administrator', 2 => 'manager']
			'roles' => [1 => 'administrator', 2 => 'manager'],
		]);
	}

/**
 * Shortcut for Controller::set('title_for_layout', ...)
 *
 * @param string $title_for_layout
 * @return void
 */
	public function title($title_for_layout) {
		$this->set('title_for_layout', $title_for_layout);
	}

/**
 * Shortcut for Controller::set('description_for_layout', ...)
 *
 * @param string $description_for_layout
 * @return void
 */
	public function description($description_for_layout) {
		$this->set('description_for_layout', $description_for_layout);
	}

/**
 * Prepares the default language to use.
 *
 * If use is logged in and has selected a preferred language, we will use it.
 * Default site's language will be used otherwise.
 *
 * If `url_locale_prefix` option is enabled, and current request's URL has not
 * language prefix on it, user will be redirected to a locale-prefixed version
 * of the requested URL.
 *
 * @return void
 */
	protected function _prepareLanguage() {
		$session = $this->request->session();
		$locales = array_keys(Configure::read('QuickApps.languages'));

		if ($session->check('user.locale') && in_array($session->read('user.locale'), $locales)) {
			Configure::write('Config.language', $session->read('user.locale'));
		} elseif (in_array(getOption('default_language'), $locales)) {
			Configure::write('Config.language', getOption('default_language'));
		} else {
			Configure::write('Config.language', 'en-us');
		}

		if (
			$this->request->url !== false &&
			getOption('url_locale_prefix') &&
			!str_starts_with($this->request->url, Configure::read('Config.language'))
		) {
			$url = $this->request->url;
			$localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';
			$url = preg_replace("/^{$localesPattern}\//", '', $url);
			$this->redirect('/' . Configure::read('Config.language') . '/' . $url);
		}
	}

/**
 * Sets the theme to use.
 * 
 * @return void
 */
	protected function _prepareTheme() {
		// TODO: change AppController::theme according to site settings.
		if (!empty($this->request->params['prefix']) && strtolower($this->request->params['prefix']) === 'admin') {
			$this->theme = 'BackendTheme';
		} else {
			$this->theme = 'FrontendTheme';
		}
	}

}
