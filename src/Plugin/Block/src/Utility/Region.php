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
namespace Block\Utility;

use Cake\View\View;

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
 * Constructor.
 *
 * @param string $name
 * @param \Cake\View\View $view Instance of View class to use
 * @return void
 */
	public function __construct($name, View $view) {
		$this->_name = $name;
		$this->_View = $view;
		$this->_blocks = $this->_View->Block->blocksIn($this->_name);
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
 * Returns the block collection of this region.
 *
 * @return \Cake\Collection\Iterator\FilterIterator
 */
	public function getBlocks() {
		return $this->_blocks;
	}

/**
 * Appends blocks from another region.
 *
 * You can not merge region with the same name.
 *
 * @param boolean $homogenize Set to true to make sure all blocks in the collection
 * are marked as they belongs to this region.
 * @return \Block\Utility\Region This region with $region's blocks appended
 */
	public function append(Region $region, $homogenize = true) {
		if ($region->getName() != $this->_name) {
			$this->_blocks = $this->_blocks->append($region->getBlocks());
		}

		if ($homogenize) {
			$this->_blocks = $this->_blocks->map(function ($block) {
				$block->block_regions->set('region', $this->_name);
				return $block;
			});
		}

		return $this;
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

			$html .= $this->_View->Block->render($block);
			$i++;
		}

		return $html;
	}

/**
 * Counts all the blocks within this region.
 * 
 * @return integer
 */
	public function countBlocks() {
		$blocks = $this->_View->Block->blocksIn($this->_name);
		return count($blocks->toArray());
	}

/**
 * Magic method, render this region on ECHO.
 *
 *     echo $this->Region->create('left-sidebar');
 *     // or
 *     echo $this->Region->create('left-sidebar')->render();
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
