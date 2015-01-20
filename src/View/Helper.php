<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\View;

use Cake\View\Helper as CakeHelper;
use Quickapps\Core\StaticCacheTrait;
use QuickApps\Event\HookAwareTrait;

/**
 * Application Helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 */
class Helper extends CakeHelper
{

    use HookAwareTrait;
    use StaticCacheTrait;
}
