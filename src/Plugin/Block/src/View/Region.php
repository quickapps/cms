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
 * Name of this region. e.g.: 'left-sidebar'
 * 
 * @var string
 */
	protected $_machineName = null;

/**
 * Collection of blocks for this region.
 * 
 * @var \Cake\Collection\Iterator\FilterIterator
 */
	protected $_blocks = null;

/**
 * Maximum number of blocks this region can holds.
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
 * View instance.
 * 
 * @var \Cake\View\View
 */
	protected $_View = null;

/**
 * Constructor.
 *
 * ### Valid options are:
 *
 * - `fixMissing`: When creating a region that is not defined by the theme,
 *    it will try to fix it by adding it to theme's regions if this option is set to TRUE.
 *    Defaults to TRUE. This option will alter theme's `composer.json` file.
 * - `theme`: Name of the theme this regions belongs to. Defaults to auto-detect.
 *
 * @param \Cake\View\View $view Instance of View class to use
 * @param string $name Machine name of the region. e.g.: `left-sidebar`
 * @param array $options
 * @return void
 */
	public function __construct(View $view, $name, $options = []) {
		$options += [
			'fixMissing' => true,
			'theme' => $view->theme,
		];
		$this->_machineName = Inflector::slug($name, '-');
		$this->_View = $view;
		$this->_theme = Plugin::info($options['theme'], true);
		$this->_blocks = $this->_View->Region->Block->blocksIn($this->_machineName);

		if (isset($this->_theme['composer']['extra']['regions'])) {
			$validRegions = array_keys($this->_theme['composer']['extra']['regions']);
			$jsonPath = "{$this->_theme['path']}/composer.json";
			if (
				!in_array($this->_machineName, $validRegions) &&
				$options['fixMissing'] &&
				is_writable($jsonPath)
			) {
				$jsonArray = json_decode(file_get_contents($jsonPath), true);
				if (is_array($jsonArray)) {
					$humanName = Inflector::humanize($this->_machineName);
					$jsonArray['extra']['regions'][$this->_machineName] = $humanName;
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
		return $this->_machineName;
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
		$blocks = $this->_View->Region->Block->blocksIn($this->_machineName);
		return count($blocks->toArray());
	}

/**
 * Appends blocks from another region.
 *
 * You can not merge regions with the same machine-name.
 *
 * @param boolean $homogenize Set to true to make sure all blocks in the collection
 * are marked as they belongs to this region
 * @return \Block\Utility\Region This region with $region's blocks appended
 */
	public function append(Region $region, $homogenize = true) {
		if ($region->getName() != $this->_machineName) {
			$this->_blocks = $this->_blocks->append($region->getBlocks());
			if ($homogenize) {
				$this->_blocks = $this->_blocks->map(function ($block) {
					$block->region->set('region', $this->_machineName);
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
			'_machineName' => $this->_machineName,
			'_blocks' => $this->_blocks->toArray(),
			'_blockLimit' => $this->_blockLimit,
			'_theme' => $this->_theme,
			'_View' => '(object) \QuickApps\View\View',
		];
	}

}
