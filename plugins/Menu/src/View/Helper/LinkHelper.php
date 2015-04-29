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
namespace Menu\View\Helper;

use Cake\Datasource\EntityInterface;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\View\View;
use Menu\View\BreadcrumbRegistry;
use QuickApps\View\Helper;

/**
 * Link helper.
 *
 * Utility helper used by MenuHelper. Provides some utility methods for working with
 * menu links, such as `isActive()` method which check if the given link matches
 * current URL.
 */
class LinkHelper extends Helper
{

    /**
     * Default configuration for this class.
     *
     * - `breadcrumbGuessing`: Whether to mark an item as "active" if its URL is on
     *   the breadcrumb stack. Defaults to false
     *
     * @var array
     */
    protected $_defaultConfig = [
        'breadcrumbGuessing' => false,
    ];

    /**
     * Returns a safe URL string for later use with HtmlHelper.
     *
     * @param string|array $url URL given as string or an array compatible
     *  with `Router::url()`
     * @return string
     */
    public function url($url)
    {
        if (is_string($url)) {
            $url = $this->localePrefix($url);
        }

        try {
            $url = Router::url($url, true);
        } catch (\Exception $ex) {
            $url = '';
        }

        return $url;
    }

    /**
     * Checks if the given menu link should be marked as active.
     *
     * If `$item->activation` is a callable function it will be used to determinate
     * if the link should be active or not, returning true from callable indicates
     * link should be active, false indicates it should not be marked as active.
     * Callable receives current request object as first argument and $item as second.
     *
     * `$item->url` property MUST exists if "activation" is not a callable, and can
     * be either:
     *
     * - A string representing an external or internal URL (all internal links must
     *   starts with "/"). e.g. `/user/login`
     *
     * - An array compatible with \Cake\Routing\Router::url(). e.g. `['controller'
     *   => 'users', 'action' => 'login']`
     *
     * Both examples are equivalent.
     *
     * @param \Cake\Datasource\EntityInterface $item A menu's item
     * @return bool
     */
    public function isActive(EntityInterface $item)
    {
        if ($item->has('activation') && is_callable($item->get('activation'))) {
            $callable = $item->get('activation');
            return $callable($this->_View->request, $item);
        }

        $itemUrl = $this->sanitize($item->get('url'));
        if (!str_starts_with($itemUrl, '/')) {
            return false;
        }

        switch ($item->get('activation')) {
            case 'any':
                return $this->_requestMatches($item->get('active'));
            case 'none':
                return !$this->_requestMatches($item->get('active'));
            case 'php':
                return php_eval($item->get('active'), [
                    'view', &$this->_View,
                    'item', &$item,
                ]) === true;
            case 'auto':
            default:
                $isInternal =
                    $itemUrl !== '/' &&
                    str_ends_with($itemUrl, str_replace_once($this->baseUrl(), '', env('REQUEST_URI')));
                $isIndex =
                    $itemUrl === '/' &&
                    $this->_View->request->isHome();
                $isExact =
                    str_replace('//', '/', "{$itemUrl}/") === str_replace('//', '/', "/{$this->_View->request->url}/") ||
                    ($itemUrl == env('REQUEST_URI'));

                if ($this->config('breadcrumbGuessing')) {
                    return ($isInternal || $isIndex || $isExact || in_array($itemUrl, $this->_crumbs()));
                }

                return ($isInternal || $isIndex || $isExact);
        }
    }

    /**
     * Sanitizes the given URL by making sure it's suitable for menu links.
     *
     * @param string $url Item's URL to sanitize
     * @return string Valid URL, empty string on error
     */
    public function sanitize($url)
    {
        try {
            $url = Router::url($url);
        } catch (\Exception $ex) {
            return '';
        }

        if (!str_starts_with($url, '/')) {
            return $url;
        }

        if (str_starts_with($url, $this->baseUrl())) {
            $url = str_replace_once($this->baseUrl(), '', $url);
        }

        return $this->localePrefix($url);
    }

    /**
     * Prepends language code to the given URL if the "url_locale_prefix" directive
     * is enabled.
     *
     * @param string $url The URL to fix
     * @return string Locale prefixed URL
     */
    public function localePrefix($url)
    {
        if (option('url_locale_prefix') &&
            str_starts_with($url, '/') &&
            !preg_match('/^\/' . $this->_localesPattern() . '/', $url)
        ) {
            $url = '/' . I18n::locale() . $url;
        }
        return $url;
    }

    /**
     * Calculates site's base URL.
     *
     * @return string Site's base URL
     */
    public function baseUrl()
    {
        static $base = null;
        if ($base === null) {
            $base = $this->_View->request->base ? $this->_View->request->base : '/';
        }
        return $base;
    }

    /**
     * Gets a list of all URLs present in current crumbs stack.
     *
     * @return array List of URLs
     */
    protected function _crumbs()
    {
        static $crumbs = null;
        if ($crumbs === null) {
            $crumbs = BreadcrumbRegistry::getUrls();
            foreach ($crumbs as &$crumb) {
                $crumb = $this->sanitize($crumb);
            }
        }

        return $crumbs;
    }

    /**
     * Check if current request path matches any pattern in a set of patterns.
     *
     * @param string $patterns String containing a set of patterns separated by \n,
     *  \r or \r\n
     * @return bool TRUE if the path matches a pattern, FALSE otherwise
     */
    protected function _requestMatches($patterns)
    {
        if (empty($patterns)) {
            return false;
        }

        $request = $this->_View->request;
        $path = '/' . $request->url;
        $patterns = explode("\n", $patterns);

        foreach ($patterns as &$p) {
            $p = $this->_View->Url->build('/') . $p;
            $p = str_replace('//', '/', $p);
            $p = str_replace($request->base, '', $p);
            $p = $this->localePrefix($p);
        }

        $patterns = implode("\n", $patterns);

        // Convert path settings to a regular expression.
        // Therefore replace newlines with a logical or, /* with asterisks and "/" with the front page.
        $toReplace = [
            '/(\r\n?|\n)/', // newlines
            '/\\\\\*/', // asterisks
            '/(^|\|)\/($|\|)/' // front '/'
        ];

        $replacements = [
            '|',
            '.*',
            '\1' . preg_quote($this->_View->Url->build('/'), '/') . '\2'
        ];

        $patternsQuoted = preg_quote($patterns, '/');
        $patterns = '/^(' . preg_replace($toReplace, $replacements, $patternsQuoted) . ')$/';
        return (bool)preg_match($patterns, $path);
    }

    /**
     * Returns a regular expression that is used to verify if an URL starts
     * or not with a language prefix.
     *
     * ## Example:
     *
     * ```
     * (en\-us|fr|es|it)
     * ```
     *
     * @return string
     */
    protected function _localesPattern()
    {
        $cacheKey = '_localesPattern';
        $cache = static::cache($cacheKey);
        if ($cache) {
            return $cache;
        }

        $pattern = '(' . implode(
            '|',
            array_map(
                'preg_quote',
                array_keys(
                    quickapps('languages')
                )
            )
        ) . ')';
        return static::cache($cacheKey, $pattern);
    }
}
