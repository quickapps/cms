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
namespace System\Controller\Admin;

use Cake\Core\App;
use Cake\Error;
use QuickApps\Utility\Plugin;
use System\Controller\SystemAppController;

/**
 * Help Controller.
 *
 * For handling plugin's help documents.
 */
class HelpController extends SystemAppController {

/**
 * Main action.
 *
 * Here is where we render all available documents.
 * Plugins are able to define their own `help document` just
 * by creating an view-element named `help.ctp`.
 *
 * Example:
 *
 * Album plugin may create its own `help document` by creating this file:
 *
 *     Album/Template/Element/help.ctp
 *
 * Optionally, plugins are able to define translated versions of
 * help document. They have to create a view-element as `help_[code].ctp`,
 * where `[code]` is a three-character locale code conform to the ISO 639-2 standard.
 * For example:
 *
 *     help_eng.ctp
 *     help_spa.ctp
 *     help_fre.ctp
 *
 * @return void
 */
	public function index() {
		$plugins = [];

		foreach (App::objects('Plugin') as $plugin) {
			if (Plugin::loaded($plugin)) {
				$helpElement = App::path('Template', $plugin)[0] . 'Element' . DS . 'help.ctp';

				if (file_exists($helpElement)) {
					$plugins[] = $plugin;
				}
			}
		}

		$this->set('plugins', $plugins);
	}

/**
 * Renders the help document of the given plugin.
 *
 * @param string $pluginName The plugin name
 * @return void
 * @throws \Cake\Error\NotFoundException When no help document was found
 */
	public function about($pluginName) {
		$locale = \Cake\Core\Configure::read('Config.lnaguage');
		$templatePath = App::path('Template', $pluginName)[0] . 'Element' . DS;
		$about = false;
		$lookFor = ["help_{$locale}", 'help'];

		foreach ($lookFor as $name) {
			$about = file_exists($templatePath . "{$name}.ctp") ? "{$pluginName}.{$name}" : false;
		}

		if ($about) {
			$this->set('about', $about);
		} else {
			throw new Error\NotFoundException(__('No help was found.'));
		}
	}

}
