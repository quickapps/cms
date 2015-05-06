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

/**
 * Page extension to use on every content's URL. Defaults to ".html". Content's URL
 * follows the pattern `/<content-type-slug>/<content-slug><content-extension>`. For
 * example, using ".html" as extension:
 *
 * ```
 * /article/my-first-article.html
 * ```
 *
 * Changing extension to "/" now you will get:
 *
 * ```
 * /article/my-first-article/
 * ```
 */
if (!defined('CONTENT_EXTENSION')) {
    define('CONTENT_EXTENSION', '.html');
}
