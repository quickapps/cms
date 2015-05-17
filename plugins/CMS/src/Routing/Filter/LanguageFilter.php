<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace CMS\Routing\Filter;

use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Filter\RoutingFilter;
use Cake\Routing\Router;

/**
 * A dispatcher filter that prepares the language code to be used.
 *
 * This filter MUST be used right after `Routing` filter or an exception will be
 * throw.
 */
class LanguageFilter extends RoutingFilter
{

    /**
     * Prepares the default language to use by the script.
     *
     * ### Detection Methods
     *
     * This method applies the following detection methods when looking for
     * language to use:
     *
     * - GET parameter: If `locale` GET parameter is present in current request, and
     *   if it's a valid language code, then will be used as current language and
     *   also will be persisted on `locale` session for further use.
     *
     * - URL: If current URL is prefixed with a valid language code and
     *   `url_locale_prefix` option is enabled, URL's language code will be used.
     *
     * - Locale session: If `locale` session exists it will be used.
     *
     * - User session: If user is logged in and has selected a valid preferred
     *   language it will be used.
     *
     * - Default: Site's language will be used otherwise.
     *
     * ### Locale Prefix
     *
     * If `url_locale_prefix` option is enabled, and current request's URL is not
     * language prefixed, user will be redirected to a locale-prefixed version of
     * the requested URL (using the language code selected as explained above).
     *
     * For example:
     *
     *     /article/demo-article.html
     *
     * Might redirects to:
     *
     *     /en_US/article/demo-article.html
     *
     * @param \Cake\Event\Event $event containing the request, response and
     *  additional parameters
     * @return void
     * @throws \Cake\Network\Exception\InternalErrorException When no valid request
     *  object could be found
     */
    public function beforeDispatch(Event $event)
    {
        parent::beforeDispatch($event);
        $request = Router::getRequest();
        if (empty($request)) {
            throw new InternalErrorException(__('No request object could be found.'));
        }

        $locales = array_keys(quickapps('languages'));
        $localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';
        $rawUrl = str_replace_once($request->base, '', env('REQUEST_URI'));
        $normalizedURL = str_replace('//', '/', "/{$rawUrl}");

        if (!empty($request->query['locale']) && in_array($request->query['locale'], $locales)) {
            $request->session()->write('locale', $request->query['locale']);
            I18n::locale($request->session()->read('locale'));
        } elseif (option('url_locale_prefix') && preg_match("/\/{$localesPattern}\//", $normalizedURL, $matches)) {
            I18n::locale($matches[1]);
        } elseif ($request->session()->check('locale') && in_array($request->session()->read('locale'), $locales)) {
            I18n::locale($request->session()->read('locale'));
        } elseif ($request->is('userLoggedIn') && in_array(user()->locale, $locales)) {
            I18n::locale(user()->locale);
        } elseif (in_array(option('default_language'), $locales)) {
            I18n::locale(option('default_language'));
        } else {
            I18n::locale(CORE_LOCALE);
        }

        if (option('url_locale_prefix') &&
            !$request->is('home') &&
            !preg_match("/\/{$localesPattern}\//", $normalizedURL)
        ) {
            $url = Router::url('/' . I18n::locale() . $normalizedURL, true);
            http_response_code(303);
            header("Location: {$url}");
            die;
        }
    }
}
