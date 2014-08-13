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
namespace System\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;

/**
 * Attaches a few request-detectors to every controller's request object.
 *
 * The following built-in detectors returns TRUE:
 *
 * - `homePage`: When the front of the site is displayed.
 * - `adminPage`: When the dashboard or administration section is being displayed.
 * - `localized`: When current request's URL is language-prefixed. e.g. "/es/..."
 * - `isUserLoggedIn`: When user has logged in.
 * - `isUserAdmin`: When user has logged in and belongs to the "Administrator" group.
 *
 * ### Usage:
 *
 * In any controller you should use `Request::is()` method as follow:
 *
 *    $this->request->is('homePage');
 */
class DetectorComponent extends Component {

/**
 * The controller this component is attached to.
 *
 * @var \Cake\Controller\Controller
 */
	protected $_controller;

/**
 * Called before the controller's beforeFilter method.
 *
 * @param Event $event
 * @return void
 */
	public function initialize(Event $event) {
		$this->_controller = $event->subject;
		$this->_controller->request->addDetector('homePage', [$this, 'homePage']);
		$this->_controller->request->addDetector('adminPage', [$this, 'adminPage']);
		$this->_controller->request->addDetector('localized', [$this, 'localized']);
		$this->_controller->request->addDetector('userLoggedIn', [$this, 'userLoggedIn']);
		$this->_controller->request->addDetector('userAdmin', [$this, 'userAdmin']);
	}

/**
 * Checks if page being rendered is site's front page.
 *
 * @return bool
 */
	public function homePage($request) {
		return (
			!empty($request->params['plugin']) &&
			strtolower($request->params['plugin']) === 'node' &&
			!empty($request->params['controller']) &&
			strtolower($request->params['controller']) === 'serve' &&
			!empty($request->params['action']) &&
			strtolower($request->params['action']) === 'front_page'
		);
	}

/**
 * Checks if page being rendered is the dashboard or administration section.
 *
 * @return bool
 */
	public function adminPage($request) {
		return (
			!empty($request->params['prefix']) &&
			$request->params['prefix'] === 'admin'
		);
	}

/**
 * Checks if current URL is language prefixed.
 *
 * @return bool
 */
	public function localized($request) {
		$locales = array_keys(quickapps('languages'));
		$localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';
		$url = str_starts_with($request->url, '/') ? str_replace_once('/', '', $request->url) : $request->url;
		return preg_match("/^{$localesPattern}\//", $url);
	}

/**
 * Checks if visitor user is logged in.
 *
 * @return bool True if logged in. False otherwise
 */
	public function userLoggedIn($request) {
		return (
			$this->_controller->Session->check('user.id') &&
			!empty($this->_controller->Session->check('user.id'))
		);
	}

/**
 * Checks if visitor user is logged in and has administrator privileges.
 *
 * @return bool True if administrator. False otherwise
 */
	public function userAdmin($request) {
		return in_array(ROLE_ID_ADMINISTRATOR, user()->roles);
	}

}
