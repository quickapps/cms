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
namespace QuickApps\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper as CakePaginatorHelper;
use QuickApps\Event\EventDispatcherTrait;

/**
 * Paginator helper library.
 *
 */
class PaginatorHelper extends CakePaginatorHelper
{

    use EventDispatcherTrait;
}
