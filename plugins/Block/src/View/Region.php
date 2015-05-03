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
use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use QuickApps\Core\StaticCacheTrait;
use QuickApps\View\View;

/**
 * Region class.
 *
 * Represents a single region of a theme.
 */
class Region
{

    use StaticCacheTrait;

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
     * @var \QuickApps\Core\Package\PluginPackage
     */
    protected $_theme;

    /**
     * View instance.
     *
     * @var \QuickApps\View\View
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
     *
     * - `theme`: Name of the theme this regions belongs to. Defaults to auto-detect.
     *
     * @param \QuickApps\View\View $view Instance of View class to use
     * @param string $name Machine name of the region. e.g.: `left-sidebar`
     * @param array $options Options given as an array
     */
    public function __construct(View $view, $name, array $options = [])
    {
        $options += [
            'fixMissing' => null,
            'theme' => $view->theme,
        ];
        $this->_machineName = Inflector::slug($name, '-');
        $this->_View = $view;
        $this->_theme = plugin($options['theme']);

        if (isset($this->_theme->composer['extra']['regions'])) {
            $validRegions = array_keys($this->_theme->composer['extra']['regions']);
            $jsonPath = "{$this->_theme->path}/composer.json";
            $options['fixMissing'] = $options['fixMissing'] == null ? Configure::read('debug') : $options['fixMissing'];
            if (!in_array($this->_machineName, $validRegions) &&
                $options['fixMissing'] &&
                !$this->_theme->isCore &&
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

        $this->_prepareBlocks();
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
     * ```php
     * $theme = $this->region('left-sidebar')->theme();
     * ```
     *
     * @return QuickApps\Core\Package\PluginPackage
     */
    public function theme()
    {
        return $this->_theme;
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
     * Render all the blocks within this region.
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
            $html .= $this->_View->render($block, []);
            $i++;
        }
        return $html;
    }

    /**
     * Fetches all block entities that could be rendered within this region.
     *
     * @return void
     */
    protected function _prepareBlocks()
    {
        $cacheKey = "{$this->_View->theme}_{$this->_machineName}";
        $blocksCache = Cache::read($cacheKey, 'blocks');

        if (!$blocksCache) {
            $Blocks = TableRegistry::get('Block.Blocks');
            $blocks = $Blocks->find()
                ->contain(['Roles', 'BlockRegions'])
                ->matching('BlockRegions', function ($q) {
                    return $q->where([
                        'BlockRegions.theme' => $this->_View->theme,
                        'BlockRegions.region' => $this->_machineName,
                    ]);
                })
                ->where(['Blocks.status' => 1])
                ->order(['BlockRegions.ordering' => 'ASC'])
                ->all();

            $blocks->sortBy(function ($block) {
                return $block->region->ordering;
            }, SORT_ASC);

            Cache::write($cacheKey, $blocks->toArray(), 'blocks');
        } else {
            $blocks = new Collection($blocksCache);
        }

        // remove blocks that cannot be rendered based on current request.
        $blocks = $blocks->filter(function ($block) {
            return $this->_filterBlock($block) && $block->renderable();
        });

        $this->blocks($blocks);
    }

    /**
     * Checks if the given block can be rendered.
     *
     * @param \Block\Model\Entity\Block $block Block entity
     * @return bool True if can be rendered
     */
    protected function _filterBlock(Block $block)
    {
        $cacheKey = "allowed_{$block->id}";
        $cache = static::cache($cacheKey);

        if ($cache !== null) {
            return $cache;
        }

        if (!empty($block->locale) &&
            !in_array(I18n::locale(), (array)$block->locale)
        ) {
            return static::cache($cacheKey, false);
        }

        if (!$block->isAccessible()) {
            return static::cache($cacheKey, false);
        }

        $allowed = false;
        switch ($block->visibility) {
            case 'except':
                // Show on all pages except listed pages
                $allowed = !$this->_urlMatch($block->pages);
                break;
            case 'only':
                // Show only on listed pages
                $allowed = $this->_urlMatch($block->pages);
                break;
            case 'php':
                // Use custom PHP code to determine visibility
                $allowed = php_eval($block->pages, [
                    'view' => &$this->_View,
                    'block' => &$block
                ]) === true;
                break;
        }

        if (!$allowed) {
            return static::cache($cacheKey, false);
        }

        return static::cache($cacheKey, true);
    }

    /**
     * Check if a current URL matches any pattern in a set of patterns.
     *
     * @param string $patterns String containing a set of patterns
     * separated by \n, \r or \r\n
     * @return bool TRUE if the path matches a pattern, FALSE otherwise
     */
    protected function _urlMatch($patterns)
    {
        if (empty($patterns)) {
            return false;
        }

        $request = Router::getRequest();
        $path = str_starts_with($request->url, '/') ? str_replace_once('/', '', $request->url) : $request->url;

        if (option('url_locale_prefix')) {
            $patterns = explode("\n", $patterns);
            $locales = array_keys(quickapps('languages'));
            $localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';

            foreach ($patterns as &$p) {
                if (!preg_match("/^{$localesPattern}\//", $p)) {
                    $p = I18n::locale() . '/' . $p;
                    $p = str_replace('//', '/', $p);
                }
            }

            $patterns = implode("\n", $patterns);
        }

        // Convert path settings to a regular expression.
        // Therefore replace newlines with a logical or, /* with asterisks and  "/" with the front page.
        $toReplace = [
            '/(\r\n?|\n)/', // newlines
            '/\\\\\*/', // asterisks
            '/(^|\|)\/($|\|)/' // front '/'
        ];

        $replacements = [
            '|',
            '.*',
            '\1' . preg_quote(Router::url('/'), '/') . '\2'
        ];

        $patternsQuoted = preg_quote($patterns, '/');
        $patterns = '/^(' . preg_replace($toReplace, $replacements, $patternsQuoted) . ')$/';
        return (bool)preg_match($patterns, $path);
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
