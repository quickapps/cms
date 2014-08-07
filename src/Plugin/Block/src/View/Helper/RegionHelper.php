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
namespace Block\View\Helper;

use Block\Utility\Region;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use QuickApps\View\Helper\AppHelper;
use QuickApps\Utility\DetectorTrait;

/**
 * Region Factory Helper.
 *
 * For handling theme regions.
 *
 * ### Usage:
 *
 * Merge `left-sidebar` and `right-sidebar` regions together,
 * the resulting region limit the number of blocks it can holds to `3`:
 * 
 *     echo $this->Region
 *         ->create('left-sidebar')
 *         ->append($this->Region->create('right-sidebar'))
 *         ->blockLimit(3);
 */
class RegionHelper extends AppHelper {

	protected static $_regions = [];

/**
 * Defines a new region.
 *
 * @param string $region Region name
 * @return \Block\Utility\Region
 */
	public function create($region) {
		static::$_regions[$region] = new Region($region, $this->_View);
		return static::$_regions[$region];
	}

/**
 * Gets a previously created region.
 *
 * If requested region is bot found it will be automatically created.
 *
 * @param string $region Region name to get
 * @return \Block\Utility\Region
 */
	public function get($region) {
		if (isset(static::$_regions[$region])) {
			return static::$_regions[$region];
		}

		return $this->create($region);
	}

}
