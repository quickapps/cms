<?php
/**
 * Node Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeHelper extends AppHelper {
/**
 * TMP holder
 *
 * @var array
 */
	protected $_tmp = array();

/**
 * Returns specified node's field (from `node` table). Valid only when rendering a
 * single node (display = full) or after LayoutHelper::renderNode() is invoked.
 *
 * @param string $field Field name to retrieve. e.g.: `id` for Node's ID
 * @return mixed Array of the field if exists. FALSE otherwise
 */
	public function getAttr($field = false) {
		if (!is_string($field)) {
			return false;
		}

		if (isset($this->_View->viewVars['Layout']['node']['Node'][$field])) {
			return $this->_View->viewVars['Layout']['node']['Node'][$field];
		} elseif (isset($this->_tmp['render_node']['Node'][$field])) {
			return $this->_tmp['render_node']['Node'][$field];
		}

		return false;
	}

/**
 * Render the specified Node or `current` Node.
 * Node rendering hook is invoked based on NodeType, but if is there is no response
 * then default rendering proccess is fired.
 *
 * @param mixed $node Optional:
 *
 * - boolean FALSE: current node will be rendered. (by default)
 * - string SLUG: render node by node's slug.
 * - array : asociative Node's array to render.
 *
 * @param array $options Node rendering options:
 *
 * - mixed class: array or string, extra CSS class(es) for node DIV container
 * - mixed display: set to string value to force rendering display mode. set to boolean false for automatic.
 *
 * @return string HTML formatted node. Empty string will be returned if node could not be rendered.
 */	
	public function render($node = false, $options = array()) {
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

		$this->_tmp['render_node'] = $node;

		$content = '';
		$display_mode = $display !== false ? $display : $this->_View->viewVars['Layout']['display'];

		foreach ($node['Field'] as $key => &$data) {
			// undefined display -> use default
			if (!isset($data['settings']['display'][$display_mode]) && isset($data['settings']['display']['default'])) {
				$data['settings']['display'][$display_mode] = $data['settings']['display']['default'];
			}
		}

		$node['Field'] = Hash::sort($node['Field'], "{n}.settings.display.{$display_mode}.ordering", 'asc');
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
 * Returns node that is being rendered using NodeHelper::render().
 *
 * @return mixed array information of the Node. Or FALSE on failure
 */
	public function workingNode() {
		if (isset($this->_tmp['render_node'])) {
			return $this->_tmp['render_node'];
		}

		return false;
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

		if (isset($field['Field']) && is_array($field['Field'])) {
			$field = $field['Field'];
		}

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
}