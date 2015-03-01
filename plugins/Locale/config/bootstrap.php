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
use Cake\Core\Configure;
use Cake\I18n\I18n;

/**
 * Language in which QuickAppsCMS's core was written. This value
 * is commonly used as fallback language and should NEVER be changed!
 */
if (!defined('CORE_LOCALE')) {
    define('CORE_LOCALE', 'en-us');
}

if (!function_exists('language')) {
    /**
     * Retrieves information for current language.
     *
     * Useful when you need to read current language's code, language's direction,
     * etc. It will returns all the information if no `$key` is given.
     *
     * ### Usage:
     *
     * ```php
     * language('code');
     * // may return: en
     *
     * language();
     * // may return:
     * [
     *     'name' => 'English',
     *     'locale' => 'en-US',
     *     'code' => 'en',
     *     'country' => 'US',
     *     'direction' => 'ltr',
     *     'icon' => 'us.gif',
     * ]
     * ```
     *
     * Accepted keys are:
     *
     * - `name`: Language's name, e.g. `English`, `Spanish`, etc.
     * - `locale`: Full localized language's code, e.g. `en-US`, `es`, etc.
     * - `code`: Language's ISO 639-1 code, e.g. `en`, `es`, `fr`, etc. (lowercase)
     * - `country`: Language's country code, e.g. `US`, `ES`, `FR`, etc. (uppercase)
     * - `direction`: Language writing direction, possible values are "ltr" or "rtl".
     * - `icon`: Flag icon (it may be empty) e.g. `es.gif`, `es.gif`,
     *    icons files are located in Locale plugin's `/webroot/img/flags/` directory,
     *    to render an icon using HtmlHelper you should do as follow:
     *
     * ```php
     * <?php echo $this->Html->image('Locale.flags/' . language('icon')); ?>
     * ```
     *
     * @param string|null $key The key to read, or null to read the whole info
     * @return mixed
     */
    function language($key = null)
    {
        $code = I18n::locale();
        if ($key !== null) {
            return Configure::read("QuickApps.languages.{$code}.{$key}");
        }
        return Configure::read('QuickApps.languages.{$code}');
    }
}

if (!function_exists('stripLanguagePrefix')) {
    /**
     * Strips language prefix from the given URL.
     *
     * For instance, `/en-us/article/my-first-article.html` becomes
     * `/article/my-first-article.html`.
     *
     * @param string $url The URL
     * @return string New URL
     */
    function stripLanguagePrefix($url)
    {
        static $locales = null;
        static $localesPattern = null;

        if (!$locales) {
            $locales = array_keys(quickapps('languages'));
        }

        if (!$localesPattern) {
            $localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';
        }

        $url = preg_replace('/\/?' . $localesPattern . '\//', '/', $url);
        return $url;
    }
}
