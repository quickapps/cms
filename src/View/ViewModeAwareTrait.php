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
namespace QuickApps\View;

use QuickApps\View\ViewModeRegistry;

/**
 * Provides methods for handling switching view mode.
 *
 */
trait ViewModeAwareTrait
{

    /**
     * Sets a view mode.
     *
     * @param string|null $slug Slug name of the view mode
     * @return void
     * @see \QuickApps\View\ViewModeRegistry::switchViewMode()
     */
    public function switchViewMode($slug)
    {
        return ViewModeRegistry::switchViewMode($slug);
    }

    /**
     * Registers a new view mode. Or overwrite if already exists.
     *
     * @param string|array $slug Slug name of your view mode. e.g.: `my-view mode`.
     * Or an array of view modes to register indexed by slug name
     * @param string|null $name Human readable name. e.g.: `My View Mode`
     * @param string|null $description A brief description about for what is this view mode
     * @return void
     * @see \QuickApps\View\ViewModeRegistry::addViewMode()
     */
    public static function addViewMode($slug, $name = null, $description = null)
    {
        return ViewModeRegistry::addViewMode($slug, $name, $description);
    }

    /**
     * Gets the slug name of in use view mode.
     *
     * @param bool $full Set to true to get full information as an array.
     *  Or set to false (by default) to get slug name only
     * @return string
     * @see \QuickApps\View\ViewModeRegistry::inUseViewMode()
     */
    public function inUseViewMode($full = false)
    {
        return ViewModeRegistry::inUseViewMode();
    }

    /**
     * Gets all registered view modes.
     *
     * @param bool $full Whether to return full information for each registered
     *  view mode, or just machine-names of each one. Defaults to false,
     *  machine-names only.
     * @return array
     * @see \QuickApps\View\ViewModeRegistry::viewModes()
     */
    public function viewModes($full = false)
    {
        return ViewModeRegistry::viewModes($full);
    }

    /**
     * Runs the given callable when the in-use view mode matches.
     *
     * You can provide multiple view modes, in that case callable method will be
     * executed if current view mode matches any in the given array.
     *
     * ### Usage
     *
     *     // run this only on `teaser` view mode
     *     echo $this->onViewMode('teaser', function () use ($someVar) {
     *         return $this->element('teaser_element', compact('someVar'));
     *     });
     *
     *     // run this on "teaser" view mode, or "search-result" view mode
     *     echo $this->onViewMode(['teaser', 'search-result'], function () use ($someVar) {
     *         return $this->element('teaser_or_search_result', compact('someVar'));
     *     });
     *
     * @param string|array $viewMode View Mode slug, or an array of slugs
     * @param callable $method A callable function to run, it receives `$this` as
     *  only argument
     * @return mixed Callable return
     */
    public function onViewMode($viewMode, callable $method)
    {
        $viewMode = !is_array($viewMode) ? [$viewMode] : $viewMode;
        if (in_array($this->inUseViewMode(), $viewMode)) {
            return $method();
        }
    }

    /**
     * Runs the given callable as it were under the given view mode.
     *
     * ### Usage
     *
     *     $this->switchMode('full');
     *     echo 'before: ' . $this->inUseViewMode();
     *     echo $this->asViewMode('teaser', function () {
     *         echo 'callable: ' . $this->inUseViewMode();
     *     });
     *     echo 'after: ' . $this->inUseViewMode();
     *
     *     // output:
     *     before: full
     *     callable: teaser
     *     after: full
     *
     * @param string|array $viewMode View Mode slug, or an array of slugs
     * @param callable $method A callable function to run, it receives `$this` as
     *  only argument
     * @return mixed Callable return
     */
    public function asViewMode($viewMode, callable $method)
    {
        $prevViewMode = $this->inUseViewMode();
        $this->switchViewMode($viewMode);
        $method();
        $this->switchViewMode($prevViewMode);
    }
}
