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
namespace Jquery\View\Helper;

use Cake\Core\Configure;
use QuickApps\View\Helper;

/**
 * jQuery Helper.
 *
 */
class JqueryHelper extends Helper
{

    /**
     * Loads jQuery's core library.
     *
     * ### Options
     *
     * - `block` Set to true to append output to view block "script" or provide
     *   custom block name.
     *
     * - `once` Whether or not the script should be checked for uniqueness. If true
     *   scripts will only be included once, use false to allow the same script to
     *   be included more than once per request.
     *
     * - `plugin` False value will prevent parsing path as a plugin.
     *
     * - `fullBase` If true the url will get a full address for the script file.
     *
     * @param array $options Array of options, and html attributes see above.
     * @return mixed String of `<script />` tags or null if block is specified in options
     *  or if $once is true and the file has been included before.
     */
    public function load($options = [])
    {
        if (Configure::read('debug')) {
            return $this->_View->Html->script('Jquery.jquery-1.11.2.js', $options);
        }
        return $this->_View->Html->script('Jquery.jquery-1.11.2.min.js', $options);
    }

    /**
     * Loads the given jQuery UI components JS files.
     *
     * You can indicate the name of the JS files to include as follow:
     *
     * ```php
     * $this->jQuery->ui('mouse', 'droppable', 'widget', ...);
     * ```
     *
     * You can provide an array of options for HtmlHelper::script() as follow:
     *
     * ```php
     * $this->jQuery->ui('mouse', 'droppable', ['block' => 'true'], 'widget', ...);
     * ```
     *
     * If no component is given all components (concatenated as a single JS file)
     * will be loaded at once.
     *
     * @return mixed String of `<script />` tags or null if block is specified in
     *  options or if $once is true and the file has been included before
     */
    public function ui()
    {
        $args = func_get_args();
        $files = [];
        $options = [];
        $out = '';

        foreach ($args as $file) {
            if (is_array($file)) {
                $options = $file;
                continue;
            }

            $file = 'Jquery.ui/' . strtolower($file);
            if (!str_ends_with($file, '.js')) {
                $file .= '.js';
            }

            if ($file != 'Jquery.ui/core.js') {
                $files[] = $file;
            }
        }

        if (empty($files)) {
            $files[] = Configure::read('debug') ? 'Jquery.jquery-ui.js' : 'Jquery.jquery-ui.min.js';
        } else {
            array_unshift($files, 'Jquery.ui/core.js');
        }

        foreach ($files as $file) {
            $out .= (string)$this->_View->Html->script($file, $options);
        }

        if (empty($out)) {
            return null;
        }

        return $out;
    }

    /**
     * Loads all CSS and JS files for the given UI theme.
     *
     * ### Usage
     *
     * You can indicate UI themes provided by an specific plugin:
     *
     * ```php
     * $this->jQuery->theme('MyPlugin.flick');
     * ```
     *
     * Theme's assets should be located at `MyPlugin/webroot/css/ui/flick/`
     *
     * If no plugin syntax is given, **Jquery** plugin will be used by default:
     *
     * ```php
     * $this->jQuery->theme('flick');
     * ```
     *
     * Theme's assets are located at `Jquery/webroot/css/ui/flick/`
     *
     * ### Theme auto-detect
     *
     * If no theme is given ($themeName = null) this method will try to:
     *
     * - Use global parameter `jQueryUI.default_theme`.
     * - Use `Jquery.ui-lightness` otherwise.
     *
     * ### Default theme
     *
     * You can define the global parameter `jQueryUI.default_theme` in your site's
     * `bootstrap.php` to indicate the theme to use by default. For instance:
     *
     * ```php
     * Configure::write('jQueryUI.default_theme', 'MyPlugin.ui-darkness');
     * ```
     *
     * The `MyPlugin.ui-darkness` theme will be used by default every time this
     * method is used with no arguments:
     *
     * ```php
     * $this->jQuery->theme();
     * ```
     *
     * Theme's assets should be located at `MyPlugin/webroot/css/ui/ui-darkness/`
     *
     * ### Options
     *
     * - `block` Set to true to append output to view block "css" or provide
     *   custom block name.
     *
     * - `once` Whether or not the css file should be checked for uniqueness. If
     *   true css files  will only be included once, use false to allow the same
     *   css to be included more than once per request.
     *
     * - `plugin` False value will prevent parsing path as a plugin.
     *
     * - `rel` Defaults to 'stylesheet'. If equal to 'import' the stylesheet will be
     *   imported.
     *
     * - `fullBase` If true the URL will get a full address for the css file.
     *
     * @param string|null $themeName Name of the theme to load
     * @param array $options Array of options and HTML arguments
     * @return string CSS <link /> or <style /> tag, depending on the type of link.
     */
    public function theme($themeName = null, $options = [])
    {
        if ($themeName === null) {
            $default = Configure::read('jQueryUI.defaultTheme');
            $themeName = $default ?: 'Jquery.ui-lightness';
        }

        $out = '';
        list($plugin, $theme) = pluginSplit($themeName);
        $plugin = !$plugin ? 'Jquery' : $plugin;
        $out .= (string)$this->_View->Html->css("{$plugin}.ui/{$theme}/theme.css", $options);

        if (Configure::read('debug')) {
            $out .= (string)$this->_View->Html->css("{$plugin}.ui/{$theme}/jquery-ui.css", $options);
        } else {
            $out .= (string)$this->_View->Html->css("{$plugin}.ui/{$theme}/jquery-ui.min.css", $options);
        }

        if (empty($out)) {
            return;
        }

        return $out;
    }
}
