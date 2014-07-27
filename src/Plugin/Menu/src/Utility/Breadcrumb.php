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
namespace Menu\Utility;

/**
 * Breadcrumb class.
 *
 * Breadcrumb indicate the current page's location within a navigational hierarchy.
 * This class is used as a registry and provides several methods for manage the crumbs stack.
 */
class Breadcrumb {

/**
 * Crumbs stack.
 *
 * @var array
 */
	protected static $_crumbs = [];

/**
 * Adds a new crumb to the stack.
 *
 * You can use this method without any argument, if you do it will automatically
 * try to guess the full breadcrumb path based on current URL (if current URL matches any URL
 * in any of your menus links).
 *
 * ### Usage
 *
 * #### Single crumb push as an array:
 *
 *     Breadcrumb::push(['title' => 'Crumb 1', 'url' => 'URL for crumb 1']);
 *     Breadcrumb::push(['title' => 'Crumb 2', 'url' => 'URL for crumb 2']);
 *     Breadcrumb::push(['title' => 'Crumb 3', 'url' => 'URL for crumb 3']);
 *
 * #### Multiple crumbs at once:
 *
 *     Breadcrumb::push([
 *         ['title' => 'Crumb 1', 'url' => 'URL for crumb 1'],
 *         ['title' => 'Crumb 2', 'url' => 'URL for crumb 2'],
 *         ['title' => 'Crumb 3', 'url' => 'URL for crumb 3'],
 *     ]);
 *
 * #### "title" and "URL" as arguments:
 *
 *     Breadcrumb::push('Crumb 1', 'URL for crumb 1');
 *     Breadcrumb::push('Crumb 2', 'URL for crumb 2');
 *     Breadcrumb::push('Crumb 3', 'URL for crumb 3');
 *
 * All three examples above produces the same HTML output when using `BreadcrumbHelper::render()`:
 *
 *     <ol>
 *         <li class="first-item"><a href="URL for crumb 1"><span>Crumb 1</span></a></li>
 *         <li class="active"><a href="URL for crumb 2"><span>Crumb 2</span></a></li>
 *         <li class="last-item"><a href="URL for crumb 3"><span>Crumb 3</span></a></li>
 *     </ol>
 *
 * @param array|string $crumbs Single crumb or an array of multiple crumbs to push at once
 * @param string $url If both $crumbs and $url are string values they will be used as `title` and `url` respectively
 * @return boolean True on success, False otherwise
 * @see \Menu\View\Helper\BreadcrumbHelper::render()
 */
	public static function push($crumbs = [], $url = '') {
		if (empty($crumbs)) {
			return false;
		}

		if (is_string($crumbs) && is_string($url)) {
			// "title" and "URL" as arguments"
			$crumbs = [
				['title' => $crumbs, 'url' => $url],
			];
		} elseif (is_array($crumbs) && isset($crumbs['title']) && isset($crumbs['url'])) {
			// Single crumb push as an array
			$crumbs = [$crumbs];
		}

		foreach ($crumbs as $crumb) {
			if (isset($crumb['title']) && isset($crumb['url'])) {
				static::$_crumbs[] = $crumb;
			}
		}

		return true;
	}

/**
 * Pops and returns the last crumb of the crumbs stack.
 *
 * @return array
 */
	public static function pop() {
		$crumb = array_pop(static::$_crumbs);
		return $crumb;
	}

/**
 * Clears the crumbs stack.
 *
 * @return void
 */
	public static function clear() {
		static::$_crumbs = [];
	}

/**
 * Counts the number of crumbs in the stack.
 *
 * @return integer
 */
	public static function count() {
		return count(static::$_crumbs);
	}

/**
 * Gets the full array stack of crumbs.
 *
 * @return array
 */
	public static function getStack() {
		return static::$_crumbs;
	}

/**
 * Gets a list of all URLs.
 *
 * @return array
 */
	public static function getUrls() {
		$urls = [];

		foreach (static::$_crumbs as $crumb) {
			if (!empty($crumb['url']) ) {
				$urls[] = $crumb['url'];
			} elseif (is_object($crumb)) {
				$urls[] = $crumb->url;
			}
		}

		return $urls;
	}

}
