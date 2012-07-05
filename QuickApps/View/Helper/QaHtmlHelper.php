<?php
/**
 * Html Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class QaHtmlHelper extends AppHelper {
/**
 * Other helpers used by QaHtmlHelper
 *
 * @var array
 * @access public
 */
	var $helpers = array('CoreHtml' => array('className' => 'Html'), 'Table');

/**
 * Constructor
 *
 * ### Settings
 *
 * - `configFile` A file containing an array of tags you wish to redefine.
 *
 * ### Customizing tag sets
 *
 * Using the `configFile` option you can redefine the tag HtmlHelper will use.
 * The file named should be compatible with HtmlHelper::loadConfig().
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		if (!empty($settings['configFile'])) {
			$this->CoreHtml->loadConfig($settings['configFile']);
		}
	}

/**
 * QuickApps implementation of TableHelper
 *
 * @var array
 * @access public
 */
	function table($data , $options) {
		$_data = compact('data', 'options');

		$this->hook('html_table_alter', $_data);
		extract($_data);

		return $this->Table->create($data, $options);
	}

/**
 * Adds a link to the breadcrumbs array.
 *
 * @param string $name Text for link
 * @param string $link URL for link (if empty it won't be a link)
 * @param mixed $options Link attributes e.g. array('id'=>'selected')
 * @return void
 * @see HtmlHelper::link() for details on $options that can be used.
 */
	public function addCrumb($name, $link = null, $options = null) {
		$data = compact('name', 'link', 'options');

		$this->hook('html_add_crumb_alter', $data);
		extract($data);

		return $this->CoreHtml->addCrumb($name, $link, $options);
	}

/**
 * Returns a doctype string.
 *
 * Possible doctypes:
 *
 *  - html4-strict: HTML4 Strict.
 *  - html4-trans: HTML4 Transitional.
 *  - html4-frame: HTML4 Frameset.
 *  - html5: HTML5.
 *  - xhtml-strict: XHTML1 Strict.
 *  - xhtml-trans: XHTML1 Transitional.
 *  - xhtml-frame: XHTML1 Frameset.
 *  - xhtml11: XHTML1.1.
 *
 * @param string $type Doctype to use.
 * @return string Doctype string
 * @access public
 * @link http://book.cakephp.org/view/1439/docType
 */
	public function docType($type = 'xhtml-strict') {
		$this->hook('html_doc_type_alter', $type);

		return $this->CoreHtml->docType($type);
	}

/**
 * Creates a link to an external resource and handles basic meta tags
 *
 * ### Options
 *
 * - `inline` Whether or not the link element should be output inline, or in scripts_for_layout.
 *
 * @param string $type The title of the external resource
 * @param mixed $url The address of the external resource or string for content attribute
 * @param array $options Other attributes for the generated tag. If the type attribute is html,
 *	rss, atom, or icon, the mime-type is returned.
 * @return string A completed `<link />` element.
 * @access public
 * @link http://book.cakephp.org/view/1438/meta
 */
	public function meta($type, $url = null, $options = array()) {
		$data = compact('type', 'url', 'options');

		$this->hook('html_meta_alter', $data);
		extract($data);

		return $this->CoreHtml->meta($type, $url, $options);
	}

/**
 * Returns a charset META-tag.
 *
 * @param string $charset The character set to be used in the meta tag. If empty,
 *  The App.encoding value will be used. Example: "utf-8".
 * @return string A meta tag containing the specified character set.
 * @access public
 * @link http://book.cakephp.org/view/1436/charset
 */
	public function charset($charset = null) {
		$this->hook('html_charset_alter', $charset);

		return $this->CoreHtml->charset($charset);
	}

/**
 * Creates an HTML link.
 *
 * If $url starts with "http://" this is treated as an external link. Else,
 * it is treated as a path to controller/action and parsed with the
 * HtmlHelper::url() method.
 *
 * If the $url is empty, $title is used instead.
 *
 * ### Options
 *
 * - `escape` Set to false to disable escaping of title and attributes.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param mixed $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 * @access public
 * @link http://book.cakephp.org/view/1442/link
 */
	public function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$data = compact('title', 'url', 'options', 'confirmMessage');

		$this->hook('html_link_alter', $data);
		extract($data);

		return $this->CoreHtml->link($title, $url, $options, $confirmMessage);
	}

/**
 * Creates a link element for CSS stylesheets.
 *
 * ### Options
 *
 * - `inline` If set to false, the generated tag appears in the head tag of the layout. Defaults to true
 *
 * @param mixed $path The name of a CSS style sheet or an array containing names of
 *   CSS stylesheets. If `$path` is prefixed with '/', the path will be relative to the webroot
 *   of your application. Otherwise, the path will be relative to your CSS path, usually webroot/css.
 * @param string $rel Rel attribute. Defaults to "stylesheet". If equal to 'import' the stylesheet will be imported.
 * @param array $options Array of HTML attributes.
 * @return string CSS <link /> or <style /> tag, depending on the type of link.
 * @access public
 * @link http://book.cakephp.org/view/1437/css
 */
	public function css($path, $rel = null, $options = array()) {
		$data = compact('path', 'rel', 'options');

		$this->hook('html_css_alter', $data);
		extract($data);

		return $this->CoreHtml->css($path, $rel, $options);
	}

/**
 * Returns one or many `<script>` tags depending on the number of scripts given.
 *
 * If the filename is prefixed with "/", the path will be relative to the base path of your
 * application.  Otherwise, the path will be relative to your JavaScript path, usually webroot/js.
 *
 * Can include one or many Javascript files.
 *
 * ### Options
 *
 * - `inline` - Whether script should be output inline or into scripts_for_layout.
 * - `once` - Whether or not the script should be checked for uniqueness. If true scripts will only be
 *   included once, use false to allow the same script to be included more than once per request.
 *
 * @param mixed $url String or array of javascript files to include
 * @param mixed $options Array of options, and html attributes see above. If boolean sets $options['inline'] = value
 * @return mixed String of `<script />` tags or null if $inline is false or if $once is true and the file has been
 *   included before.
 * @access public
 * @link http://book.cakephp.org/view/1589/script
 */
	public function script($url, $options = array()) {
		$data = compact('url', 'options');

		$this->hook('html_script_alter', $data);
		extract($data);

		return $this->CoreHtml->script($url, $options);
	}

/**
 * Wrap $script in a script tag.
 *
 * ### Options
 *
 * - `safe` (boolean) Whether or not the $script should be wrapped in <![CDATA[ ]]>
 * - `inline` (boolean) Whether or not the $script should be added to $scripts_for_layout or output inline
 *
 * @param string $script The script to wrap
 * @param array $options The options to use.
 * @return mixed string or null depending on the value of `$options['inline']`
 * @access public
 * @link http://book.cakephp.org/view/1604/scriptBlock
 */
	public function scriptBlock($script, $options = array()) {
		$data = compact('script', 'options');

		$this->hook('html_script_block_alter', $data);
		extract($data);

		return $this->CoreHtml->scriptBlock($script, $options);
	}

/**
 * Begin a script block that captures output until HtmlHelper::scriptEnd()
 * is called. This capturing block will capture all output between the methods
 * and create a scriptBlock from it.
 *
 * ### Options
 *
 * - `safe` Whether the code block should contain a CDATA
 * - `inline` Should the generated script tag be output inline or in `$scripts_for_layout`
 *
 * @param array $options Options for the code block.
 * @return void
 * @access public
 * @link http://book.cakephp.org/view/1605/scriptStart
 */
	public function scriptStart($options = array()) {
		$this->hook('html_script_start_alter', $options);

		return $this->CoreHtml->scriptStart($options);
	}

/**
 * End a Buffered section of Javascript capturing.
 * Generates a script tag inline or in `$scripts_for_layout` depending on the settings
 * used when the scriptBlock was started
 *
 * @return mixed depending on the settings of scriptStart() either a script tag or null
 * @access public
 * @link http://book.cakephp.org/view/1606/scriptEnd
 */
	public function scriptEnd() {
		$r = $this->CoreHtml->scriptEnd();

		$this->hook('html_script_end_alter', $r);

		return $r;
	}

/**
 * Builds CSS style data from an array of CSS properties
 *
 * ### Usage:
 *
 * {{{
 * echo $html->style(array('margin' => '10px', 'padding' => '10px'), true);
 *
 * // creates
 * 'margin:10px;padding:10px;'
 * }}}
 *
 * @param array $data Style data array, keys will be used as property names, values as property values.
 * @param boolean $oneline Whether or not the style block should be displayed on one line.
 * @return string CSS styling data
 * @access public
 * @link http://book.cakephp.org/view/1440/style
 */
	public function style($data, $oneline = true) {
		$data = compact('data', 'oneline');

		$this->hook('html_style_alter', $data);
		extract($data);

		return $this->CoreHtml->style($data, $oneline);
	}

/**
 * Returns the breadcrumb trail as a sequence of &raquo;-separated links.
 *
 * @param string $separator Text to separate crumbs.
 * @param string $startText This will be the first crumb, if false it defaults to first crumb in array
 * @return string Composed bread crumbs
 */
	public function getCrumbs($separator = '&raquo;', $startText = false) {
		$data = compact('separator', 'startText');

		$this->hook('html_get_crumbs_alter', $data);
		extract($data);

		return $this->CoreHtml->getCrumbs($separator, $startText);
	}

/**
 * Returns breadcrumbs as a (x)html list
 *
 * This method uses HtmlHelper::tag() to generate list and its elements. Works
 * similiary to HtmlHelper::getCrumbs(), so it uses options which every
 * crumb was added with.
 *
 * @param array $options Array of html attributes to apply to the generated list elements.
 * @return string breadcrumbs html list
 * @access public
 */
	function getCrumbList($options = array()) {
		$this->hook('html_get_crumb_list_alter', $options);

		return $this->CoreHtml->getCrumbList($options);
	}

/**
 * Creates a formatted IMG element. If `$options['url']` is provided, an image link will be
 * generated with the link pointed at `$options['url']`.  This method will set an empty
 * alt attribute if one is not supplied.
 *
 * ### Usage
 *
 * Create a regular image:
 *
 * `echo $html->image('cake_icon.png', array('alt' => 'CakePHP'));`
 *
 * Create an image link:
 *
 * `echo $html->image('cake_icon.png', array('alt' => 'CakePHP', 'url' => 'http://cakephp.org'));`
 *
 * @param string $path Path to the image file, relative to the app/webroot/img/ directory.
 * @param array $options Array of HTML attributes.
 * @return string completed img tag
 * @access public
 * @link http://book.cakephp.org/view/1441/image
 */
	public function image($path, $options = array()) {
		$data = compact('path', 'options');

		$this->hook('html_image_alter', $data);
		extract($data);

		return $this->CoreHtml->image($path, $options);
	}

/**
 * Returns a row of formatted and named TABLE headers.
 *
 * @param array $names Array of tablenames. Each tablename also can be a key that points to an array with a set
 *     of attributes to its specific tag
 * @param array $trOptions HTML options for TR elements.
 * @param array $thOptions HTML options for TH elements.
 * @return string Completed table headers
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::tableHeaders
 */
	public function tableHeaders($names, $trOptions = null, $thOptions = null) {
		$data = compact('names', 'trOptions', 'thOptions');

		$this->hook('html_table_headers_alter', $data);
		extract($data);

		return $this->CoreHtml->tableHeaders($names, $trOptions, $thOptions);
	}

/**
 * Returns a formatted string of table rows (TR's with TD's in them).
 *
 * @param array $data Array of table data
 * @param array $oddTrOptions HTML options for odd TR elements if true useCount is used
 * @param array $evenTrOptions HTML options for even TR elements
 * @param bool $useCount adds class "column-$i"
 * @param bool $continueOddEven If false, will use a non-static $count variable,
 *	so that the odd/even count is reset to zero just for that call.
 * @return string Formatted HTML
 * @access public
 * @link http://book.cakephp.org/view/1447/tableCells
 */
	public function tableCells($data, $oddTrOptions = null, $evenTrOptions = null, $useCount = false, $continueOddEven = true) {
		$data = compact('data', 'oddTrOptions', 'evenTrOptions', 'useCount', 'continueOddEven');

		$this->hook('html_table_cells_alter', $data);
		extract($data);

		return $this->CoreHtml->tableCells($data, $oddTrOptions, $evenTrOptions, $useCount, $continueOddEven);
	}

/**
 * Returns a formatted block tag, i.e DIV, SPAN, P.
 *
 * ### Options
 *
 * - `escape` Whether or not the contents should be html_entity escaped.
 *
 * @param string $name Tag name.
 * @param string $text String content that will appear inside the div element.
 *   If null, only a start tag will be printed
 * @param array $options Additional HTML attributes of the DIV tag, see above.
 * @return string The formatted tag element
 * @access public
 * @link http://book.cakephp.org/view/1443/tag
 */
	public function tag($name, $text = null, $options = array()) {
		$data = compact('name', 'text', 'options');

		$this->hook('html_tag_alter', $data);
		extract($data);

		return $this->CoreHtml->tag($name, $text, $options);
	}

/**
 * Returns a formatted existent block of $tags
 *
 * @param string $tag Tag name
 * @return string Formatted block
 */
	public function useTag($tag) {
		$args = func_get_args();

		array_shift($args);

		$data = compact('tag', 'args');

		$this->hook('html_useTag_alter', $data);
		extract($data);

		foreach ($args as &$arg) {
			if (is_array($arg)) {
				$arg = $this->CoreHtml->_parseAttributes($arg, null, ' ', '');
			}
		}

		$before = $this->hook('html_before_use_tag', $data, array('collectReturn' => true));
		$after = $this->hook('html_after_use_tag', $data, array('collectReturn' => true));

		return implode(' ', (array)$before) . vsprintf($this->CoreHtml->_tags[$tag], $args) . implode(' ', (array)$after);
	}

/**
 * Returns a formatted DIV tag for HTML FORMs.
 *
 * ### Options
 *
 * - `escape` Whether or not the contents should be html_entity escaped.
 *
 * @param string $class CSS class name of the div element.
 * @param string $text String content that will appear inside the div element.
 *   If null, only a start tag will be printed
 * @param array $options Additional HTML attributes of the DIV tag
 * @return string The formatted DIV element
 * @access public
 * @link http://book.cakephp.org/view/1444/div
 */
	public function div($class = null, $text = null, $options = array()) {
		$data = compact('class', 'text', 'options');

		$this->hook('html_div_alter', $data);
		extract($data);

		return $this->CoreHtml->div($class, $text, $options);
	}

/**
 * Returns a formatted P tag.
 *
 * ### Options
 *
 * - `escape` Whether or not the contents should be html_entity escaped.
 *
 * @param string $class CSS class name of the p element.
 * @param string $text String content that will appear inside the p element.
 * @param array $options Additional HTML attributes of the P tag
 * @return string The formatted P element
 * @access public
 * @link http://book.cakephp.org/view/1445/para
 */
	public function para($class, $text, $options = array()) {
		$data = compact('class', 'text', 'options');

		$this->hook('html_para_alter', $data);
		extract($data);

		return $this->CoreHtml->para($class, $text, $options);
	}

/**
 * Returns an audio/video element
 *
 * ### Usage
 *
 * Using an audio file:
 *
 * `echo $this->Html->media('audio.mp3', array('fullBase' => true));`
 *
 * Outputs:
 *
 * `<video src="http://www.somehost.com/files/audio.mp3">Fallback text</video>`
 *
 * Using a video file:
 *
 * `echo $this->Html->media('video.mp4', array('text' => 'Fallback text'));`
 *
 * Outputs:
 *
 * `<video src="/files/video.mp4">Fallback text</video>`
 *
 * Using multiple video files:
 *
 * {{{
 * echo $this->Html->media(
 * 		array('video.mp4', array('src' => 'video.ogv', 'type' => "video/ogg; codecs='theora, vorbis'")),
 * 		array('tag' => 'video', 'autoplay')
 * );
 * }}}
 *
 * Outputs:
 *
 * {{{
 * <video autoplay="autoplay">
 * 		<source src="/files/video.mp4" type="video/mp4"/>
 * 		<source src="/files/video.ogv" type="video/ogv; codecs='theora, vorbis'"/>
 * </video>
 * }}}
 *
 * ### Options
 *
 * - `tag` Type of media element to generate, either "audio" or "video".
 * 	If tag is not provided it's guessed based on file's mime type.
 * - `text` Text to include inside the audio/video tag
 * - `pathPrefix` Path prefix to use for relative urls, defaults to 'files/'
 * - `fullBase` If provided the src attribute will get a full address including domain name
 *
 * @param string|array $path Path to the video file, relative to the webroot/{$options['pathPrefix']} directory.
 *  Or an array where each item itself can be a path string or an associate array containing keys `src` and `type`
 * @param array $options Array of HTML attributes, and special options above.
 * @return string Generated media element
 */
	public function media($path, $options = array()) {
		$data = compact('path', 'options');

		$this->hook('html_media_alter', $data);
		extract($data);

		return $this->CoreHtml->media($path, $options);
	}

/**
 * Build a nested list (UL/OL) out of an associative array.
 *
 * @param array $list Set of elements to list
 * @param array $options Additional HTML attributes of the list (ol/ul) tag or if ul/ol use that as tag
 * @param array $itemOptions Additional HTML attributes of the list item (LI) tag
 * @param string $tag Type of list tag to use (ol/ul)
 * @return string The nested list
 */
	public function nestedList($list, $options = array(), $itemOptions = array(), $tag = 'ul') {
		$data = compact('list', 'options', 'itemOptions', 'tag');

		$this->hook('html_nested_list_alter', $data);
		extract($data);

		return $this->CoreHtml->nestedList($list, $options, $itemOptions, $tag);
	}

/**
 * Load Html configs
 *
 * @param mixed $configFile String with the config file (load using PhpReader) or an array with file and reader name
 * @param string $path Path with config file
 * @return mixed False to error or loaded configs
 */
	public function loadConfig($configFile, $path = CONFIGS) {
		$data = compact('configFile', 'path');

		$this->hook('html_load_config_alter', $data);
		extract($data);

		return $this->CoreHtml->loadConfig($configFile, $path);
	}

/**
 * Returns a space-delimited string with items of the $options array. If a
 * key of $options array happens to be one of:
 *
 * - 'compact'
 * - 'checked'
 * - 'declare'
 * - 'readonly'
 * - 'disabled'
 * - 'selected'
 * - 'defer'
 * - 'ismap'
 * - 'nohref'
 * - 'noshade'
 * - 'nowrap'
 * - 'multiple'
 * - 'noresize'
 *
 * And its value is one of:
 *
 * - '1' (string)
 * - 1 (integer)
 * - true (boolean)
 * - 'true' (string)
 *
 * Then the value will be reset to be identical with key's name.
 * If the value is not one of these 3, the parameter is not output.
 *
 * 'escape' is a special option in that it controls the conversion of
 *  attributes to their html-entity encoded equivalents.  Set to false to disable html-encoding.
 *
 * If value for any option key is set to `null` or `false`, that option will be excluded from output.
 *
 * @param array $options Array of options.
 * @param array $exclude Array of options to be excluded, the options here will not be part of the return.
 * @param string $insertBefore String to be inserted before options.
 * @param string $insertAfter String to be inserted after options.
 * @return string Composed attributes.
 */
	public function _parseAttributes($options, $exclude = null, $insertBefore = ' ', $insertAfter = null) {
		return $this->CoreHtml->_parseAttributes($options, $exclude, $insertBefore, $insertAfter);
	}
}