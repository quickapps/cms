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
use Cake\Network\Request;

/**
 * Attaches a few request-detectors to Request object.
 *
 * The following built-in detectors returns TRUE:
 *
 * - `home`: When the front page (a.k.a. home page) of the site is displayed.
 * - `dashboard`: When the dashboard section is being displayed.
 * - `admin`: When the dashboard or administration section is being displayed,
 *    that is anything within `/admin/*`
 * - `localized`: When current request's URL is language-prefixed. e.g. "/en-us/..."
 * - `userLoggedIn`: When user has logged in.
 * - `userAdmin`: When user has logged in and belongs to the "Administrator" group.
 */

/**
 * Checks if page being rendered is site's home page.
 *
 *     $request->isHome();
 */
Request::addDetector('home', function ($request) {
    return (
        !empty($request->params['plugin']) &&
        strtolower($request->params['plugin']) === 'node' &&
        !empty($request->params['controller']) &&
        strtolower($request->params['controller']) === 'serve' &&
        !empty($request->params['action']) &&
        strtolower($request->params['action']) === 'home'
    );
});

/**
 * Checks if page being rendered is the dashboard or administration section.
 *
 *     $request->isAdmin();
 */
Request::addDetector('admin', function ($request) {
    return (
        !empty($request->params['prefix']) &&
        $request->params['prefix'] === 'admin'
    );
});

/**
 * Checks if page being rendered is the dashboard.
 *
 *     $request->isDashboard();
 */
Request::addDetector('dashboard', function ($request) {
    return (
        !empty($request->params['plugin']) &&
        strtolower($request->params['plugin']) === 'system' &&
        !empty($request->params['controller']) &&
        strtolower($request->params['controller']) === 'dashboard' &&
        !empty($request->params['action']) &&
        strtolower($request->params['action']) === 'index'
    );
});

/**
 * Checks if current URL is language prefixed.
 *
 *     $request->isLocalized();
 */
Request::addDetector('localized', function ($request) {
    $locales = array_keys(quickapps('languages'));
    $localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';
    $url = str_starts_with($request->url, '/') ? str_replace_once('/', '', $request->url) : $request->url;
    return preg_match("/^{$localesPattern}\//", $url);
});

/**
 * Checks if visitor user is logged in.
 *
 *     $request->isUserLoggedIn();
 */
Request::addDetector('userLoggedIn', function ($request) {
    $sessionExists = $request->session()->check('Auth.User.id');
    $sessionContent = $request->session()->read('Auth.User.id');
    return ($sessionExists && !empty($sessionContent));
});

/**
 * Checks if visitor user is logged in and has administrator privileges.
 *
 *     $request->isUserAdmin();
 */
Request::addDetector('userAdmin', function ($request) {
    return in_array(ROLE_ID_ADMINISTRATOR, array_values(user()->role_ids));
});
