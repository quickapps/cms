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
 * Render css files links.
 *
 * @param array $stylesheets Asociative array of extra css elements to merge
 *
 *    array(
 *        'inline' => array("css code1", "css code2", ...)
 *        'print' => array("file1", "file2", ...),
 *        'all' => array("file3", "file4", ...),
 *        ....
 *    );
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
 *    array(
 *        'inline' => array("code1", "code2", ...),
 *        'file' => array("path_to_file1", "path_to_file2", ...)
 *    );
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
 * @param array $metaForLayout Optional asociative array of aditional meta-tags to
 *							 merge with Layout metas `meta_name => content`.
 * @return string HTML formatted meta tags
 * @see AppController::$Layout
 */
	public function meta($metaForLayout = array()) {
		if (!is_array($metaForLayout) || empty($metaForLayout)) {
			$metaForLayout = Hash::merge($this->_View->viewVars['Layout']['meta'], $metaForLayout);
		}

		$out = '';

		foreach ($metaForLayout as $name => $content) {
			$out .= $this->_View->Html->meta($name, $content) . "\n";
		}

		$this->hook('layout_meta_alter', $out);

		return $out;
	}

/**
 * Returns node type of the current node's being renderend.
 * (Valid only when rendering a single node [display = full])
 *
 * @return mixed String ID of the NodeType or FALSE if could not be found
 */
	public function getNodeType() {
		if (!isset($this->_View->viewVars['Layout']['node']['NodeType']['id'])) {
			return false;
		}

		return $this->_View->viewVars['Layout']['node']['NodeType']['id'];
	}

/**
 * Returns specified node's field.
 * Valid only when rendering a single node (display = full).
 *
 * @param string $field Field name to retrieve
 * @return mixed Array of the field if exists. FALSE otherwise
 */
	public function nodeField($field = false) {
		if (!is_string($field)) {
			return false;
		}

		if ($field == 'node_type_id') {
			return $this->getNodeType();
		}

		if (isset($this->_View->viewVars['Layout']['node']['Node'][$field])) {
			return $this->_View->viewVars['Layout']['node']['Node'][$field];
		}

		return false;
	}

/**
 * Render the specified Node or `current` Node.
 * Node rendering hook is invoked based on NodeType, but if is there is no response
 * then default rendering proccess is fired.
 *
 * @param mixed $node Optional:
 *	- boolean FALSE: current node will be rendered. (by default)
 *	- string SLUG: render node by node's slug.
 *	- array : asociative Node's array to render.
 * @param array $options Node rendering options:
 *	- mixed class: array or string, extra CSS class(es) for node DIV container
 *	- mixed display: set to string value to force rendering display mode. set to boolean false for automatic.
 * @return string HTML formatted node. Empty string will be returned if node could not be rendered.
 */
	public function renderNode($node = false, $options = array()) {
		$options = array_merge(
			array(
				'class' => array(),
				'display' => false
			)
		, $options);

		extract($options);

		$nodeClasses = !is_array($class) ? array($class) : $class;

		if ($node === false) {
			$node = $this->_View->viewVars['Layout']['node'];
		} elseif (is_string($node)) {
			$node = ClassRegistry::init('Node.Node')->findBySlug($node);
		} elseif (!is_array($node)) {
			return '';
		}

		if (empty($node)) {
			return '';
		}

		$content = '';
		$view_mode = $display !== false ? $display : $this->_View->viewVars['Layout']['display'];

		foreach ($node['Field'] as $key => &$data) {
			// undefined display -> use default
			if (!isset($data['settings']['display'][$view_mode]) && isset($data['settings']['display']['default'])) {
				$data['settings']['display'][$view_mode] = $data['settings']['display']['default'];
			}
		}

		$node['Field'] = Hash::sort($node['Field'], "{n}.settings.display.{$view_mode}.ordering", 'asc');
		$sufix = $node['NodeType']['module'] == 'Node' ? 'render' : $node['NodeType']['id'];
		$callback = "{$node['NodeType']['base']}_{$sufix}";
		$beforeRender = (array)$this->hook('before_render_node', $node, array('collectReturn' => true));

		if (in_array(false, $beforeRender, true)) {
			return '';
		}

		$content .= implode('', $beforeRender);
		$content_callback = $this->hook($callback, $node, array('collectReturn' => false));

		if (empty($content_callback)) {
			$content .= "<h1>" . __t('The node could not be rendered') . "</h1>";
		} else {
			$content .= $content_callback;
		}

		$content .= implode('', (array)$this->hook('after_render_node', $node, array('collectReturn' => true)));
		$content = "\n\t" . $this->hooktags($content) . "\n";

		if (isset($this->_tmp['renderedNodes'])) {
			$this->_tmp['renderedNodes']++;
		} else {
			$this->_tmp['renderedNodes'] = 1;
		}

		if (isset($node['Node']['params']['class'])) {
			$nodeClasses = array_merge($nodeClasses, explode(' ', preg_replace('/\s{2,}/', ' ', $node['Node']['params']['class'])));
		}

		$nodeClasses = array_merge(
			array(
				'node',
				"node-{$node['NodeType']['id']}",
				"node-{$this->_View->viewVars['Layout']['display']}",
				"node-" . ($node['Node']['promote'] ? "promoted" : "demote"),
				"node-" . ($node['Node']['sticky'] ? "sticky" : "nosticky"),
				"node-" . ($this->_tmp['renderedNodes']%2 ? "odd" : "even")
			),
			$nodeClasses);

		$div = "\n" . $this->_View->Html->div(implode(' ', $nodeClasses), $content, array('id' => "node-{$node['Node']['id']}")) . "\n";

		return $div;
	}

/**
 * Renders the given field.
 * Field's `view.ctp` nor `edit.ctp` element is rendered.
 *
 * @param array $field Field information array
 * @param boolean $edit Set to TRUE for edit form. FALSE for view mode
 * @param string $display Force rendering for the given display-mode
 * @return string HTML formatted field
 */
	public function renderField($field, $edit = false, $display = null) {
		$__display = $display ? $display : $this->_View->viewVars['Layout']['display'];

		if (isset($field['settings']['display'][$__display]['type']) &&
			$field['settings']['display'][$__display]['type'] == 'hidden'
		) {
			return '';
		}

		$field['label'] = $this->hooktags($field['label']);
		$elementVars = array();

		if ($edit) {
			$view = 'edit';
			$field['label'] .= $field['required'] ? ' *' : '';
			$field['description'] = !empty($field['description']) ? $this->hooktags($field['description']) : '';
		} else {
			$display = isset($field['settings']['display'][$__display]) ? $__display: 'default';

			if (isset($field['settings']['display'][$display]['type']) && $field['settings']['display'][$display]['type'] != 'hidden') {
				$view = 'view';
				$elementVars['display'] = $field['settings']['display'][$display];
			} else {
				return '';
			}
		}

		$elementVars['field'] = $field;
		$data = array('field' => $field, 'edit' => $edit, 'display' => $display);
		$beforeRender = (array)$this->hook('before_render_field', $data, array('collectReturn' => true));

		if (in_array(false, $beforeRender, true)) {
			return '';
		}

		extract($data);

		$result = $this->_View->element(Inflector::camelize($field['field_module']) . '.' . $view, array('data' => $elementVars));

		if (!empty($result)) {
			$result .= implode('', (array)$this->hook('after_render_field', $data, array('collectReturn' => true)));

			if (!$edit &&
				(!isset($field['settings']['display'][$__display]['hooktags']) || $field['settings']['display'][$__display]['hooktags'])
			) {
				$result = $this->hooktags($result);
			}

			$result = "\n\t" . $result . "\n";

			return "\n<div class=\"field-container field-name-{$field['name']} field-module-{$field['field_module']}\">{$result}</div>\n";
		}

		return '';
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
 * Return rendered breadcrumb. Data is passed to themes for formatting the crumbs.
 * Default formatting is fired in case of no theme format-response.
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
 * Render child nodes of the given menu node (father).
 *
 * @param mixed $path String path of the father node or boolen false to use current path
 * @param string $region Theme region where the child nodes will be rendered, 'content' by default
 * @return string Html rendered menu
 */
	public function menuNodeChildren($path = false, $region = 'content') {
		$output = '';

		if (!$path) {
			$base = Router::url('/');
			$path = '/';
			$path .= $base !== '/' ? str_replace($base, '', $this->_View->here) : $this->_View->here;
			$path = preg_replace("/\/{2,}/i", '/', $path);
		}

		$MenuLink = Classregistry::init('Menu.MenuLink');
		$here = $MenuLink->find('first',
			array(
				'conditions' => array(
					'MenuLink.router_path' => $path,
					'MenuLink.status' => 1
				)
			)
		);

		if (!empty($here)) {
			$subs = $MenuLink->children($here['MenuLink']['id']);
			$_subs['MenuLink'] = Hash::extract($subs, '{n}.MenuLink');

			if (empty($_subs['MenuLink'])) {
				return '';
			}

			$_subs['region'] = $region;
			$_subs['id'] = 'no-id';

			foreach ($_subs['MenuLink'] as &$node) {
				$tt = __t($node['link_title']);
				$dt = __t($node['description']);
				$node['link_title'] = $tt != $node['link_title'] ? $tt : __d(Inflector::underscore($node['module']), $node['link_title']);
				$node['description'] = $dt != $node['description'] ? $dt : __d(Inflector::underscore($node['module']), $node['description']);
			}

			$output = $this->_View->element('theme_menu', array('menu' => $_subs));
		}

		return $output;
	}

/**
 * Wrapper method to MenuHelper::generate().
 *
 * @param array $menu Array of links to render
 * @param array $settings Optional, customization options for menu rendering process
 * @return string HTML rendered menu
 * @see MenuHelper::generate
 */
	public function menu($menu, $settings = array()) {
		$data = array(
			'menu' => $menu,
			'settings' => $settings
		);

		$this->hook('menu_alter', $data);
		extract($data);

		return $this->Menu->generate($menu, $settings);
	}

/**
 * Generates user's avatar image.
 *
 * @param array $user Optional user data, current logged user data will be used otherwise
 * @param array $options extra Options for Html->image()
 * @return HTML <img>
 */
	public function userAvatar($user = false, $options = array()) {
		$__options = array(
			'class' => 'user-avatar'
		);

		if (!$user) {
			$user = $this->Session->read('Auth.User');
		}

		if (!isset($user['User']) && is_array($user)) {
			$user['User'] = $user;
		}

		if (isset($user['User']['avatar']) && !empty($user['User']['avatar'])) {
			$avatar = $user['User']['avatar'];
		} else {
			if (Configure::read('Variable.user_use_gravatar')) {
				if (isset($user['User']['email']) && !empty($user['User']['email'])) {
					$hash = md5(strtolower(trim("{$user['User']['email']}")));
				} else {
					$hash = md5(strtolower(trim("")));
				}

				$args = array();

				if (Configure::read('Variable.user_gravatar_size')) {
					$args[] = 's=' . Configure::read('Variable.user_gravatar_size');
				}

				if (Configure::read('Variable.user_gravatar_default')) {
					$args[] = 'd=' . Configure::read('Variable.user_gravatar_default');
				}

				if (Configure::read('Variable.user_gravatar_rating')) {
					$args[] = 'r=' . Configure::read('Variable.user_gravatar_rating');
				}

				if (Configure::read('Variable.user_gravatar_force_default') == 'y') {
					$args[] = 'f=' . Configure::read('Variable.user_gravatar_force_default');
				}

				$avatar = "http://www.gravatar.com/avatar/{$hash}?" . implode('&', $args);
			} else {
				$avatar = Configure::read('Variable.user_default_avatar');
			}
		}

		$options = array_merge($__options, $options);
		$html = $this->_View->Html->image($avatar, $options);

		$this->hook('after_render_user_avatar', $html);

		return $html;
	}

/**
 * Manually insert a custom block to stack.
 *
 * @param array $block Formatted block array:
 *  - title
 *  - pages
 *  - visibility
 *  - body
 *  - region
 *  - theme
 *  - format
 * @param string $region Theme region where to push
 * @return boolean TRUE on success. FALSE otherwise
 */
	public function blockPush($block = array(), $region = '') {
		$_block = array(
			'title' => '',
			'pages' => '',
			'visibility' => 0,
			'body' => '',
			'region' => null,
			'theme' => null,
			'format' => null
		);

		$block = array_merge($_block, $block);
		$block['module'] = null;
		$block['id'] = null;
		$block['delta'] = null;

		if (!empty($region)) {
			$block['region'] = $region;
		}

		if (is_null($block['theme'])) {
			$block['theme'] =  QuickApps::themeName();
		}

		if (empty($block['region']) || empty($block['body'])) {
			return false;
		}

		$__block  = $block;

		unset($__block['format'], $__block['body'], $__block['region'], $__block['theme']);

		$Block = array(
			'Block' => $__block,
			'BlockCustom' => array(
				'body' => $block['body'],
				'format' => $block['format']
			),
			'BlockRegion' => array(
				0 => array(
					'theme' => QuickApps::themeName(),
					'region' => $block['region']
				)
			)
		);

		$this->_View->viewVars['Layout']['blocks'][] = $Block;
		$this->_tmp['blocksInRegion'][$region]['blocks'][] = $Block;
		$this->_tmp['blocksInRegion'][$region]['blocks_ids'][] = $Block['Block']['id'];

		return true;
	}

/**
 * Creates a simple plain (deph 0) menu list.
 * Useful when creating backend submenu buttons.
 *
 * ### Usage
 *
 *    $links = array(
 *        array('title link 1', '/your/url_1/', 'options' => array(), 'pattern' => '/url/to/match'),
 *        array('title link 2', '/your/url_2/', 'options' => array('class' => 'css-class')),
 *        ...
 *    );
 *
 *    $this->Layout->toolbar($links);
 *
 * ### Link Parameters
 *
 *  - `options` array (optional): array of options for HtmlHelper::link()
 *  - `pattern` string (optional): show link as selected on pattern match (asterisk allowed)
 *
 * @param array $links List of links
 * @param array $options Array of options:
 *  - `id`: id attribute for the container (ul, ol)
 *  - `type`: type of list, ol, ul. default: ul
 *  - `itemType`: type of child node. default: li
 *  - `activeClass`: class attribute for selected itemType. default: `selected`
 * @return string HTML
 */
	public function toolbar($links, $options = array()) {
		$data = array('links' => $links, 'options' => $options);
		$this->hook('toolbar_alter', $data);

		extract($data);

		$_options = array(
			'id' => null,
			'type' => 'ul',
			'itemType' => 'li',
			'activeClass' => 'selected'
		);

		$options = array_merge($_options, $options);

		extract($options);

		$id = !is_null($id) ? " id=\"{$id}\" " : '';
		$o = "<{$type}{$id}>\n";
		$here = preg_replace("/\/{2,}/", '/', "/" . str_replace($this->_View->base, '', $this->_View->here) . "/");
		$here = preg_replace(array('/^\/[a-z]{3}\//', '/\/{1,}$/'), array('/', ''), $here);
		$path = parse_url($here);
		$path = $path['path'];

		foreach ($this->_View->request->named as $key => $val) {
			$path = str_replace("{$key}:{$val}", '', $path);
		}

		$path = preg_replace('/\/{2,}/', '/', "/{$path}/");
		$path = preg_replace(array('/^\/[a-z]{3}\//', '/\/{1,}$/'), array('/', ''), $path);

		foreach ($links as $link) {
			$link[1] = preg_replace(array('/\/{2,}/', '/^\/[a-z]{3}\//', '/\/{1,}$/'), array('/', '', ''), "{$link[1]}/");
			$selected = '';

			if ($here == $link[1] || $path == $link[1]) {
				$selected = " class=\"{$activeClass}\" ";
			} elseif (isset($link['pattern']) && $link['pattern'] !== false) {
				if ($link['pattern'] === true) {
					if ($link[1][0] === '/') {
						$__l = substr($link[1], 1);
					}

					$link['pattern'] = "*{$__l}*";
				}

				$selected = QuickApps::urlMatch($link['pattern'], $here) ? " class=\"{$activeClass}\" " : '';
			}

			$link = isset($link['options']) && is_array($link['options']) ? $this->_View->Html->link($link[0], $link[1], $link['options']) : $this->_View->Html->link($link[0], $link[1]);
			$o .= "\t<{$itemType}{$selected}><span>" . $link . "</span></{$itemType}>\n";
		}

		$o .= "\n</{$type}>";

		return $o;
	}

/**
 * Checks if the given theme region is empty or not.
 *
 * @param string $region Region alias
 * @return boolean TRUE no blocks in region, FALSE otherwise
 */
	public function emptyRegion($region) {
		return ($this->blocksInRegion($region) == 0);
	}

/**
 * Returns the number of blocks in the specified region.
 *
 * @param string $region Region alias to count
 * @return integer Number of blocks
 */
	public function blocksInRegion($region) {
		if (isset($this->_tmp['blocksInRegion'][$region]['blocks_ids'])) {
			return count($this->_tmp['blocksInRegion'][$region]['blocks_ids']);
		}

		$__blocks = $this->_View->viewVars['Layout']['blocks'];
		$block_ids = @Hash::extract((array)$__blocks, "{n}.BlockRegion.{n}[theme=" . QuickApps::themeName() . "][region={$region}].block_id");
		$t = 0;

		foreach ($__blocks as $block) {
			if (
				!in_array($block['Block']['id'], $block_ids) ||
				!$this->__blockAllowed($block)
			) {
				continue;
			}

			if (!isset($this->_tmp['blocksInRegion'][$region]['blocks_ids']) ||
				!in_array($block['Block']['id'], $this->_tmp['blocksInRegion'][$region]['blocks_ids'])
			) {
				// Cache improve
				$block['__allowed'] = true;
				$this->_tmp['blocksInRegion'][$region]['blocks'][] = $block;
				$this->_tmp['blocksInRegion'][$region]['blocks_ids'][] = $block['Block']['id'];
			}

			$t++;
		}

		return $t;
	}

/**
 * Render all blocks for a particular region.
 *
 * @param string $region Region alias to render
 * @return string Html blocks
 */
	public function blocks($region) {
		if (!$this->emptyRegion($region)) {
			$output = '';

			if (isset($this->_tmp['blocksInRegion'][$region]['blocks'])) {
				$blocks = $this->_tmp['blocksInRegion'][$region]['blocks'];
			} else {
				$blocks = array();
				$__blocks = $this->_View->viewVars['Layout']['blocks'];
				$block_ids = @Hash::extract((array)$__blocks, "{n}.BlockRegion.{n}[theme=" . QuickApps::themeName() . "][region={$region}].block_id");

				foreach ($__blocks as $key => $block) {
					if (in_array($block['Block']['id'], $block_ids)) {
						$blocks[] = $block;
					}
				}
			}

			foreach ($blocks as &$block) {
				if (isset($block['BlockRegion'])) {
					foreach ($block['BlockRegion'] as $key => $br) {
						if (!($br['theme'] == QuickApps::themeName() && $br['region'] == $region)) {
							unset($block['BlockRegion'][$key]);
						}
					}
				}
			}

			$blocks = @Hash::sort((array)$blocks, '{n}.BlockRegion.{n}.ordering', 'asc');

			foreach ($blocks as $k => $b) {
				if (empty($block) || !is_array($b)) {
					unset($blocks[$k]);
				}
			}

			$i = 1;
			$total = count($blocks);

			foreach ($blocks as $block) {
				$block['Block']['__region'] = $region;
				$block['Block']['__weight'] = array($i, $total);

				if ($o = $this->block($block)) {
					$output .= $o;
					$i += 1;
				}
			}

			$_data = array('html' => $output, 'region' => $region);
			$this->hook('after_render_blocks', $_data, array('collectReturn' => false)); // pass all rendered blocks (HTML) to modules

			extract($_data);

			return $html;
		}

		return '';
	}

/**
 * Checks if an element exists and return its full path.
 *
 * @param string $name The name of the element to find
 * @return mixed Either a string to the element filename or false when one can't be found
 */
	public function elementExists($name) {
		list($plugin, $name) = $this->_View->pluginSplit($name);
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
					return $path . 'Elements' . DS . $name . $ext;
				}
			}
		}

		return false;
	}

/**
 * Render single block.
 * By default the following CSS classes may be applied to the block wrapper DIV element:
 *  -	`qa-block`: always applied.
 *  -	`qa-block-first`: only to the first element of the region.
 *  -	`qa-block-last`: only to the last element of the region.
 *  -	`qa-block-unique`: to the block number 1/1 of the region, in other words,
 *		the first & last at the same time.
 *
 * @param array $block Well formated block array.
 * @param array $options Array of options:
 *	- boolean title: Render title. default true.
 *	- boolean body: Render body. default true.
 *	- string region: Region where block belongs to.
 *	- array params: extra options used by block.
 *	- array class: list of extra CSS classes for block wrapper.
 * @return string Html
 */
	public function block($block, $options = array()) {
		$options = array_merge(
			array(
				'title' => true,
				'body' => true,
				'region' => true,
				'params' => array(),
				'class' => array('qa-block')
			),
			$options
		);

		$block['Block']['__region'] = !isset($block['Block']['__region']) ? '' : $block['Block']['__region'];
		$block['Block']['__weight'] = !isset($block['Block']['__weight']) ? array(0, 0) : $block['Block']['__weight'];

		if (!$this->__blockAllowed($block)) {
			return false;
		}

		if (is_array($block['Block']['__weight']) && $block['Block']['__weight'] != array(0, 0)) {
			if ($block['Block']['__weight'][1] == 1) {
				$options['class'][] = 'qa-block-unique';
			} elseif ($block['Block']['__weight'][0] === 1) {
				$options['class'][] = 'qa-block-first';
			} elseif ($block['Block']['__weight'][0] == $block['Block']['__weight'][1]) {
				$options['class'][] = 'qa-block-last';
			}
		}

		$region = $block['Block']['__region'];
		$Block = array(
			'id' => $block['Block']['id'],
			'module' => $block['Block']['module'],
			'delta' => $block['Block']['delta'],
			'title' => $block['Block']['title'],
			'body' => null,
			'region' => $region,
			'description' => null,
			'format' => null,
			'params' => (isset($block['Block']['params']) ? $block['Block']['params'] : array())
		);

		if (!empty($block['Menu']['id']) && $block['Block']['module'] == 'Menu') {
			// menu block
			$block['Menu']['region'] = $region;
			$Block['title'] = empty($Block['title']) ? $block['Menu']['title'] : $Block['title'];
			$Block['body'] = $this->_View->element('theme_menu', array('menu' => $block['Menu']));
			$Block['description'] = $block['Menu']['description'];
			$options['class'][] = 'qa-block-menu';
		} elseif (!empty($block['BlockCustom']['body'])) {
			// custom block
			$Block['body'] = @$block['BlockCustom']['body'];
			$Block['format'] = @$block['BlockCustom']['format'];
			$Block['description'] = @$block['BlockCustom']['description'];
			$options['class'][] = 'qa-block-custom';
		} else {
			// module block
			if ($this->elementExists("{$block['Block']['module']}.{$block['Block']['delta']}_block")) {
				$Block = $Block = $this->_View->element("{$block['Block']['module']}.{$block['Block']['delta']}_block", array('block' => $block));
			} else {
				$Block = $this->hook("{$block['Block']['module']}_{$block['Block']['delta']}", $block, array('collectReturn' => false));
			}

			if (empty($Block)) {
				return false;
			}

			if (is_string($Block)) {
				$Block = array(
					'body' => $Block
				);
			}

			if (!isset($Block['params'])) {
				$Block['params'] = (isset($block['Block']['params']) ? $block['Block']['params'] : array());
			}

			$Block['id'] = $block['Block']['id'];
			$Block['module'] = $block['Block']['module'];
			$Block['delta'] = $block['Block']['delta'];
			$Block['region'] = $region;
			$Block['title'] = !isset($Block['title']) ? $block['Block']['title'] : $Block['title'];
			$options['class'][] = 'qa-block-module';
		}

		$Block['weight'] = $block['Block']['__weight']; // X of total

		if ($options['title']) {
			$Block['title'] = $this->hooktags($Block['title']);
		} else {
			unset($Block['title']);
		}

		if ($options['body']) {
			$Block['body'] = $this->hooktags($Block['body']);
		} else {
			unset($Block['body']);
		}

		if (!$options['region']) {
			$Block['region'] = null;
		}

		if ($options['params']) {
			$options['params'] = !is_array($options['params']) ? array($options['params']) : $options['params'];
			$Block['params'] = $options['params'];
		}

		$this->hook('block_alter', $Block, array('collectReturn' => false)); // pass block array to modules

		$out = $this->_View->element('theme_block', array('block' => $Block)); // try theme rendering
		$data = array(
			'html' => $out,
			'block' => $Block
		);

		$this->hook('after_render_block', $data, array('collectReturn' => false));
		extract($data);

		return "<div id=\"qa-block-{$Block['id']}\" class=\"" . implode(' ', $options['class']) . "\">{$html}</div>";
	}

/**
 * Checks if the given block can be rendered.
 *
 * @param array $block Block structure
 * @return boolean
 */
	private function __blockAllowed($block) {
		if (!isset($block['__allowed'])) {
			if (isset($block['Block']['locale']) &&
				!empty($block['Block']['locale']) &&
				!in_array(Configure::read('Variable.language.code'), $block['Block']['locale'])
			) {
				return false;
			}

			if (!empty($block['Role'])) {
				$roles_id = Hash::extract($block, '{n}.Role.id');
				$allowed = false;

				foreach (QuickApps::userRoles() as $role) {
					if (in_array($role, $roles_id)) {
						$allowed = true;

						break;
					}
				}

				if (!$allowed) {
					return false;
				}
			}

			/**
			 * Check visibility
			 *
			 * - 0: Show on all pages except listed pages
			 * - 1: Show only on listed pages
			 * - 2: Use custom PHP code to determine visibility
			 */
			switch ($block['Block']['visibility']) {
				case 0:
					$allowed = QuickApps::urlMatch($block['Block']['pages']) ? false : true;
				break;

				case 1:
					$allowed = QuickApps::urlMatch($block['Block']['pages']) ? true : false;
				break;

				case 2:
					$allowed = $this->php_eval($block['Block']['pages']);
				break;
			}

			if (!$allowed) {
				return false;
			}
		} elseif (!$block['__allowed']) {
			return false;
		}

		return true;
	}
}