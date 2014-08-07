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
	);

/**
 * Format package name to CamelCase.
 *
 * For example, "user-manager-plugin" becomes "UserManagerPlugin",
 *
 * @param array $vars
 * @return array Modified $vars
 */
	public function inflectPackageVars($vars) {
		$vars['name'] = str_replace(' ', '', 
			ucwords(
				strtolower(
					str_replace(array('-', '_'), ' ', $vars['name'])
				)
			)
		);

		return $vars;
	}

}
