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
namespace QuickApps\View\Helper;

use Cake\View\Helper\HtmlHelper as CakeHtmlHelper;
use QuickApps\Event\HookAwareTrait;

/**
 * Html helper class for easy use of HTML widgets.
 *
 * Overwrites CakePHP's Html Helper and adds alter hooks to every method,
 * so plugins may alter html elements rendering cycle.
 */
class HtmlHelper extends CakeHtmlHelper
{

    use HookAwareTrait;

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
     *             <?php echo $this->head(); ?>
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
     * - (string) combination of "css" and "js": Equivalent to bool true. will
     *   include both, JS and CSS files.
     *
     * #### Example:
     *
     *     // no CSS nor JS
     *     <?php echo $this->Html->head(['boostrap' => false]); ?>
     *
     *     // CSS files only (default)
     *     <?php echo $this->Html->head(['boostrap' => 'css']); ?>
     *
     *     // CSS & JS files
     *     <?php echo $this->Html->head(['boostrap' => true]); ?>
     *
     *     // JS files only
     *     <?php echo $this->Html->head(['boostrap' => 'js']); ?>
     *
     *     // CSS & JS files, it can be either "css,js" or "js,css"
     *     <?php echo $this->Html->head(['boostrap' => 'css,js']); ?>
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

    /**
     * {@inheritDoc}
     */
    public function addCrumb($name, $link = null, array $options = [])
    {
        $this->alter(['HtmlHelper.addCrumb', $this->_View], $name, $link, $options);
        return parent::addCrumb($name, $link, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function docType($type = 'html5')
    {
        $this->alter(['HtmlHelper.docType', $this->_View], $type);
        return parent::docType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function meta($type, $content = null, array $options = [])
    {
        $this->alter(['HtmlHelper.meta', $this->_View], $type, $url, $options);
        return parent::meta($type, $url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function charset($charset = null)
    {
        $this->alter(['HtmlHelper.charset', $this->_View], $charset);
        return parent::charset($charset);
    }

    /**
     * {@inheritDoc}
     */
    public function link($title, $url = null, array $options = [], $confirmMessage = false)
    {
        $this->alter(['HtmlHelper.link', $this->_View], $title, $url, $options, $confirmMessage);
        return parent::link($title, $url, $options, $confirmMessage);
    }

    /**
     * {@inheritDoc}
     */
    public function css($path, array $options = array())
    {
        $this->alter(['HtmlHelper.css', $this->_View], $path, $options);
        return parent::css($path, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function script($url, array $options = array())
    {
        $this->alter(['HtmlHelper.script', $this->_View], $url, $options);
        return parent::script($url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function scriptBlock($script, array $options = array())
    {
        $this->alter(['HtmlHelper.scriptBlock', $this->_View], $script, $options);
        return parent::scriptBlock($script, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function scriptStart(array $options = array())
    {
        $this->alter(['HtmlHelper.scriptStart', $this->_View], $options);
        return parent::scriptStart($options);
    }

    /**
     * {@inheritDoc}
     */
    public function scriptEnd()
    {
        $this->alter(['HtmlHelper.scriptEnd', $this->_View]);
        return parent::scriptEnd();
    }

    /**
     * {@inheritDoc}
     */
    public function style(array $data, $oneline = true)
    {
        $this->alter(['HtmlHelper.style', $this->_View], $data, $oneline);
        return parent::style($data, $oneline);
    }

    /**
     * {@inheritDoc}
     */
    public function getCrumbs($separator = '&raquo;', $startText = false)
    {
        $this->alter(['HtmlHelper.getCrumbs', $this->_View], $separator, $startText);
        return parent::getCrumbs($separator, $startText);
    }

    /**
     * {@inheritDoc}
     */
    public function getCrumbList(array $options = [], $startText = false)
    {
        $this->alter(['HtmlHelper.getCrumbList', $this->_View], $options, $startText);
        return parent::getCrumbList($options, $startText);
    }

    /**
     * {@inheritDoc}
     */
    public function image($path, array $options = array())
    {
        $this->alter(['HtmlHelper.image', $this->_View], $path, $options);
        return parent::image($path, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function tableHeaders(array $names, array $trOptions = null, array $thOptions = null)
    {
        $this->alter(['HtmlHelper.tableHeaders', $this->_View], $names, $trOptions, $thOptions);
        return parent::tableHeaders($names, $trOptions, $thOptions);
    }

    /**
     * {@inheritDoc}
     */
    public function tableCells($data, $oddTrOptions = null, $evenTrOptions = null, $useCount = false, $continueOddEven = true)
    {
        $this->alter(['HtmlHelper.tableCells', $this->_View], $data, $oddTrOptions, $evenTrOptions, $useCount, $continueOddEven);
        return parent::tableCells($data, $oddTrOptions, $evenTrOptions, $useCount, $continueOddEven);
    }

    /**
     * {@inheritDoc}
     */
    public function tag($name, $text = null, array $options = [])
    {
        $this->alter(['HtmlHelper.tableHeaders', $this->_View], $name, $text, $options);
        return parent::tag($name, $text, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function div($class = null, $text = null, array $options = [])
    {
        $this->alter(['HtmlHelper.div', $this->_View], $class, $text, $options);
        return parent::div($class, $text, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function para($class, $text, array $options = [])
    {
        $this->alter(['HtmlHelper.para', $this->_View], $class, $text, $option);
        return parent::para($class, $text, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function media($path, array $options = [])
    {
        $this->alter(['HtmlHelper.meta', $this->_View], $path, $options);
        return parent::media($path, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function nestedList(array $list, array $options = [], array $itemOptions = [])
    {
        $this->alter(['HtmlHelper.nestedList', $this->_View], $list, $options, $itemOptions);
        return parent::nestedList($list, $options, $itemOptions);
    }
}
