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
namespace Composer\Installers;

/**
 * Composer installer.
 *
 * Handles installation process for plugins and themes.
 */
class QuickAppsInstaller extends BaseInstaller {

/**
 * Paths to themes and plugins.
 *
 * @var array
 */
	protected $locations = array(
		'plugin' => 'Plugin/{$name}/',
		'theme' => 'Theme/Themed/{$name}/',
	);

/**
 * Format package name to CamelCase.
 *
 * @param array $vars
 * @return array Modified $vars
 */
	public function inflectPackageVars($vars) {
		$vars['name'] = strtolower(str_replace(array('-', '_'), ' ', $vars['name']));
		$vars['name'] = str_replace(' ', '', ucwords($vars['name']));

		return $vars;
	}

}
