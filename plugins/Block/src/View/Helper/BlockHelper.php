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
namespace Block\View\Helper;

use Block\Model\Entity\Block;
use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\I18n\I18n;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use QuickApps\View\Helper;

/**
 * Block Helper.
 *
 * For handling block rendering.
 */
class BlockHelper extends Helper
{

    /**
     * Render all blocks for a particular region.
     *
     * @param string $region Region alias to render
     * @return string HTML blocks
     */
    public function region($region)
    {
        $this->alter(['BlockHelper.region', $this->_View], $region);
        $html = '';
        $blocks = $this->blocksIn($region);

        foreach ($blocks as $block) {
            $html .= $this->render($block);
        }

        return $html;
    }

    /**
     * Renders a single block.
     *
     * @param \Block\Model\Entity\Block $block Block entity to render
     * @param array $options Array of options
     * @return string HTML
     */
    public function render(Block $block, array $options = [])
    {
        $this->alter(['BlockHelper.render', $this->_View], $block, $options);
        if ($this->allowed($block)) {
            return $this->trigger(["Block.{$block->handler}.display", $this->_View], $block, $options)->result;
        }
        return '';
    }

    /**
     * Returns a list of block entities within the given region.
     *
     * @param string $region Region's machine-name
     * @param bool $all True will return the whole list, that is including blocks
     *  that will never be rendered because of its visibility; language or role
     *  restrictions, etc. Set to false (by default) will return only blocks
     *  that can be rendered.
     * @return \Cake\Collection\Collection
     */
    public function blocksIn($region, $all = false)
    {
        $cacheKey = $all ? "{$this->_View->theme}_{$region}_all" : "{$this->_View->theme}_{$region}";
        $blocksCache = Cache::read($cacheKey, 'blocks');

        if (!$blocksCache) {
            $Blocks = TableRegistry::get('Block.Blocks');
            $blocks = $Blocks->find()
                ->contain(['Roles', 'BlockRegions'])
                ->matching('BlockRegions', function ($q) use ($region) {
                    return $q->where([
                        'BlockRegions.theme' => $this->_View->theme,
                        'BlockRegions.region' => $region,
                    ]);
                })
                ->where(['Blocks.status' => 1])
                ->all()
                ->filter(function ($block) {
                    // we have to remove all blocks that belongs to a disabled plugin
                    if ($block->handler === 'Block') {
                        return true;
                    }
                    foreach ($this->_listeners() as $listener) {
                        if (str_starts_with($listener, "Block.{$block->handler}")) {
                            return true;
                        }
                    }
                    return false;
                });

            if (!$all) {
                $blocks->filter(function ($block) {
                    // we do a second pass to remove blocks that will never be rendered
                    return $this->allowed($block);
                });
            }

            $blocks->sortBy(function ($block) {
                return $block->region->ordering;
            }, SORT_ASC);

            Cache::write($cacheKey, $blocks->toArray(), 'blocks');
        } else {
            $blocks = new Collection($blocksCache);
        }

        return $blocks;
    }

    /**
     * Checks if the given block can be rendered.
     *
     * @param \Block\Model\Entity\Block $block Block entity
     * @return bool
     */
    public function allowed(Block $block)
    {
        $this->alter(['BlockHelper.allowed', $this->_View], $block);
        $cacheKey = "allowed_{$block->id}";
        $cache = static::cache($cacheKey);

        if ($cache !== null) {
            return $cache;
        }

        if (!empty($block->locale) &&
            !in_array(I18n::defaultLocale(), (array)$block->locale)
        ) {
            return static::cache($cacheKey, false);
        }

        if ($block->has('roles') && !empty($block->roles)) {
            $rolesIds = [];
            $userRoles = user()->roles;
            $allowed = false;
            foreach ($block->roles as $role) {
                $rolesIds[] = $role->id;
            }
            foreach ($userRoles as $role) {
                if (in_array($role, $rolesIds)) {
                    $allowed = true;
                    break;
                }
            }
            if (!$allowed) {
                return static::cache($cacheKey, false);
            }
        }

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
     * Returns all listeners that starts with `Block.`
     *
     * @return array
     */
    protected function _listeners()
    {
        $cacheKey = '_listeners';
        $cache = static::cache($cacheKey);

        if (!$cache) {
            $cache = [];
            foreach (listeners() as $listener) {
                if (str_starts_with($listener, 'Block.')) {
                    $cache[] = $listener;
                }
            }
            static::cache($cacheKey, $cache);
        }

        return $cache;
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
                    $p = I18n::defaultLocale() . '/' . $p;
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
        $regexps[$patterns] = '/^(' . preg_replace($toReplace, $replacements, $patternsQuoted) . ')$/';
        return (bool)preg_match($regexps[$patterns], $path);
    }
}
