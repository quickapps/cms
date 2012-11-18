<?php
/**
 * Block Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class BlockHelper extends AppHelper {
/**
 * TMP holder
 *
 * @var array
 */
	protected $_tmp = array();

/**
 * Manually insert a custom block to stack.
 *
 * @param array $block Formatted block array, possible keys:
 *
 * -	title: Block's title.
 * -	pages: List of paths on which to include/exclude the block or PHP code, 
 *		depending on `visibility` setting.
 * -	visibility: Flag to indicate how to show blocks on pages;
 *		- 0: Show on all pages except listed pages.
 *		- 1: Show only on listed pages.
 *		- 2: Use custom PHP code to determine visibility.
 * -	body: Block contents.
 * -	region: Theme region machine-name where to assign the block. e.g. 'sidebar-left'
 * -	theme: There machine-name where block belongs to. e.g. 'MyThemeName'
 * -	format: Not used.
 *
 * @param string $region Theme region where to push
 * @return boolean TRUE on success. FALSE otherwise
 */
	public function push($block = array(), $region = '') {
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
			$block['theme'] = QuickApps::themeName();
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
 * Returns the number of blocks in the specified region.
 *
 * @param string $region Region alias to count
 * @return integer Number of blocks
 */
	public function regionCount($region) {
		if (isset($this->_tmp['blocksInRegion'][$region]['blocks_ids'])) {
			return count($this->_tmp['blocksInRegion'][$region]['blocks_ids']);
		}

		$theme = QuickApps::themeName();
		$cache_key = Inflector::underscore("{$theme}_{$region}_") . Configure::read('Variable.language.code');
		$blocks = Cache::read('blocks_' . $cache_key);

		if (!$blocks) {
			$Block = ClassRegistry::init('Block.Block');
			$block_ids = $Block->BlockRegion->find('all',
				array(
					'conditions' => array(
						'BlockRegion.theme' => $theme,
						'BlockRegion.region' => $region
					),
					'fields' => array('id', 'block_id'),
					'recursive' => -1
				)
			);

			$options = array(
				'conditions' => array(
					// only blocks assigned to current theme
					'Block.id' => Hash::extract($block_ids, '{n}.BlockRegion.block_id'),
					'Block.themes_cache LIKE' => "%:{$theme}:%",
					'Block.status' => 1,
					'OR' => array(
						// only blocks assigned to any/current language
						'Block.locale =' => null,
						'Block.locale =' => '',
						'Block.locale LIKE' => '%s:3:"' . Configure::read('Variable.language.code') . '"%',
						'Block.locale' => 'a:0:{}'
					)
				),
				'recursive' => 2
			);

			$Block->Menu->unbindModel(array('hasMany' => array('Block')));
			$blocks = $Block->find('all', $options);

			Cache::write("blocks_{$cache_key}", $blocks);
		}

		if (!empty($this->_View->viewVars['Layout']['blocks'])) {
			$blocks = array_merge($blocks, $this->_View->viewVars['Layout']['blocks']);
		}

		$t = 0;
		$block_ids = @Hash::extract((array)$blocks, "{n}.BlockRegion.{n}[theme=" . QuickApps::themeName() . "][region={$region}].block_id"); // filter mergered

		foreach ($blocks as $block) {
			if (!in_array($block['Block']['id'], $block_ids) || !$this->__allowed($block)) {
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
	public function region($region) {
		if ($this->regionCount($region)) {
			$output = '';

			if (isset($this->_tmp['blocksInRegion'][$region]['blocks'])) {
				$blocks = $this->_tmp['blocksInRegion'][$region]['blocks'];
			} else {
				$blocks = array();
				$__blocks = $this->_View->viewVars['Layout']['blocks'];
				$block_ids = Hash::extract((array)$__blocks, "{n}.BlockRegion.{n}[theme=" . QuickApps::themeName() . "][region={$region}].block_id");

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
 * Render single block.
 * By default the following CSS classes may be applied to the block wrapper DIV element:
 *
 * -	qa-block: always applied.
 * -	qa-block-first: only to the first element of the region.
 * -	qa-block-last: only to the last element of the region.
 * -	qa-block-unique: to the block number 1/1 (unique) of the region.
 *
 * @param array $block Well formated block array.
 * @param array $options Array of options:
 *
 *	- boolean title: Render title. default true.
 *	- boolean body: Render body. default true.
 *	- string region: Region where block belongs to.
 *	- array params: extra options used by block.
 *	- array class: list of extra CSS classes for block wrapper.
 *
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

		if (!$this->__allowed($block)) {
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
			if ($this->_View->Layout->elementExists("{$block['Block']['module']}.{$block['Block']['delta']}_block")) {
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
				$Block['params'] = isset($block['Block']['params']) ? $block['Block']['params'] : array();
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
			$params = Hash::merge($Block['params'], $options['params']);
			$Block['params'] = $options['params'] = $params;
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
	private function __allowed($block) {
		if (!isset($block['__allowed'])) {
			if (isset($block['Block']['locale']) &&
				!empty($block['Block']['locale']) &&
				!in_array(Configure::read('Variable.language.code'), $block['Block']['locale'])
			) {
				return false;
			}

			if (!empty($block['Role'])) {
				$roles_id = Hash::extract($block, 'Role.{n}.id');
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