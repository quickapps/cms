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

use Block\View\Region;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use QuickApps\View\Helper;

/**
 * Region Factory Helper.
 *
 * For handling theme's regions.
 *
 * ### Usage:
 *
 * Merge `left-sidebar` and `right-sidebar` regions together, the resulting region
 * limits the number of blocks it can holds to `3`:
 * 
 *     echo $this->Region
 *         ->create('left-sidebar')
 *         ->append($this->Region->create('right-sidebar'))
 *         ->blockLimit(3);
 *
 * You can define all regions on top of your theme's layout and render them later:
 *
 *     <?php
 *         $this->Region->create('site-footer')
 *             ->append($this->Region->create('main-menu'))
 *             ->append($this->Region->create('user-menu'));
 *     ?>
 *     <!DOCTYPE html>
 *     <html>
 *         ...
 *         <body>
 *             ...
 *             <?php echo $this->Region->create('right-sidebar'); ?>
 *             ... 
 *             <footer><?php echo $this->Region->get('site-footer'); ?></footer>
 */
class RegionHelper extends Helper {

/**
 * Holds all region instances created for later access.
 * 
 * @var array
 */
	protected static $_regions = [];

/**
 * An array containing the names of helpers this helper uses.
 *
 * @var array
 */
	public $helpers = ['Block.Block'];

/**
 * Defines a new region.
 *
 * ### Valid options are:
 *
 * - `fixMissing`: When creating a region that is not defined by the theme, it
 *    will try to fix it by adding it to theme's regions if this option is set
 *    to TRUE. Defaults to NULL which automatically enables when `debug` is
 *    enabled. This option will not work when using QuickAppsCMS's core themes.
 *    (NOTE: This option will alter theme's `composer.json` file)
 * - `theme`: Name of the theme this regions belongs to. Defaults to auto-detect.
 *
 * @param string $region Region name
 * @param array $options Array of options described above
 * @return \Block\View\Region
 */
	public function create($region, array $options = []) {
		$this->alter('RegionHelper.create', $region, $options);
		static::$_regions[$region] = new Region($this->_View, $region, $options);
		return static::$_regions[$region];
	}

/**
 * Gets a previously created region.
 *
 * If requested region is not found it will be automatically
 * created and returned.
 *
 * @param string $region Region name to get
 * @return \Block\View\Region
 */
	public function get($region) {
		$this->alter('RegionHelper.get', $region);
		if (isset(static::$_regions[$region])) {
			return static::$_regions[$region];
		}
		return $this->create($region);
	}

}
