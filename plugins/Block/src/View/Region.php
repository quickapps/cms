<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Block\View;

use Block\Model\Entity\Block;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\View\View;
use QuickApps\Core\Plugin;

/**
 * Region class.
 *
 * Represents a single region of a theme.
 */
class Region
{

    /**
     * machine name of this region. e.g. 'left-sidebar'
     *
     * @var string
     */
    protected $_machineName = null;

    /**
     * Collection of blocks for this region.
     *
     * @var \Cake\Collection\Collection
     */
    protected $_blocks = null;

    /**
     * Maximum number of blocks this region can holds.
     *
     * @var null|int
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
     * - `fixMissing`: When creating a region that is not defined by the theme, it
     *    will try to fix it by adding it to theme's regions if this option is set
     *    to TRUE. Defaults to NULL which automatically enables when `debug` is
     *    enabled. This option will not work when using QuickAppsCMS's core themes.
     *    (NOTE: This option will alter theme's `composer.json` file)
     * - `theme`: Name of the theme this regions belongs to. Defaults to auto-detect.
     *
     * @param \Cake\View\View $view Instance of View class to use
     * @param string $name Machine name of the region. e.g.: `left-sidebar`
     * @param array $options Options given as an array
     * @return void
     */
    public function __construct(View $view, $name, array $options = [])
    {
        $options += [
            'fixMissing' => null,
            'theme' => $view->theme,
        ];
        $this->_machineName = Inflector::slug($name, '-');
        $this->_View = $view;
        $this->_theme = Plugin::info($options['theme'], true);
        $this->_View->loadHelper('Block.Block');
        $this->blocks($this->_View->Block->blocksIn($this->_machineName));

        if (isset($this->_theme['composer']['extra']['regions'])) {
            $validRegions = array_keys($this->_theme['composer']['extra']['regions']);
            $jsonPath = "{$this->_theme['path']}/composer.json";
            $options['fixMissing'] = $options['fixMissing'] == null ? Configure::read('debug') : $options['fixMissing'];
            if (!in_array($this->_machineName, $validRegions) &&
                $options['fixMissing'] &&
                !$this->_theme['isCore'] &&
                is_writable($jsonPath)
            ) {
                $jsonArray = json_decode(file_get_contents($jsonPath), true);
                if (is_array($jsonArray)) {
                    $humanName = Inflector::humanize(str_replace('-', '_', $this->_machineName));
                    $jsonArray['extra']['regions'][$this->_machineName] = $humanName;
                    $encode = json_encode($jsonArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
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
    public function name()
    {
        return $this->_machineName;
    }

    /**
     * Returns information of the theme this regions belongs to.
     *
     * ### Usage:
     *
     *     // full info:
     *     $theme = $this->region('left-sidebar')
     *         ->theme();
     *
     *     // gets theme's "composer.json" info as an array
     *     $themeAuthor = $this->region('left-sidebar')
     *         ->theme('composer.author');
     *
     * @param null|string $path If set to a string value, it will extract
     *  the specified value from the theme's info-array. Null (by default)
     *  returns the whole info-array.
     * @return mixed
     */
    public function theme($path = null)
    {
        if ($path == null) {
            return $this->_theme;
        }
        return Hash::get($this->_theme, $path);
    }

    /**
     * Gets or sets the block collection of this region.
     *
     * When passing a collection of blocks as first argument, all blocks in the
     * collection will be homogenized, see homogenize() for details.
     *
     * @param \Cake\Collection\Collection $blocks Blocks collection if you want to
     *  overwrite current collection, leave empty to return current collection
     * @return \Cake\Collection\Collection
     * @see \Block\View\Region::homogenize()
     */
    public function blocks(Collection $blocks = null)
    {
        if ($blocks) {
            $this->_blocks = $blocks;
            $this->homogenize();
        }
        return $this->_blocks;
    }

    /**
     * Counts the number of blocks within this region.
     *
     * @return int
     */
    public function count()
    {
        return count($this->blocks()->toArray());
    }

    /**
     * Limits the number of blocks in this region.
     *
     * Null means unlimited number.
     *
     * @param null|int $number Defaults to null
     * @return \Block\View\Region
     */
    public function blockLimit($number = null)
    {
        $number = $number !== null ? intval($number) : $number;
        $this->_blockLimit = $number;
        return $this;
    }

    /**
     * Merge blocks from another region.
     *
     * You can not merge regions with the same machine-name, new blocks are appended
     * to this region.
     *
     * @param \Block\View\Region $region Region to merge with
     * @param bool $homogenize Set to true to make sure all blocks in the
     *  collection are marked as they belongs to this region
     * @return \Block\View\Region This region with $region's blocks appended
     */
    public function merge(Region $region, $homogenize = true)
    {
        if ($region->name() !== $this->name()) {
            $blocks1 = $this->blocks();
            $blocks2 = $region->blocks();
            $combined = $blocks1->append($blocks2)->toArray(false);
            $this->blocks(collection($combined));

            if ($homogenize) {
                $this->homogenize();
            }
        }
        return $this;
    }

    /**
     * Makes sure that every block in this region is actually marked as it belongs
     * to this region.
     *
     * Used when merging blocks from another region.
     *
     * @return \Block\View\Region This region with homogenized blocks
     */
    public function homogenize()
    {
        $this->_blocks = $this->blocks()->map(function ($block) {
            $block->region->set('region', $this->_machineName);
            return $block;
        });
        return $this;
    }

    /**
     * Render all blocks within this region.
     *
     * @return string
     */
    public function render()
    {
        $html = '';
        $i = 0;
        foreach ($this->blocks() as $block) {
            if ($this->_blockLimit !== null && $i === $this->_blockLimit) {
                break;
            }
            $html .= $this->_View->Block->render($block);
            $i++;
        }
        return $html;
    }

    /**
     * Magic method for rendering this region.
     *
     *     echo $this->region('left-sidebar');
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Returns an array that can be used to describe the internal state of
     * this object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            '_machineName' => $this->_machineName,
            '_blocks' => $this->blocks()->toArray(),
            '_blockLimit' => $this->_blockLimit,
            '_theme' => $this->_theme,
            '_View' => '(object) \QuickApps\View\View',
        ];
    }
}
