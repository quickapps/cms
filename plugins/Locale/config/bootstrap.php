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

/**
 * Retrieves information for current language.
 *
 * Useful when you need to read current language's code, direction, etc.
 * It will return all the information if no `$key` is given.
 *
 * ### Usage:
 *
 *     language('code');
 *     // may return: en-us
 *
 *     language();
 *     // may return:
 *     [
 *         'name' => 'English',
 *         'code' => 'en-us',
 *         'iso' => 'en',
 *         'country' => 'US',
 *         'direction' => 'ltr',
 *         'icon' => 'us.gif',
 *     ]
 *
 * Accepted keys are:
 *
 * - `name`: Language's name, e.g. `English`, `Spanish`, etc.
 * - `code`: Localized language's code, e.g. `en-us`, `es`, etc.
 * - `iso`: Language's ISO 639-1 code, e.g. `en`, `es`, `fr`, etc.
 * - `country`: Language's country code, e.g. `US`, `ES`, `FR`, etc.
 * - `direction`: Language writing direction, possible values are "ltr" or "rtl".
 * - `icon`: Flag icon (it may be empty) e.g. `es.gif`, `es.gif`,
 *    icons files are located in Locale plugin's `/webroot/img/flags/` directory,
 *    to render an icon using HtmlHelper you should do as follow:
 *
 *     <?php echo $this->Html->image('Locale.flags/' . language('icon')); ?>
 *
 * @param string|null $key The key to read, or null to read the whole info
 * @return mixed
 */
function language($key = null)
{
    $code = I18n::defaultLocale();
    if ($key !== null) {
        return Configure::read("QuickApps.languages.{$code}.{$key}");
    }
    return Configure::read('QuickApps.languages.{$code}');
}
