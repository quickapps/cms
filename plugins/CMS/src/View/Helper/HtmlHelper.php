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
namespace CMS\View\Helper;

use Cake\View\Helper\HtmlHelper as CakeHtmlHelper;
use CMS\Event\EventDispatcherTrait;

/**
 * Html helper class for easy use of HTML widgets.
 *
 * Overwrites CakePHP's Html Helper and adds alter hooks to every method,
 * so plugins may alter html elements rendering cycle.
 */
class HtmlHelper extends CakeHtmlHelper
{

    use EventDispatcherTrait;

    /**
     * Renders default layout's header.
     *
     * This should be used within "<head>" & "</head>", provides a basic
     * head configuration for usage in theme's layouts.
     *
     * ### Usage:
     *
     * In your theme's layout (e.g. `default.ctp`) you have to use this method
     * between `<head>`  & `</head>` tags, for example:
     *
     *     <!DOCTYPE html>
     *     <html>
     *           <head>
     *             <?= $this->head(); ?>
     *             <!-- rest of your head code -->
     *          </head>
     *          <body>
     *              <!-- page content -->
     *          </body>
     *     </html>
     *
     * If you want to automatically include some Twitter Bootstrap's files
     * set `bootstrap` key in the $options array as follow:
     *
     * - (bool) false: Nothing will be automatically included.
     * - (bool) true: Will include Twitter Bootstrap's CSS & JS files.
     * - (string) "css": Include CSS files only. (By default)
     * - (string) "js": Include JS files only.
     * - (string) Both "css,js" or "js,css": Equivalent to bool true, will include
     *   both, JS and CSS files.
     *
     * #### Example:
     *
     *     // no CSS nor JS
     *     <?= $this->Html->head(['boostrap' => false]); ?>
     *
     *     // CSS files only (default)
     *     <?= $this->Html->head(['boostrap' => 'css']); ?>
     *
     *     // CSS & JS files
     *     <?= $this->Html->head(['boostrap' => true]); ?>
     *
     *     // JS files only
     *     <?= $this->Html->head(['boostrap' => 'js']); ?>
     *
     *     // CSS & JS files, it can be either "css,js" or "js,css"
     *     <?= $this->Html->head(['boostrap' => 'css,js']); ?>
     *
     * Other options available are:
     *
     * - `icon`: True to include favicon meta-tag. Defaults to true.
     * - `prepend`: Any additional HTML code you need to prepend to the result.
     * - `append`: Any additional HTML code you need to append to the result.
     *
     * @param mixed $options As described above
     * @return string HTML code
     */
    public function head($options = [])
    {
        $options += [
            'bootstrap' => 'css',
            'icon' => true,
            'prepend' => '',
            'append' => '',
        ];

        return $this->_View->element('layout_head', $options);
    }
}
