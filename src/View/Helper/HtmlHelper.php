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

use Cake\View\Helper\HtmlHelper as CakeHtmlHelper;
use QuickApps\Utility\HookTrait;

/**
 * Html Helper class for easy use of HTML widgets.
 *
 */
class HtmlHelper extends CakeHtmlHelper {

	use HookTrait;

/**
 * {@inheritdoc}
 *
 * @param string $name Text for link
 * @param string $link URL for link (if empty it won't be a link)
 * @param string|array $options Link attributes e.g. array('id' => 'selected')
 * @return void
 */
	public function addCrumb($name, $link = null, $options = null) {
		$event = $this->hook('HtmlHelper.addCrumb', $name, $link, $options);

		return parent::addCrumb($name, $link, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $type Doctype to use.
 * @return string Doctype string
 */
	public function docType($type = 'html5') {
		$event = $this->hook('HtmlHelper.docType', $type);

		return parent::docType($type);
	}

/**
 * {@inheritdoc}
 *
 * @param string $type The title of the external resource
 * @param string|array $url The address of the external resource or string for content attribute
 * @param array $options Other attributes for the generated tag. If the type attribute is html,
 *    rss, atom, or icon, the mime-type is returned.
 * @return string A completed `<link />` element.
 */
	public function meta($type, $url = null, $options = array()) {
		$event = $this->hook('HtmlHelper.meta', $type, $url, $options);

		return parent::meta($type, $url, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $charset The character set to be used in the meta tag. If empty,
 *  The App.encoding value will be used. Example: "utf-8".
 * @return string A meta tag containing the specified character set.
 */
	public function charset($charset = null) {
		$event = $this->hook('HtmlHelper.charset', $charset);

		return parent::charset($charset);
	}

/**
 * {@inheritdoc}
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of options and HTML attributes.
 * @param string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 */
	public function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$event = $this->hook('HtmlHelper.link', $title, $url, $options, $confirmMessage);

		return parent::link($title, $url, $options, $confirmMessage);
	}

/**
 * {@inheritdoc}
 *
 * @param string|array $path The name of a CSS style sheet or an array containing names of
 *   CSS stylesheets. If `$path` is prefixed with '/', the path will be relative to the webroot
 *   of your application. Otherwise, the path will be relative to your CSS path, usually webroot/css.
 * @param array $options Array of options and HTML arguments.
 * @return string CSS <link /> or <style /> tag, depending on the type of link.
 */
	public function css($path, $options = array()) {
		$event = $this->hook('HtmlHelper.css', $path, $options);

		return parent::css($path, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string|array $url String or array of javascript files to include
 * @param array $options Array of options, and html attributes see above.
 * @return mixed String of `<script />` tags or null if block is specified in options
 *   or if $once is true and the file has been included before.
 */
	public function script($url, $options = array()) {
		$event = $this->hook('HtmlHelper.script', $url, $options);

		return parent::script($url, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $script The script to wrap
 * @param array $options The options to use. Options not listed above will be
 *    treated as HTML attributes.
 * @return mixed string or null depending on the value of `$options['block']`
 */
	public function scriptBlock($script, $options = array()) {
		$event = $this->hook('HtmlHelper.scriptBlock', $script, $options);

		return parent::scriptBlock($script, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param array $options Options for the code block.
 * @return void
 */
	public function scriptStart($options = array()) {
		$event = $this->hook('HtmlHelper.scriptStart', $options);

		return parent::scriptStart($options);
	}

/**
 * {@inheritdoc}
 *
 * @return mixed depending on the settings of scriptStart() either a script tag or null
 */
	public function scriptEnd() {
		$event = $this->hook('HtmlHelper.scriptEnd');

		return parent::scriptEnd();
	}

/**
 * {@inheritdoc}
 *
 * @param array $data Style data array, keys will be used as property names, values as property values.
 * @param boolean $oneline Whether or not the style block should be displayed on one line.
 * @return string CSS styling data
 */
	public function style($data, $oneline = true) {
		$event = $this->hook('HtmlHelper.style', $data, $oneline);

		return parent::style($data, $oneline);
	}

/**
 * {@inheritdoc}
 *
 * @param string $separator Text to separate crumbs.
 * @param string|array|boolean $startText This will be the first crumb, if false it defaults to first crumb in array. Can
 *   also be an array, see above for details.
 * @return string Composed bread crumbs
 */
	public function getCrumbs($separator = '&raquo;', $startText = false) {
		$event = $this->hook('HtmlHelper.getCrumbs', $separator, $startText);

		return parent::getCrumbs($separator, $startText);
	}

/**
 * {@inheritdoc}
 *
 * @param array $options Array of html attributes to apply to the generated list elements.
 * @param string|array|boolean $startText This will be the first crumb, if false it defaults to first crumb in array. Can
 *   also be an array, see `HtmlHelper::getCrumbs` for details.
 * @return string breadcrumbs html list
 */
	public function getCrumbList($options = array(), $startText = false) {
		$event = $this->hook('HtmlHelper.getCrumbList', $options, $startText);

		return parent::getCrumbList($options, $startText);
	}

/**
 * {@inheritdoc}
 *
 * @param string $path Path to the image file, relative to the app/webroot/img/ directory.
 * @param array $options Array of HTML attributes. See above for special options.
 * @return string completed img tag
 */
	public function image($path, $options = array()) {
		$event = $this->hook('HtmlHelper.image', $path, $options);

		return parent::image($path, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param array $names Array of tablenames. Each tablename also can be a key that points to an array with a set
 *     of attributes to its specific tag
 * @param array $trOptions HTML options for TR elements.
 * @param array $thOptions HTML options for TH elements.
 * @return string Completed table headers
 */
	public function tableHeaders($names, $trOptions = null, $thOptions = null) {
		$event = $this->hook('HtmlHelper.tableHeaders', $names, $trOptions, $thOptions);

		return parent::tableHeaders($names, $trOptions, $thOptions);
	}

/**
 * {@inheritdoc}
 *
 * @param array $data Array of table data
 * @param array $oddTrOptions HTML options for odd TR elements if true useCount is used
 * @param array $evenTrOptions HTML options for even TR elements
 * @param boolean $useCount adds class "column-$i"
 * @param boolean $continueOddEven If false, will use a non-static $count variable,
 *    so that the odd/even count is reset to zero just for that call.
 * @return string Formatted HTML
 */
	public function tableCells($data, $oddTrOptions = null, $evenTrOptions = null, $useCount = false, $continueOddEven = true) {
		$event = $this->hook('HtmlHelper.tableCells', $data, $oddTrOptions, $evenTrOptions, $useCount, $continueOddEven);

		return parent::tableCells($data, $oddTrOptions, $evenTrOptions, $useCount, $continueOddEven);
	}

/**
 * {@inheritdoc}
 *
 * @param string $name Tag name.
 * @param string $text String content that will appear inside the div element.
 *   If null, only a start tag will be printed
 * @param array $options Additional HTML attributes of the DIV tag, see above.
 * @return string The formatted tag element
 */
	public function tag($name, $text = null, $options = array()) {
		$event = $this->hook('HtmlHelper.tableHeaders', $name, $text, $options);

		return parent::tag($name, $text, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $class CSS class name of the div element.
 * @param string $text String content that will appear inside the div element.
 *   If null, only a start tag will be printed
 * @param array $options Additional HTML attributes of the DIV tag
 * @return string The formatted DIV element
 */
	public function div($class = null, $text = null, $options = array()) {
		$event = $this->hook('HtmlHelper.div', $class, $text, $options);

		return parent::div($class, $text, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $class CSS class name of the p element.
 * @param string $text String content that will appear inside the p element.
 * @param array $options Additional HTML attributes of the P tag
 * @return string The formatted P element
 */
	public function para($class, $text, $options = array()) {
		$event = $this->hook('HtmlHelper.para', $class, $text, $option);

		return parent::para($class, $text, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string|array $path Path to the video file, relative to the webroot/{$options['pathPrefix']} directory.
 *  Or an array where each item itself can be a path string or an associate array containing keys `src` and `type`
 * @param array $options Array of HTML attributes, and special options above.
 * @return string Generated media element
 */
	public function media($path, $options = array()) {
		$event = $this->hook('HtmlHelper.meta', $path, $options);

		return parent::media($path, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param array $list Set of elements to list
 * @param array $options Additional HTML attributes of the list (ol/ul) tag or if ul/ol use that as tag
 * @param array $itemOptions Additional HTML attributes of the list item (LI) tag
 * @param string $tag Type of list tag to use (ol/ul)
 * @return string The nested list
 */
	public function nestedList($list, $options = array(), $itemOptions = array(), $tag = 'ul') {
		$event = $this->hook('HtmlHelper.nestedList', $list, $options, $itemOptions, $tag);

		return parent::nestedList($list, $options, $itemOptions, $tag);
	}

}
