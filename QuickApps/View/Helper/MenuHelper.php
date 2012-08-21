<?php
/**
 * Menu Helper
 *
 * @author	 Christopher Castro <chris@quickapps.es>
 * @package	 QuickApps.View.Helper
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
		'activeClass' => 'selected',
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
 *	-	`activeClass`: Classname for the selected/current item. (default - 'selected')
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
 *	-	`element`: Path to an element to render to get node contents, Plugin-Dot-Syntax allowed.
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
	public function generate($data, $settings = array()) {
		$this->settings = array_merge($this->__defaultSettings, $settings);
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
			$__attrs[] = "{$attr}=\"" . implode(' ', $values) . "\"";
		}

		if (!empty($__attrs)) {
			$this->settings['itemAttributes'] = $__attrs;
		}

		if (is_array($data)) {
			foreach($data as $item) {
				$out .= $this->_buildItem($item, $this->settings['__pos__'], $this->settings['__depth__']);
			}
		}

		if ($this->settings['id']) {
			$this->settings['wrapperAttributes']['id'] = $this->settings['id'];
		}

		if ($this->settings['class']) {
			if (
				isset($this->settings['wrapperAttributes']['class']) &&
				is_string($this->settings['wrapperAttributes']['class'])
			) {
				$this->settings['wrapperAttributes']['class'] .= ' ' . $this->settings['class'];
			} else {
				$this->settings['wrapperAttributes']['class'] = $this->settings['class'];
			}
		}

		$out = preg_replace('~(.*)' . preg_quote('$__last-class__$', '~') . '~', '$1' . $this->settings['lastClass'], $out, 1);
		$out = str_replace('$__last-class__$', '', $out);

		if (isset($this->__tmp['regenerateCrumbs'])) {
			$this->__tmp['crumb_urls'] = null;
		}

		foreach ($this->settings['wrapperAttributes'] as $name => $value) {
			if (is_integer($name) && $name === 0) {
				$wrapperAttributes[] = $value;
			} else {
				$wrapperAttributes[] = "{$name}=\"{$value}\"";
			}
		}

		$this->settings['wrapperAttributes'] = $this->__tmp['wrapperAttributes'];
		$tabs = $this->settings['__depth__'] > 0 ? str_repeat("\t", $this->settings['__depth__'] + 1) : '';

		return sprintf("\n" . $tabs . $this->settings['wrapperFormat'] . "\n", implode(' ', $wrapperAttributes), "\n" . $out . $tabs);
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
		$isSelected = $hasChildren = false;
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
			$depth = $this->settings['__depth__'];
			$this->settings['__depth__']++;
			$children = $this->generate($item['children'], $this->settings);
			$this->settings['__depth__'] = $depth;
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

			$isSelected = $this->__isSelected($item);
		}

		if ($pos === 0) {
			$itemClass[] = $this->settings['firstClass'];
		}

		if ($isSelected) {
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

		if ($this->settings['element']) {
			$content = $this->_View->element($this->settings['element'], $elementData);
			$this->__tmp['regenerateCrumbs'] = true;
		} elseif ($this->settings['callback']) {
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

	private function __isSelected($item) {
		$isSelected = false;

		if (isset($item['link_path']) && !empty($item['link_path'])) {
			return false;
		}

		if (
			isset($item['selected_on']) && !empty($item['selected_on']) &&
			isset($item['selected_on_type']) && !empty($item['selected_on_type'])
		) {
			switch ($item['selected_on_type']) {
				case 'php':
					$isSelected = $this->php_eval($item['selected_on']) === true;
				break;

				case 'reg':
					$isSelected = QuickApps::urlMatch($item['selected_on'], '/' . $this->_View->request->url);
				break;
			}
		} elseif (
			isset($this->_View->viewVars['Layout']['node']['Node']['translation_of']) &&
			!empty($this->_View->viewVars['Layout']['node']['Node']['translation_of']) &&
			preg_match('/\/(.*)\.html$/', $item['url']) &&
			strpos($item['url'], "/{$this->_View->viewVars['Layout']['node']['Node']['translation_of']}.html") !== false
		) {
			$isSelected = true;
		} elseif (
			$this->settings['partialMatch'] &&
			in_array($item['url'], $this->__tmp['crumb_urls'])
		) {
			$isSelected = true;
		} elseif (
			QuickApps::is('view.frontpage') &&
			QuickApps::strip_language_prefix($item['url']) == '/'
		) {
			$isSelected = true;
		} else {
			$getURL = $this->__getUrl();

			if (isset($getURL[0]) && $getURL[0] == __t($item['url'])) {
				$isSelected = true;
			}
		}

		return $isSelected;
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
			$u = urldecode(QuickApps::strip_language_prefix($u));
		}

		$this->__tmp['__getUrl'] = $out;

		return $this->__tmp['__getUrl'];
	}
}