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

use Cake\Controller\Controller;
use QuickApps\Utility\AlertTrait;
use QuickApps\Utility\DetectorTrait;
use QuickApps\Utility\HookTrait;
use QuickApps\Utility\ViewModeTrait;

/**
 * Main controller class for organization of business logic.
 *
 * Provides basic QuickAppsCMS functionality, such as themes handling,
 * user authorization, and more.
 */
class AppController extends Controller {

	use AlertTrait;
	use DetectorTrait;
	use HookTrait;
	use ViewModeTrait;

/**
 * In use theme name.
 *
 * @var string
 */
	public $theme = 'BackBootstrap';

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
		// TODO: set default languages and other stuff
		// TODO: change AppController::theme according to site settings.
		if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] === 'admin') {
			$this->theme = 'BackBootstrap';
		} else {
			$this->theme = 'FrontBootstrap';
		}
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

}
