<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace QuickApps\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\UrlHelper as CakeUrlHelper;
use QuickApps\Event\HookAwareTrait;

/**
 * UrlHelper class for generating urls.
 */
class UrlHelper extends CakeUrlHelper
{

    use HookAwareTrait;
}
