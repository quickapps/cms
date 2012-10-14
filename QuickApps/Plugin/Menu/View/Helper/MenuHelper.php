<?php
/**
 * Menu Helper
 *
 * @author	 Christopher Castro <chris@quickapps.es>
 * @package	 QuickApps.Plugin.Menu.View.Helper
 */
class MenuHelper extends AppHelper {
/**
 * Temporary container
 *
 * @var array
 */
	private $__tmp = array(
		'ids' => array()
	);

/**
 * Menu item settings
 *
 * @var array
 */
	private $__itemDefaults = array(
		'id' => null,
		'class' => null,
		'title' => null,
		'description' => '',
		'url' => null,
		'target' => '_self',
		'permissions' => array(),
		'children' => array(),
		'expanded' => true,
		'element' => false,
		'callback' => false,
		'status' => 1
	);

/**
 * Menu settings
 *
 * @var array
 */
	private $__defaultSettings = array(
		'model' => 'MenuLink',
		'id' => null,
		'class' => null,
		'partialMatch' => false,
		'activeClass' => 'active',
		'firstClass' => 'first-item',
		'lastClass' => 'last-item',
		'force' => false,
		'childrenClass' => 'hasChildren',
		'evenOdd' => false,
		'itemFormat' => '<li %s>%s</li>',
		'itemAttributes' => array(),
		'wrapperFormat' => '<ul %s>%s</ul>',
		'wrapperAttributes' => array(),
		'element' => false,
		'callback' => false,
		'urlPath' => array('link_path', 'router_path'),
		'titlePath' => array('link_title', 'title', 'name', 'alias'),
		'descriptionPath' => array('description'),
		'__pos__' => 0,
		'__depth__' => 0
	);

/**
 * Settings of the menu being rendered
 *
 * @var array
 */
	public $settings = array();

/**
 * Tree menu generation method.
 *
 * It accepts only results of `Model::find('threaded')`.
 *
 * ## Settings:
 *	-	`model`: Name of the model (key) to look for in the data array. (default - 'MenuLink')
 *	-	`id`: Optional string for the id attribute of top level tag. (default - none)
 *	-	`class`: Optional string of CSS classes for top level tag. (default - none)
 *	-	`partialMatch`: Normally url matching are strict.
 *		e.g.: Suppose you are in /items/details and your menu contains an entry for `/item` then by
 *	  	default it'll not set active. But if you set `partialMatch` to true then it'll set active. (default - false)
 *	-	`activeClass`: Classname for the selected/current item. (default - 'active')
 *	-	`firstClass`: Classname for the first item. (default - 'first-item')
 *	-	`lastClass`: Classname for the first item. (default - 'last-item')
 *	-	`force`: Forced rendering. Items with `status` = 0 will be rendered, as well childs of parents with `expanded` = false.
 *		It basically ignores both `status` and `expanded` attributes for each item.
 *	-	`childrenClass`: Classname for an item containing sub menu. (default - 'hasChildren')
 *	-	`evenOdd`: If it is set to true then even/odd classname will be provided with each item. (default - false)
 *	-	`itemFormat`: If you want to use other tag than li for menu items. (default - '<li %s>%s</li>')
 *		Array-path-patterns are allowed. e.g.: The pattern `{title}` will be replaced by `$item['title']`.
 *	-	`itemAttributes`: Mixed array list of html attributes for the item tag. (default - none)
 *		It accept: associative array, plain array or mixed structure.
 *		e.g.: array('id' => 'item-id', 'class' => 'item-class', 'rare-attr="attr-value"')
 *	-	`wrapperFormat`: if you want to use other tag than ul for menu items container. (default - '<ul %s>%s</ul>')
 *		Array-path-patterns are allowed.
 *	-	`wrapperAttributes`: Mixed array list of html attributes for the top level tag. Same as `itemAttributes` (default - none)
 *	-	`element`: Path to a rendering element, Plugin-Dot-Syntax allowed.
 *		e.g.: 'MyModule.node_element'. (default - false)
 *	-	`callback`: Callback to use to get node contents.
 *		e.g. array(&$anObject, 'methodName') or 'floatingMethod'. (default - false)
 *	-	`urlPath`: The array key where to get the `url` parameter. It can be a single value as string, or
 *		a list of possible keys to try.
 *		e.g.: `array('url', 'link_url')` will try to get the URL from `$item['title']` or `$item['link_url']` if first fail.
 *	-	`titlePath`: The array key where to get the `title` parameter. Work same as `urlPath`.
 *	-	`descriptionPath`: The array key where to get the `description` parameter. Work same as `urlPath`.
 *		This value is used as `title` attrbute for the <a> tag. e.g.: `<a href="" title="DESCRIPTION">...`
 *	-	`__pos__`: Used internally when running recursively. Should never be modified.
 *	-	`__depth__`: Used internally when running recursively. Should never be modified.
 *
 * @param array $data data to loop on
 * @param array $settings
 * @return string HTML representation of the passed data
 */
	public function render($data, $settings = array()) {
		$out = $this->__render($data, $settings);
		$out = substr_replace($out, $this->settings['lastClass'], strrpos($out, '$__last-class__$'), strlen('$__last-class__$'));;
		$out = str_replace('$__last-class__$', '', $out);
		$out = str_replace('<ul >', '<ul>', $out);
		$out = str_replace('class=" ', 'class="', $out);

		return $out;
	}

	private function __render($data, $settings) {
		$_data = array(
			'menu' => $data,
			'settings' => array_merge($this->__defaultSettings, $settings)
		);

		$this->hook('menu_render_alter', $_data);
		extract($_data);

		$this->settings = $settings;
		$out = '';
		$__attrs = $wrapperAttributes = array();
		$this->__tmp['wrapperAttributes'] = $this->settings['wrapperAttributes'];

		if (isset($data[$this->settings['model']])) {
			$data = $data[$this->settings['model']];
		}

		if (!isset($this->__tmp['crumb_urls']) || empty($this->__tmp['crumb_urls'])) {
			$this->__tmp['crumb_urls'] = (array)Hash::extract($this->_View->viewVars['breadCrumb'], "{n}.url");

			if (Configure::read('Variable.url_language_prefix')) {
				foreach ($this->__tmp['crumb_urls'] as $crumb) {
					if (!preg_match('/^\/[a-z]{3}\//', $crumb)) {
						$this->__tmp['crumb_urls'][] = str_replace('//', '/', '/' . Configure::read('Config.language') . '/' . $crumb);
					}
				}
			}
		}

		$this->settings['urlPath'] = empty($this->settings['urlPath']) ? 'url' : $this->settings['urlPath'];
		$this->settings['titlePath'] = empty($this->settings['titlePath']) ? 'title' : $this->settings['titlePath'];

		if (is_string($this->settings['urlPath'])) {
			$this->settings['urlPath'] = array($this->settings['urlPath']);
		}

		if (is_string($this->settings['titlePath'])) {
			$this->settings['titlePath'] = array($this->settings['titlePath']);
		}

		foreach ($this->settings['itemAttributes'] as $attr => $values) {
			$values = is_array($values) ? implode(' ', $values) : $values;
			$__attrs[$attr] = $values;
		}

		if (!empty($__attrs)) {
			$this->settings['itemAttributes'] = $__attrs;
		}

		if (is_array($data)) {
			foreach($data as $item) {
				$out .= $this->_buildItem($item, $this->settings['__pos__'], $this->settings['__depth__']);
			}
		}

		if ($this->settings['__depth__'] == 0) {
			if ($this->settings['id']) {
				$wrapperAttributes[] = "id=\"{$this->settings['id']}\"";
			}

			if ($this->settings['class']) {
				$wrapperAttributes[] = "class=\"{$this->settings['class']}\"";
			}
		} else {
			foreach ($this->settings['wrapperAttributes'] as $name => $value) {
				if (is_integer($name) && $name === 0) {
					$wrapperAttributes[] = $value;
				} else {
					$wrapperAttributes[] = "{$name}=\"{$value}\"";
				}
			}
		}

		if (isset($this->__tmp['regenerateCrumbs'])) {
			$this->__tmp['crumb_urls'] = null;
		}

		$this->settings['wrapperAttributes'] = $this->__tmp['wrapperAttributes'];
		$tabs = $this->settings['__depth__'] > 0 ? str_repeat("\t", $this->settings['__depth__'] + 1) : '';

		return sprintf("\n" . $tabs . $this->settings['wrapperFormat'] . "\n", implode(' ', $wrapperAttributes), "\n" . $out . $tabs);
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
 * - `options` array (optional): array of options for HtmlHelper::link()
 * - `pattern` string (optional): show link as selected on pattern match (asterisk allowed)
 *
 * @param array $links List of links
 * @param array $options Array of options:
 * - `id`: id attribute for the container (ul, ol)
 * - `type`: type of list, ol, ul. default: ul
 * - `itemType`: type of child node. default: li
 * - `class`: class attribute for the top level element (ul)
 * - `activeClass`: class attribute for selected itemType. default: `active`
 * @return string HTML
 * @deprecated  
 */
	public function toolbar($links, $options = array()) {
		$data = array('links' => $links, 'options' => $options);
		$this->hook('menu_toolbar_alter', $data);

		extract($data);

		$_options = array(
			'id' => null,
			'type' => 'ul',
			'itemType' => 'li',
			'activeClass' => 'active',
			'class' => ''
		);

		$options = array_merge($_options, $options);

		extract($options);

		$class = is_array($class) ? implode(' ', $class) : $class;
		$class = !empty($class) ? " class=\"{$class}\"" : '';
		$id = !is_null($id) ? " id=\"{$id}\" " : '';
		$o = "<{$type}{$id}{$class}>\n";
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
			$selected = '';

			if (strpos($link[1], '://') === false) {
				$link[1] = preg_replace(array('/\/{2,}/', '/^\/[a-z]{3}\//', '/\/{1,}$/'), array('/', '', ''), "{$link[1]}/");

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
			}

			if (isset($link['options']) && is_array($link['options'])) {
				$link = $this->_View->Html->link($link[0], $link[1], $link['options']);
			} else {
				$link = $this->_View->Html->link($link[0], $link[1]);
			}

			$o .= "\t<{$itemType}{$selected}>" . $link . "</{$itemType}>\n";
		}

		$o .= "\n</{$type}>";

		return $o;
	}

	protected function _buildItem($item, $pos = 0, $depth = 0) {
		if (!empty($item[$this->settings['model']])) {
			$__item = array_merge($this->__itemDefaults, $item[$this->settings['model']]);

			if (isset($item['children'])) {
				$__item['children'] = $item['children'];
			}

			$item = $__item;
		} else {
			$item = array_merge($this->__itemDefaults, $item);
		}

		if (!$item['status'] && !$this->settings['force']) {
			return '';
		}

		$out = $children = '';
		$isActive = $hasChildren = false;
		$itemClass = array('$__last-class__$');
		$itemAttributes = array();
		$item['title'] = $this->__title($item);
		$this->__tmp['itemAttributes'] = $this->settings['itemAttributes'];

		if (empty($item['title'])) {
			return '';
		}

		if (!empty($item['permissions'])) {
			$userRoles = QuickApps::userRoles();
			$allowed = false;

			foreach ($item['permissions'] as $p) {
				if (in_array($p, $userRoles)) {
					$allowed = true;

					break;
				}
			}

			if (!$allowed) {
				return '';
			}
		}

		if (
			(!empty($item['children']) && $item['expanded']) ||
			(!empty($item['children']) && $this->settings['force'])
		) {
			$_settings = $__settings = $this->settings;
			$__settings['__depth__']++;
			$children = $this->__render($item['children'], $__settings);
			$this->settings = $_settings;
			$hasChildren = true;
		}

		$item['url'] = $this->__url($item);

		if (!empty($item['url'])) {
			$options = array(
				'target' => $item['target'],
				'escape' => false
			);

			if ($item['url'] == '#') {
				if (isset($options['onclick'])) {
					$options['onclick'] .= 'return false;';
				} else {
					$options['onclick'] = 'return false;';
				}
			}

			if ($description = $this->__description($item)) {
				$options['title'] = $description;
			}

			$url = $this->_View->Html->link(
				'<span>' . __t($item['title']) . '</span>',
				__t($item['url']),
				$options
			);

			$isActive = $this->__isActive($item);
		}

		if ($pos === 0) {
			$itemClass[] = $this->settings['firstClass'];
		}

		if ($isActive) {
			$itemClass[] = $this->settings['activeClass'];
		}

		if ($hasChildren) {
			$itemClass[] = $this->settings['childrenClass'];
		}

		if ($this->settings['evenOdd']) {
			$itemClass[] = (($pos&1) ? 'even' : 'odd');
		}

		$itemClass = array_filter($itemClass);

		if (isset($item['class'])) {
			if (is_array($item['class'])) {
				$itemClass = array_merge($itemClass, $item['class']);
			} else {
				$itemClass[] = $item['class'];
			}
		}

		if (isset($item['id'])) {
			$id = is_numeric($item['id']) ? "menu-item-{$item['id']}" : $item['id'];

			if (!in_array($id, $this->__tmp['ids'])) {
				$this->settings['itemAttributes']['id'] = $id;
				$this->__tmp['ids'][] = $id;
			}

			$itemClass[] = is_numeric($item['id']) ? "menu-item-{$item['id']}" : $item['id'];
		}

		if (!empty($itemClass)) {
			if (isset($this->settings['itemAttributes']['class'])) {
				$this->settings['itemAttributes']['class'] = $this->settings['itemAttributes']['class'] . ' ' . implode(' ', $itemClass);
			} else {
				$this->settings['itemAttributes']['class'] = implode(' ', $itemClass);
			}
		}

		$elementData = array(
			'data' => $item,
			'depth' => $this->settings['__depth__'],
			'position' => $this->settings['__pos__'],
			'hasChildren' => $hasChildren
		);

		if ($this->settings['element'] || $__item['element']) {
			$element = $__item['element'] ? $__item['element'] : $this->settings['element'];
			$content = $this->_View->element($element, $elementData);
			$this->__tmp['regenerateCrumbs'] = true;
		} elseif ($this->settings['callback'] || $__item['callback']) {
			$callback = $__item['callback'] ? $__item['callback'] : $this->settings['callback'];
			list($content) = array_map($callback, array($elementData));
		} else {
			$content = $url;
		}

		$content .= $children;

		foreach ($this->settings['itemAttributes'] as $name => $value) {
			if (is_integer($name) && $name === 0) {
				$itemAttributes[] = $value;
			} else {
				$itemAttributes[] = "{$name}=\"{$value}\"";
			}
		}

		$tabs = str_repeat("\t", $this->settings['__depth__'] + 1);
		$return = sprintf('%s' . $this->settings['itemFormat'], str_repeat("\t", $depth), implode(' ', $itemAttributes), $content . $tabs);
		$this->settings['itemAttributes'] = $this->__tmp['itemAttributes'];
		$this->settings['__pos__']++;

		return $tabs . $this->__parseAtts($return, $item) . "\n";
	}

	private function __isActive($item) {
		$isActive = false;
		$this->__tmp['here_full'] = !isset($this->__tmp['here_full']) ? Router::url(null, true) : $this->__tmp['here_full'];

		if (isset($item['link_path']) && !empty($item['link_path'])) {
			return false;
		}

		if (
			isset($item['selected_on']) && !empty($item['selected_on']) &&
			isset($item['selected_on_type']) && !empty($item['selected_on_type'])
		) {
			switch ($item['selected_on_type']) {
				case 'php':
					$isActive = $this->php_eval($item['selected_on']) === true;
				break;

				case 'reg':
					$isActive = QuickApps::urlMatch($item['selected_on'], '/' . $this->_View->request->url);
				break;
			}
		} elseif (
			isset($this->_View->viewVars['Layout']['node']['Node']['translation_of']) &&
			!empty($this->_View->viewVars['Layout']['node']['Node']['translation_of']) &&
			preg_match('/\/(.*)\.html$/', $item['url']) &&
			strpos($item['url'], "/{$this->_View->viewVars['Layout']['node']['Node']['translation_of']}.html") !== false
		) {
			$isActive = true;
		} elseif (
			$this->settings['partialMatch'] &&
			in_array($item['url'], $this->__tmp['crumb_urls'])
		) {
			$isActive = true;
		} elseif (
			QuickApps::is('view.frontpage') &&
			QuickApps::strip_language_prefix($item['url']) == '/'
		) {
			$isActive = true;
		} elseif ($item['url'] == $this->__tmp['here_full']) {
			$isActive = true;
		} else {
			$getURL = $this->__getUrl();

			if (isset($getURL[0]) && $getURL[0] == $item['url']) {
				$isActive = true;
			}
		}

		return $isActive;
	}

	private function __title($item) {
		foreach ($this->settings['titlePath'] as $tp) {
			if (isset($item[$tp]) && !empty($item[$tp])) {
				return $item[$tp];
			}
		}

		return '';
	}

	private function __url($item) {
		foreach ($this->settings['urlPath'] as $up) {
			if (isset($item[$up]) && !empty($item[$up])) {
				return $item[$up];
			}
		}

		return '#';
	}

	private function __description($item) {
		foreach ($this->settings['descriptionPath'] as $dp) {
			if (isset($item[$dp]) && !empty($item[$dp])) {
				return $item[$dp];
			}
		}

		return '';
	}

	private function __parseAtts($string, $item) {
		preg_match_all('/\{([\{\}0-9a-zA-Z_\.]+)\}/iUs', $string, $path);

		if (isset($path[1]) && !empty($path[1])) {
			foreach ($path[0] as $i => $m) {
				$string = str_replace($m, array_pop(Hash::extract($item, trim($path[1][$i]))), $string);
			}
		}

		return $string;
	}

	private function __getUrl() {
		if (isset($this->__tmp['__getUrl']) && !empty($this->__tmp['__getUrl'])) {
			return $this->__tmp['__getUrl'];
		}

		$url = '/' . $this->_View->request->url;
		$out = array();
		$out[] = $url;

		foreach ($this->_View->request->params['named'] as $key => $val) {
			$url = QuickApps::str_replace_once("/{$key}:{$val}", '', $url);
			$out[] = $url;
		}

		$out[] = $url;

		if ($this->_View->request->params['controller'] == Inflector::underscore($this->plugin)) {
			$url =  QuickApps::str_replace_once("/{$this->_View->request->params['controller']}", '', $url);
			$out[] = $url;
		} else if ($this->_View->request->params['action'] == 'index' || $this->_View->request->params['action'] == 'admin_index') {
			$url =  QuickApps::str_replace_once("/index", '', $url);
			$out[] = $url;
		}

		foreach ($this->_View->request->params['pass'] as $p) {
			$url = QuickApps::str_replace_once("/{$p}", '', $url);
			$out[] = $url;
		}

		$out = array_unique($out);

		foreach ($out as &$u) {
			$u = preg_replace('/^\/[a-z]{3}\//', '/', $u);
			$u = preg_replace('/\/{2,}/', '/', $u);
			$u = urldecode($u);
		}

		$this->__tmp['__getUrl'] = $out;

		return $this->__tmp['__getUrl'];
	}
}