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
namespace Block\View;

use Cake\Utility\Inflector;
use Cake\View\View;
use QuickApps\Core\Plugin;

/**
 * Region class.
 *
 * Represents a region of a theme.
 */
class Region {

/**
 * Name of this region.
 * 
 * @var string
 */
	protected $_name = null;

/**
 * Collection of blocks for this region.
 * 
 * @var \Cake\Collection\Iterator\FilterIterator
 */
	protected $_blocks = null;

/**
 * View instance.
 * 
 * @var \Cake\View\View
 */
	protected $_View = null;

/**
 * Maximum number of blocks in this region.
 *
 * @var null|integer
 */
	protected $_blockLimit = null;

/**
 * Information about the theme this region belongs to.
 *
 * @var array
 */
	protected $_theme;

/**
 * Constructor.
 *
 * ### Valid options are:
 *
 * - `fixMissing`: If set to TRUE when creating a region that is not defined by the theme,
 * it will try to fix it by adding it to theme's regions. Defaults to TRUE. This option
 * will alter theme's `composer.json` file.
 * - `theme`: Name of the theme this regions belongs to. Defaults to auto-detect.
 *
 * @param \Cake\View\View $view Instance of View class to use
 * @param string $name
 * @param array $options
 * @return void
 */
	public function __construct(View $view, $name, $options = []) {
		$options += [
			'fixMissing' => true,
			'theme' => $view->theme,
		];
		$this->_name = $name;
		$this->_View = $view;
		$this->_theme = Plugin::info($options['theme'], true);
		$this->_blocks = $this->_View->Region->Block->blocksIn($this->_name);

		if (isset($this->_theme['composer']['extra']['regions'])) {
			$validRegions = array_keys($this->_theme['composer']['extra']['regions']);
			$jsonPath = "{$this->_theme['path']}/composer.json";
			if (
				!in_array($this->_name, $validRegions) &&
				$options['fixMissing'] &&
				is_writable($jsonPath)
			) {
				$jsonArray = json_decode(file_get_contents($jsonPath), true);
				if (is_array($jsonArray)) {
					$machineName = Inflector::slug($this->_name, '-');
					$humanName = Inflector::humanize($this->_name);
					$jsonArray['extra']['regions'][$machineName] = $humanName;
					$encode = json_encode($jsonArray, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
					if ($encode) {
						file_put_contents($jsonPath, $encode);
					}
				}
				
			}
		}
	}

/**
 * Returns the name of this region.
 *
 * @return string
 */
	public function getName() {
		return $this->_name;
	}

/**
 * Returns information of the theme this regions belongs to.
 *
 * @return array
 */
	public function getTheme() {
		return $this->_theme;
	}

/**
 * Returns the block collection of this region.
 *
 * @return \Cake\Collection\Iterator\FilterIterator
 */
	public function getBlocks() {
		return $this->_blocks;
	}

/**
 * Limits the number of blocks in an area.
 *
 * Null means unlimited number.
 *  
 * @param null|integer $number Defaults to null
 * @return \Block\Utility\Region
 */
	public function blockLimit($number = null) {
		$number = $number !== null ? intval($number) : $number;
		$this->_blockLimit = $number;
		return $this;
	}

/**
 * Counts all the blocks within this region.
 * 
 * @return integer
 */
	public function countBlocks() {
		$blocks = $this->_View->Region->Block->blocksIn($this->_name);
		return count($blocks->toArray());
	}

/**
 * Appends blocks from another region.
 *
 * You can not merge regions with the same name.
 *
 * @param boolean $homogenize Set to true to make sure all blocks in the collection
 * are marked as they belongs to this region
 * @return \Block\Utility\Region This region with $region's blocks appended
 */
	public function append(Region $region, $homogenize = true) {
		if ($region->getName() != $this->_name) {
			$this->_blocks = $this->_blocks->append($region->getBlocks());
			if ($homogenize) {
				$this->_blocks = $this->_blocks->map(function ($block) {
					$block->block_regions->set('region', $this->_name);
					return $block;
				});
			}
		}

		return $this;
	}

/**
 * Render all blocks within this region.
 * 
 * @return string
 */
	public function render() {
		$html = '';
		$i = 0;

		foreach ($this->_blocks as $block) {
			if ($this->_blockLimit !== null && $i === $this->_blockLimit) {
				break;
			}
			$html .= $this->_View->Region->Block->render($block);
			$i++;
		}

		return $html;
	}

/**
 * Magic method for rendering this region.
 *
 *     echo $this->Region->create('left-sidebar');
 * 
 * @return string
 */
	public function __toString() {
		return $this->render();
	}

/**
 * Returns an array that can be used to describe the internal state of this
 * object.
 *
 * @return array
 */
	public function __debugInfo() {
		return [
			'_name' => $this->_name,
			'_blocks' => $this->_blocks->toArray(),
			'_blockLimit' => $this->_blockLimit,
			'_View' => '(object) \QuickApps\View\View',
		];
	}

}
