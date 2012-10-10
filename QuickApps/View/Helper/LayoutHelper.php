<?php
/**
 * Layout Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class LayoutHelper extends AppHelper {
/**
 * Used by some methods to cache data in order to improve
 * comunication between them, for example see LayoutHelper::blocksInRegion().
 *
 * @var array
 */
	protected $_tmp = array();

/**
 * Render CSS files links.
 *
 * @param array $stylesheets Asociative array of extra css elements to merge
 *
 *     array(
 *         'inline' => array("css code1", "css code2", ...)
 *         'print' => array("file1", "file2", ...),
 *         'all' => array("file3", "file4", ...),
 *         ....
 *     );
 *
 * @return string HTML css link-tags and inline-styles
 * @see AppController::$Layout
 */
	public function stylesheets($stylesheets = array()) {
		$output = $inline = $import = '';
		$stylesheets = Hash::merge($this->_View->viewVars['Layout']['stylesheets'], $stylesheets);
		$themePath = App::themePath(Configure::read('Theme.info.folder'));

		// pass css list array to modules
		$this->hook('stylesheets_alter', $stylesheets);

		foreach ($stylesheets as $media => $files) {
			foreach ($files as $file) {
				if ($media == 'inline') {
					$inline .= "{$file}\n\n";
				} elseif ($media == 'import') {
					$c = Router::url($file, true);
				} else {
					$c = $this->_View->Html->css($file, 'stylesheet', array('media' => $media));
				}

				if ($media != 'inline') {
					if (preg_match('/\/theme\/' . Configure::read('Theme.info.folder') . '\/css\/(.*).css/', $c, $matches)) {
						if ($matches[1] &&
							file_exists(TMP . 'cache' . DS . 'persistent' . DS . Inflector::underscore('cake_theme_' . Configure::read('Theme.info.folder') . '_' . $matches[1] . '_css'))
						) {
							$c = preg_replace('/\/theme\/' . Configure::read('Theme.info.folder') . '\/css\/(.*).css/', '/theme/' . Configure::read('Theme.info.folder') . '/custom_css/\1.css', $c);
						}
					}
				}

				if ($media == 'import') {
					$import .= '@import url("' . $c . '");' . "\n";
				} elseif ($media != 'inline') {
					$output .= "\n". $c;
				}
			}
		}

		if (!empty($import)) {
			$output .= "\n<style type=\"text/css\" media=\"all\">\n{$import}</style>\n";
		}

		if (!empty($inline)) {
			$output .= "\n<style type=\"text/css\"><!--\t\n {$inline} \n--></style>\n";
		}

		return $output;
	}

/**
 * Insert a CSS file in the stylesheets list to be included
 * on layout header using Layout::stylesheets().
 *
 * This method will NOT work if used on Themes layouts.
 * Use in Views ONLY.
 *
 * @param string $path URL to the css file
 * @param string $media Media type
 * @return void
 * @see AppController::$Layout
 */
	public function css($path, $media = 'all') {
		if (!in_array($path, $this->_View->viewVars['Layout']['stylesheets'][$media])) {
			$this->_View->viewVars['Layout']['stylesheets'][$media][] = $path;
		}
	}

/**
 * Render js files links.
 *
 * ### Usage
 *
 *     array(
 *         'inline' => array("code1", "code2", ...),
 *         'file' => array("path_to_file1", "path_to_file2", ...)
 *     );
 *
 * @param array $javascripts Asociative array of extra js elements to merge:
 * @return string HTML javascript link-tags and inline-code
 * @see AppController::$Layout
 */
	public function javascripts($javascripts = array()) {
		$output = '';
		$javascripts = Hash::merge($this->_View->viewVars['Layout']['javascripts'], $javascripts);

		// pass javascripts list to modules if they need to alter them
		$this->hook('javascripts_alter', $javascripts);

		// js files
		$javascripts['file'] = array_unique($javascripts['file']);

		foreach ($javascripts['file'] as $file) {
			$output .= "\n" . $this->_View->Html->script($file);
		}

		// js inline code
		$inline = "\n";
		$javascripts['inline'] = array_unique($javascripts['inline']);

		foreach ($javascripts['inline'] as $block) {
			$inline .= "{$block}\n\n";
		}

		if ($buffer = $this->_View->Js->writeBuffer(array('safe' => false))) {
			$buffer = preg_replace(
			array(
				'/<script type="text\/javascript".*?>/',
				'/<\/script>/',
			), '', $buffer);
			$inline .= "{$buffer}\n\n";
		}

		$output .= "\n" . $this->_View->Html->scriptBlock($inline);

		return "\n" . $output . "\n";
	}

/**
 * Insert a JS file in the javascripts list to be included
 * on layout header using Layout::javascripts().
 *
 * This method will NOT work if used on Themes layouts.
 * Use in Views ONLY.
 *
 * @param string $url URL to the js file
 * @param string $type Insert as `file` or `inline`. default: `file`
 * @return void
 * @see AppController::$Layout
 */
	public function script($url, $type = 'file') {
		if (!in_array($url, $this->_View->viewVars['Layout']['javascripts'][$type])) {
			$this->_View->viewVars['Layout']['javascripts'][$type][] = $url;
		}
	}

/**
 * Render extra code for header.
 * This function should be used by themes just before </head>.
 *
 * @return string HTML code to include in header
 */
	public function header() {
		if (is_string($this->_View->viewVars['Layout']['header'])) {
			return $this->_View->viewVars['Layout']['header'];
		}

		if (is_array($this->_View->viewVars['Layout']['header'])) {
			$out = '';

			foreach ($this->_View->viewVars['Layout']['header'] as $code) {
				$out .= "{$code}\n";
			}
		}

		$this->hook('layout_header_alter', $out);

		return "\n" . $out;
	}

/**
 * Shortcut for `$title_for_layout`.
 *
 * @return string Current page's title
 */
	public function title() {
		$title = isset($this->_View->viewVars['Layout']['node']['Node']['title']) ? __t($this->_View->viewVars['Layout']['node']['Node']['title']) : Configure::read('Variable.site_name');
		$title = $this->_View->viewVars['title_for_layout'] != Inflector::camelize($this->_View->params['controller']) || Router::getParam('admin') ? $this->_View->viewVars['title_for_layout'] : $title;

		$this->hook('layout_title_alter', $title);

		return $this->hooktags(__t($title));
	}

/**
 * Shortcut for `View::fetch('content')`.
 *
 * @return string Current page's HTML content
 */
	public function content() {
		$content = $this->_View->fetch('content');

		$this->hook('layout_content_alter', $content);

		return $content;
	}

/**
 * Render extra code for footer.
 * This function should be used by themes just before </body>.
 *
 * @return string HTML code
 */
	public function footer() {
		if (is_string($this->_View->viewVars['Layout']['footer'])) {
			return $this->_View->viewVars['Layout']['header'];
		}

		if (is_array($this->_View->viewVars['Layout']['footer'])) {
			$out = '';

			foreach ($this->_View->viewVars['Layout']['footer'] as $code) {
				$out .= "{$code}\n";
			}
		}

		$this->hook('layout_footer_alter', $out);

		return "\n" . $out;
	}

/**
 * Return all meta-tags for the current page.
 * This function should be used by themes between <head> and </head> tags.
 *
 * @param array $metaForLayout
 *	Optional asociative array of aditional meta-tags to
 *	merge with Layout metas `meta_name => content`.
 * @return string HTML formatted meta tags
 * @see AppController::$Layout
 */
	public function meta($metaForLayout = array()) {
		$metaForLayout = Hash::merge($metaForLayout, $this->_View->viewVars['Layout']['meta']);
		$out = $this->_View->Html->charset() . "\n";

		foreach ($metaForLayout as $name => $content) {
			if (empty($name) || empty($content)) {
				continue;
			}

			if (is_array($content)) {
				$out .= $this->_View->Html->meta($content) . "\n";
			} else {
				$out .= $this->_View->Html->meta($name, $content) . "\n";
			}
		}

		$this->hook('layout_meta_alter', $out);

		return $out;
	}

/**
 * Shortcut for Session setFlash.
 *
 * @param string $msg Mesagge to display
 * @param string $class Type of message: error, success, alert, bubble
 * @param string $id Message id, default is 'flash'
 * @return void
 */
	public function flashMsg($msg, $class, $id = 'flash') {
		$message = $msg;
		$element = 'theme_flash_message';
		$params = array('class' => $class);

		CakeSession::write("Message.{$id}", compact('message', 'element', 'params'));
	}

/**
 * Show flash messages.
 * If ID is given only that message will be rendered.
 * All messages will be rendered otherwise.
 *
 * @param string $id Optional ID of the messages
 * @return string
 * @see LayoutHelper::flashMsg()
 */
	public function sessionFlash($id = false) {
		if ($id) {
			return $this->Session->flash($id);
		} else {
			$messages = CakeSession::read('Message');

			if (is_array($messages)) {
				$out = '';

				foreach (array_keys($messages) as $key) {
					$out .= $this->Session->flash($key);
				}

				return $out;
			} elseif (is_string($messages)) {
				return $messages;
			}
		}

		return '';
	}

/**
 * Checks if an view-element exists and return its full path.
 *
 * @param string $_name The name of the element to find
 * @return mixed Either a string to the element filename or false when one can't be found
 */
	public function elementExists($_name) {
		if (isset($this->_tmp['elementExists'][$_name])) {
			return $this->_tmp['elementExists'][$_name];
		}

		list($plugin, $name) = $this->_View->pluginSplit($_name);
		$exts = array($this->_View->ext);

		if ($this->_View->ext !== '.ctp') {
			array_push($exts, '.ctp');
		}

		$viewPaths = App::path('View');
		$corePaths = array_merge(App::core('View'), App::core('Console/Templates/skel/View'));

		if (!empty($plugin)) {
			$count = count($viewPaths);

			for ($i = 0; $i < $count; $i++) {
				if (!in_array($viewPaths[$i], $corePaths)) {
					$paths[] = $viewPaths[$i] . 'Plugin' . DS . $plugin . DS;
				}
			}

			$paths = array_merge($paths, App::path('View', $plugin));
		}

		$paths = array_unique(array_merge($paths, $viewPaths));

		if (!empty($this->theme)) {
			$themePaths = array();

			foreach ($paths as $path) {
				if (strpos($path, DS . 'Plugin' . DS) === false) {
					if ($plugin) {
						$themePaths[] = $path . 'Themed' . DS . $this->theme . DS . 'Plugin' . DS . $plugin . DS;
					}

					$themePaths[] = $path . 'Themed' . DS . $this->theme . DS;
				}
			}

			$paths = array_merge($themePaths, $paths);
		}

		$paths = array_merge($paths, $corePaths);

		foreach ($exts as $ext) {
			foreach ($paths as $path) {
				if (file_exists($path . 'Elements' . DS . $name . $ext)) {
					$this->_tmp['elementExists'][$_name] = $path . 'Elements' . DS . $name . $ext;

					return $this->_tmp['elementExists'][$_name];
				}
			}
		}

		$this->_tmp['elementExists'][$_name] = false;

		return false;
	}

/**
 * Return rendered breadcrumb. Data is passed to themes for formatting the crumbs.
 * Default formatting is fired in case of no theme-format response.
 *
 * @return string HTML formatted breadcrumb
 */
	public function breadCrumb() {
		$b = $this->_View->viewVars['breadCrumb'];
		$this->hook('breadcrumb_alter', $b);

		$crumbs = $this->_View->element('theme_breadcrumb', array('breadcrumb' => $b));

		return $crumbs;
	}

/**
 * Generates user's avatar image.
 *
 * @param array $user Optional user data, current logged user data will be used otherwise
 * @param array $options extra Options for Html->image()
 * @return HTML <img>
 * @deprecated Since v1.1. Use UserHelper::avatar() instead
 */
	public function userAvatar($user = false, $options = array()) {
		trigger_error(__t('LayoutHelper::userAvatar() is deprecated, use UserHelper::avatar() instead'), E_USER_WARNING);
		return $this->User->avatar($user, $options);
	}	

/**
 * Render child nodes of the given menu node (father).
 *
 * @param mixed $path String path of the father node or boolen false to use current path
 * @param string $region Theme region where the child nodes will be rendered, 'content' by default
 * @return string Html rendered menu
 * @deprecated Since v1.1. Use MenuHelper::menuNodeChildren() instead
 */
	public function menuNodeChildren($path = false, $region = 'content') {
		trigger_error(__t('LayoutHelper::menuNodeChildren() is deprecated, use MenuHelper::menuNodeChildren() instead'), E_USER_WARNING);
		return $this->Menu->menuNodeChildren($path, $region);
	}

/**
 * Wrapper method to MenuHelper::generate().
 *
 * @param array $menu Array of links to render
 * @param array $settings Optional, customization options for menu rendering process
 * @return string HTML rendered menu
 * @see MenuHelper::generate
 * @deprecated Since v1.1. Use MenuHelper::render() instead
 */
	public function menu($menu, $settings = array()) {
		trigger_error(__t('LayoutHelper::menu() is deprecated, use MenuHelper::render() instead'), E_USER_WARNING);
		return $this->Menu->render($menu, $settings);
	}

/**
 * Creates a simple plain (deph 0) menu list.
 * Useful when creating backend submenu buttons.
 *
 * ### Usage
 *
 *     $links = array(
 *         array('title link 1', '/your/url_1/', 'options' => array(), 'pattern' => '*url/to/match*'),
 *         array('title link 2', '/your/url_2/', 'options' => array('class' => 'css-class')),
 *         ...
 *     );
 *
 *     $this->Menu->toolbar($links);
 *
 * ### Link Parameters
 *
 * - options: array (optional): array of options for HtmlHelper::link()
 * - pattern: string (optional): show link as selected on pattern match (asterisk allowed)
 *
 * @param array $links List of links
 * @param array $options Array of options:
 *
 *  - id: id attribute for the container (ul, ol)
 *  - type: type of list, ol, ul. default: ul
 *  - itemType: type of child node. default: li
 *  - activeClass: class attribute for selected itemType. default: `selected`
 *
 * @return string HTML
 * @deprecated Since v1.1. Use MenuHelper::toolbar() instead
 */
	public function toolbar($links, $options = array()) {
		trigger_error(__t('LayoutHelper::toolbar() is deprecated, use MenuHelper::toolbar() instead'), E_USER_WARNING);
		return $this->Menu->toolbar($links, $options);
	}

/**
 * Manually insert a custom block to stack.
 *
 * @param array $block Formatted block array:
 *
 *  - title
 *  - pages
 *  - visibility
 *  - body
 *  - region
 *  - theme
 *  - format
 *
 * @param string $region Theme region where to push
 * @return boolean TRUE on success. FALSE otherwise
 * @deprecated Since v1.1. Use BlockHelper::push() instead
 */
	public function blockPush($block = array(), $region = '') {
		trigger_error(__t('LayoutHelper::blockPush() is deprecated, use BlockHelper::push() instead'), E_USER_WARNING);
		return $this->Block->push($block, $region);
	}

/**
 * Checks if the given theme region is empty or not.
 *
 * @param string $region Region alias
 * @return boolean TRUE no blocks in region, FALSE otherwise
 * @deprecated Since v1.1. Use BlockHelper::regionCount() instead
 */
	public function emptyRegion($region) {
		trigger_error(__t('LayoutHelper::emptyRegion() is deprecated, use BlockHelper::regionCount() instead'), E_USER_WARNING);
		return ($this->Block->regionCount($region) == 0);
	}

/**
 * Returns the number of blocks in the specified region.
 *
 * @param string $region Region alias to count
 * @return integer Number of blocks
 * @deprecated Since v1.1. Use BlockHelper::regionCount() instead
 */
	public function blocksInRegion($region) {
		trigger_error(__t('LayoutHelper::blocksInRegion() is deprecated, use BlockHelper::regionCount() instead'), E_USER_WARNING);
		return $this->Block->regionCount($region);
	}

/**
 * Render all blocks for a particular region.
 *
 * @param string $region Region alias to render
 * @return string Html blocks
 * @deprecated Since v1.1. Use BlockHelper::region() instead
 */
	public function blocks($region) {
		trigger_error(__t('LayoutHelper::blocks() is deprecated, use BlockHelper::region() instead'), E_USER_WARNING);
		return $this->Block->region($region);
	}

/**
 * Render single block.
 * By default the following CSS classes may be applied to the block wrapper DIV element:
 *
 *  -	`qa-block`: always applied.
 *  -	`qa-block-first`: only to the first element of the region.
 *  -	`qa-block-last`: only to the last element of the region.
 *  -	`qa-block-unique`: to the block number 1/1 of the region, in other words,
 *		the first & last at the same time.
 *
 * @param array $block Well formated block array.
 * @param array $options Array of options:
 *
 *  - boolean title: Render title. default true.
 *	- boolean body: Render body. default true.
 *	- string region: Region where block belongs to.
 *	- array params: extra options used by block.
 *	- array class: list of extra CSS classes for block wrapper.
 *
 * @return string Html
 * @deprecated Since v1.1. Use BlockHelper::render() instead
 */
	public function block($block, $options = array()) {
		trigger_error(__t('LayoutHelper::block() is deprecated, use BlockHelper::render() instead'), E_USER_WARNING);
		return $this->Block->render($block, $options);
	}

/**
 * Returns node type of the current node's being renderend.
 * Valid only when rendering a single node (display = full) or
 * after LayoutHelper::renderNode() is invoked.
 *
 * @return mixed String ID of the NodeType or FALSE if could not be found
 * @deprecated Since v1.1. Use NodeHelper::getAttr() instead
 */
	public function getNodeType() {
		trigger_error(__t('LayoutHelper::getNodeType() is deprecated, use NodeHelper::getAttr("node_type_id") instead'), E_USER_WARNING);
		return $this->Node->getAttr('node_type_id');
	}

/**
 * Returns specified node's field (from `node` table).
 * Valid only when rendering a single node (display = full) or
 * after LayoutHelper::renderNode() is invoked.
 *
 * @param string $field Field name to retrieve. e.g.: `id` for Node's ID
 * @return mixed Array of the field if exists. FALSE otherwise
 * @deprecated Since v1.1. Use NodeHelper::getAttr() instead
 */
	public function nodeField($field = false) {
		trigger_error(__t('LayoutHelper::nodeField() is deprecated, use NodeHelper::getAttr() instead'), E_USER_WARNING);
		return $this->Node->getAttr($field);
	}

/**
 * Render the specified Node or `current` Node.
 * Node rendering hook is invoked based on NodeType, but if is there is no response
 * then default rendering proccess is fired.
 *
 * @param mixed $node Optional:
 *
 *  - (boolean) FALSE: current node will be rendered. (by default)
 *  - (string) SLUG: render node by node's slug.
 *  - (array): asociative Node's array to render.
 *
 * @param array $options Node rendering options:
 *
 *  - (mixed) class: array or string, extra CSS class(es) for node DIV container
 *  - (mixed) display: set to string value to force rendering display mode. set to boolean false for automatic.
 *
 * @return string HTML formatted node. Empty string will be returned if node could not be rendered.
 * @deprecated Since v1.1. Use NodeHelper::render() instead
 */
	public function renderNode($node = false, $options = array()) {
		trigger_error(__t('LayoutHelper::renderNode() is deprecated, use NodeHelper::render() instead'), E_USER_WARNING);
		return $this->Node->render($node, $options);
	}

/**
 * Renders the given field.
 * Field's `view.ctp` nor `edit.ctp` element is rendered.
 *
 * @param array $field Field information array
 * @param boolean $edit Set to TRUE for edit form. FALSE for view mode
 * @param string $display Force rendering for the given display-mode
 * @return string HTML formatted field
 * @deprecated Since v1.1. Use NodeHelper::renderField() instead
 */
	public function renderField($field, $edit = false, $display = null) {
		trigger_error(__t('LayoutHelper::renderField() is deprecated, use NodeHelper::renderField() instead'), E_USER_WARNING);
		return $this->Node->renderField($field, $edit, $display);
	}
}