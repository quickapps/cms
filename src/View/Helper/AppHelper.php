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
namespace QuickApps\View\Helper;

use Cake\View\Helper;
use Quickapps\Utility\HookTrait;
use Quickapps\Utility\CacheTrait;

/**
 * Application Helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 */
class AppHelper extends Helper {

	use HookTrait;
	use CacheTrait;

}
