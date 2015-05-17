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
namespace CMS\View;

use CMS\View\ViewModeRegistry;

/**
 * Provides methods for handling switching view mode.
 *
 */
trait ViewModeAwareTrait
{

    /**
     * Sets a view mode or get current view mode.
     *
     * @param string|null $slug Slug name of the view mode
     * @return void
     * @see \CMS\View\ViewModeRegistry::uses()
     */
    public function viewMode($slug = null)
    {
        if ($slug === null) {
            return ViewModeRegistry::current();
        }

        return ViewModeRegistry::uses($slug);
    }

    /**
     * Registers a new view mode. Or overwrite if already exists.
     *
     * @param string|array $slug Slug name of your view mode. e.g.: `my-view mode`.
     *  Or an array of view modes to register indexed by slug name
     * @param string|null $name Human readable name. e.g.: `My View Mode`
     * @param string|null $description A brief description about for what is this view mode
     * @return void
     * @see \CMS\View\ViewModeRegistry::add()
     */
    public static function addViewMode($slug, $name = null, $description = null)
    {
        return ViewModeRegistry::add($slug, $name, $description);
    }

    /**
     * Gets the full list of all registered view modes, or for a single view mode
     * if $viewMode is set to a string value.
     *
     * @param bool|string $viewMode Set to true to get full list. Or false (by default) to
     *  get only the slug of all registered view modes. Or set to a string value to
     *  get information for that view mode only.
     * @return array
     * @see \CMS\View\ViewModeRegistry::modes()
     */
    public function viewModes($viewMode = false)
    {
        return ViewModeRegistry::modes($viewMode);
    }

    /**
     * Runs the given callable when the in-use view mode matches.
     *
     * You can provide multiple view modes, in that case callable method will be
     * executed if current view mode matches any in the given array.
     *
     * ### Usage
     *
     * ```php
     * // run this only on `teaser` view mode
     * echo $this->onViewMode('teaser', function () use ($someVar) {
     *     return $this->element('teaser_element', compact('someVar'));
     * });
     *
     * // run this on "teaser" view mode, or "search-result" view mode
     * echo $this->onViewMode(['teaser', 'search-result'], function () use ($someVar) {
     *     return $this->element('teaser_or_search_result', compact('someVar'));
     * });
     * ```
     *
     * @param string|array $viewMode View Mode slug, or an array of slugs
     * @param callable $method A callable function to run, it receives `$this` as
     *  only argument
     * @return mixed Callable return
     */
    public function onViewMode($viewMode, callable $method)
    {
        $viewMode = !is_array($viewMode) ? [$viewMode] : $viewMode;
        if (in_array($this->viewMode(), $viewMode)) {
            return $method();
        }
    }

    /**
     * Runs the given callable as it were under the given view mode.
     *
     * ### Usage
     *
     * ```php
     * $this->viewMode('full');
     * echo 'before: ' . $this->viewMode();
     *
     * echo $this->asViewMode('teaser', function () {
     *      echo 'callable: ' . $this->viewMode();
     * });
     *
     * echo 'after: ' . $this->viewMode();
     *
     * // output:
     * // before: full
     * // callable: teaser
     * // after: full
     * ```
     *
     * @param string|array $viewMode View Mode slug, or an array of slugs
     * @param callable $method A callable function to run, it receives `$this` as
     *  only argument
     * @return mixed Callable return
     */
    public function asViewMode($viewMode, callable $method)
    {
        $prevViewMode = $this->viewMode();
        $this->viewMode($viewMode);
        $method();
        $this->viewMode($prevViewMode);
    }
}
