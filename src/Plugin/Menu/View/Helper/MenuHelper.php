<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Menu\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\StringTemplateTrait;
use Cake\View\View;

/**
 * Menu factory.
 *
 */
class MenuHelper extends Helper {

	use StringTemplateTrait;

/**
 * Default config for this class.
 *
 * - itemCallable: Callable method used when formating each item.
 * - activeClass: CSS class to use when an item is active (its url matches current url).
 * - firstItemClass: CSS class for the first item.
 * - lastItemClass: CSS class for the last item.
 * - hasChildrenClass: CSS class to use when an item has children.
 * - split: Split menu into multiple root menus (multiple UL's)
 * - templates: HTML templates used when formating items.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'itemCallable' => null,
		'activeClass' => 'active',
		'firstClass' => 'first-item',
		'lastClass' => 'last-item',
		'hasChildrenClass' => 'has-children',
		'split' => false,
		'templates' => [
			'div' => '<div{{attrs}}>{{content}}</div>',
			'parent' => '<ul{{attrs}}>{{content}}</ul>',
			'child' => '<li{{attrs}}>{{content}}</li>',
			'link_label' => '<span{{attrs}}>{{content}}</span>',
			'link' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
		]
	];

/**
 * Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $config Configuration settings for the helper.
 */
	public function __construct(View $View, $config = array()) {
		$this->_defaultConfig['itemCallable'] = function ($entity, $info, $childContent) {
			return $this->formatItem($entity, $info, $childContent);
		};

		parent::__construct($View, $config);
	}

/**
 * Renders a nested menu.
 *
 * This methods renders a HTML menu using a `threaded` result set:
 *
 *     // In controller:
 *     $this->set('links', $this->Links->find('threaded'));
 *
 *     // In view:
 *     echo $this->Menu->render('links');
 *
 * ### Options:
 *
 * You can pass an associative array `key => value`.
 * Any `key` not in `$_defaultConfig` will be treated as an additional attribute for the top level UL (root).
 * If `key` is in `$_defaultConfig` it will overwrite default configuration parameters:
 *
 * - `itemCallable`: Callable method used when formating each item.
 * - `activeClass`: CSS class to use when an item is active (its url matches current url).
 * - `firstItemClass`: CSS class for the first item.
 * - `lastItemClass`: CSS class for the last item.
 * - `hasChildrenClass`: CSS class to use when an item has children.
 * - `templates`: The templates you want to use for this menu. Any templates will be merged on top of
 *    the already loaded templates. This option can either be a filename in App/Config that contains
 *    the templates you want to load, or an array of templates to use. You can use
 *    resetTemplates() to restore the original templates.
 *
 * @param \Cake\ORM\Query $items Nested items to render as menu
 * @param array $options An array of html attributes and options
 * @return string HTML
 */
	public function render($items, $options = []) {
		if (!empty($options['templates']) && is_array($options['templates'])) {
			$templater->add($options['templates']);
			unset($options['templates']);
		}

		$out = '';
		$attrs = [];

		foreach ($options as $key => $value) {
			if (isset($this->_defaultConfig[$key])) {
				$this->config($key, $value);
			} else {
				$attrs[$key] = $value;
			}
		}

		$config = $this->config();
		$this->countItems($items);

		if ($config['split'] > 1) {
			$arrayItems = $items->toArray();
			$count = count($arrayItems);
			$size = round($count / $config['split']);
			$chunk = array_chunk($arrayItems, $size);
			$i = 0;

			foreach ($chunk as $menu) {
				$i++;
				$out .=	$this->formatTemplate('parent', [
					'attrs' => $this->templater()->formatAttributes(['class' => 'menu-part part-' . $i]),
					'content' => $this->_render($menu, $config['itemCallable'])
				]);
			}

			$out = $this->formatTemplate('div', [
				'attrs' => $this->templater()->formatAttributes($attrs),
				'content' => $out,
			]);
		} else {
			$out .= $this->formatTemplate('parent', [
				'attrs' => $this->templater()->formatAttributes($attrs),
				'content' => $this->_render($items, $config['itemCallable'])
			]);
		}

		$this->_clear();

		return $out;
	}

/**
 * Default callable method (see itemCallable option).
 *
 * @param \Cake\ORM\Entity $item The item to render
 * @param array $info Array of useful information such as `index`, `total` and `depth`
 * @param string $childContent Inner HTML content for this item
 * @return string
 */
	public function formatItem($item, $info, $childContent) {
		$config = $this->config();
		$liAttrs = [];
		$linkAttrs = [];
		$labelAttrs = [];

		if ($info['index'] === 1) {
			$liAttrs['class'][] = $config['firstClass'];
		}

		if ($info['index'] === $info['total']) {
			$liAttrs['class'][] = $config['lastClass'];
		}

		if (!empty($childContent)) {
			$liAttrs['class'][] = $config['hasChildrenClass'];
		}

		switch ($item->selected_on_type) {
			case 'reg':
				if ($this->_urlMatch($item->selected_on)) {
					$liAttrs['class'][] = $config['activeClass'];
					$linkAttrs['class'] = $config['activeClass'];
					$labelAttrs['class'] = $config['activeClass'];
				}
			break;

			case 'php':
				if ($this->_phpEval($item->selected_on)) {
					$liAttrs['class'][] = $config['activeClass'];
					$linkAttrs['class'] = $config['activeClass'];
					$labelAttrs['class'] = $config['activeClass'];
				}
			break;

			default:
				$isInternal =
					$item->url !== '/' &&
					$item->url[0] === '/' &&
					strpos($item->url, $this->_View->request->url) !== false;
				$isIndex =
					$item->url === '/' &&
					$this->_View->is('page.index');
				$isExact =
					$item->url === $this->_View->request->url;

				if ($isInternal || $isIndex || $isExact) {
					$liAttrs['class'][] = $config['activeClass'];
					$linkAttrs['class'] = $config['activeClass'];
					$labelAttrs['class'] = $config['activeClass'];
				}
			break;
		}

		if (!empty($item->description)) {
			$linkAttrs['title'] = $item->description;
		}

		if (!empty($item->target)) {
			$linkAttrs['target'] = $item->target;
		}

		$liAttrs = $this->templater()->formatAttributes($liAttrs);
		$linkAttrs = $this->templater()->formatAttributes($linkAttrs);
		$labelAttrs = $this->templater()->formatAttributes($labelAttrs);

		return
			$this->formatTemplate('child', [
				'attrs' => $liAttrs,
				'content' => $this->formatTemplate('link', [
					'url' => $this->_View->Html->url($item->url, true),
					'attrs' => $linkAttrs,
					'content' => $this->formatTemplate('link_label', [
						'attrs' => $labelAttrs,
						'content' => $item->title,
					])
				]) . $childContent
			]);
	}

/**
 * Counts items in menu.
 *
 * @param \Cake\ORM\Query $items
 * @return integer
 */
	public function countItems($items) {
		if ($this->_count) {
			return $this->_count;
		}

		$this->_count($items);
		return $this->_count;
	}

/**
 * Restores the default values built into MenuHelper.
 *
 * @return void
 */
	public function resetTemplates() {
		$this->templates($this->_defaultConfig['templates']);
	}

/**
 * Internal method to recursively generate the menu.
 *
 * @param \Cake\ORM\Query $items
 * @param callable $itemCallable
 * @param integer $depth
 * @return string HTML
 */
	public function _render($items, $itemCallable, $depth = 0) {
		$content = '';

		foreach ($items as $item) {
			$childContent = '';

			if ($item->has('children') && !empty($item->children) && $item->expanded) {
				$childContent = $this->formatTemplate('parent', [
					'attrs' => '',
					'content' => $this->_render($item->children, $itemCallable, $depth + 1)
				]);
			}

			$this->_index++;
			$info = [
				'index' => $this->_index,
				'total' => $this->_count,
				'depth' => $depth,
			];
			$content .= $itemCallable($item, $info, $childContent);
		}

		return $content;
	}

/**
 * Evaluate a string of PHP code.
 *
 * This is a wrapper around PHP's eval(). It uses output buffering to capture both
 * returned and printed text. Unlike eval(), we require code to be surrounded by
 * <?php ?> tags; in other words, we evaluate the code as if it were a stand-alone
 * PHP file.
 *
 * Using this wrapper also ensures that the PHP code which is evaluated can not
 * overwrite any variables in the calling code, unlike a regular eval() call.
 *
 * @param string $code The code to evaluate
 * @return A string containing the printed output of the code,
 *    followed by the returned output of the code.
 */
	protected function _phpEval($code) {
		ob_start();

		$View =& $this->_View;
		print eval('?>' . $code);
		$output = ob_get_contents();
		ob_end_clean();

		return (bool)$output;
	}

/**
 * Check if a path matches any pattern in a set of patterns.
 *
 * @param string $patterns String containing a set of patterns separated by \n, \r or \r\n
 * @param mixed $path String as path to match. Or boolean FALSE to use actual page url
 * @return boolean TRUE if the path matches a pattern, FALSE otherwise
 */
	protected function _urlMatch($patterns, $path = false) {
		if (empty($patterns)) {
			return false;
		}

		$request = $this->_View->request;
		$path = !$path ? '/' . $request->url : $path;
		$patterns = explode("\n", $patterns);

		if (\Cake\Core\Configure::read('QuickApps.variables.url_language_prefix')) {
			if (!preg_match('/^\/([a-z]{3})\//', $path, $matches)) {
				$path = "/" . Configure::read('Config.language'). $path;
			}
		}

		foreach ($patterns as &$p) {
			$p = $this->_View->Html->url('/') . $p;
			$p = str_replace('//', '/', $p);
			$p = str_replace($request->base, '', $p);
		}

		$patterns = implode("\n", $patterns);

		// Convert path settings to a regular expression.
		// Therefore replace newlines with a logical or, /* with asterisks and the <front> with the frontpage.
		$to_replace = array(
			'/(\r\n?|\n)/', // newlines
			'/\\\\\*/',	 // asterisks
			'/(^|\|)\/($|\|)/' // front '/'
		);

		$replacements = array(
			'|',
			'.*',
			'\1' . preg_quote($this->_View->Html->url('/'), '/') . '\2'
		);

		$patterns_quoted = preg_quote($patterns, '/');
		$regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';

		return (bool) preg_match($regexps[$patterns], $path);
	}

/**
 * Internal method for counting items in menu.
 *
 * This method will ignore children if parent has been marked as `do no expand`.
 *
 * @param \Cake\ORM\Query $items
 * @return integer
 */
	protected function _count($items) {
		foreach ($items as $item) {
			$this->_count++;

			if ($item->has('children') && !empty($item->children) && $item->expanded) {
				$this->_count($item->children);
			}
		}
	}

/**
 * Clears all temporary variables used when rendering a menu,
 * so they do not interfere when rendering other menus.
 *
 * @return void
 */
	protected function _clear() {
		$this->_index = 0;
		$this->_count = 0;
	}

}
