<?php
App::uses('JSMin', 'Vendor');

/**
 * Jquery UI Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Plugin.System
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class JqueryUI {
    static $loadedUI = array();
    static $loadedThemes = array();
    static $presets = array(
        // interactions
        'draggable' => array('ui.core', 'ui.widget', 'ui.mouse'),
        'droppable' => array('ui.core', 'ui.widget', 'ui.mouse', 'ui.raggable'),
        'resizable' => array('ui.core', 'ui.widget', 'ui.mouse'),
        'selectable' => array('ui.core', 'ui.widget', 'ui.mouse'),
        'sortable' => array('ui.core', 'ui.widget', 'ui.mouse'),
        // widgets
        'accordion' => array('ui.core', 'ui.widget', 'effects.core'),
        'autocomplete' => array('ui.core', 'ui.widget', 'ui.position'),
        'button' => array('ui.core', 'ui.widget'),
        'datepicker' => array('ui.core'),
        'dialog' => array('ui.core', 'ui.position', 'ui.widget', 'ui.mouse', 'ui.draggable', 'ui.resizable'),
        'progressbar' => array('ui.core', 'ui.widget'),
        'slider' => array('ui.core', 'ui.widget', 'ui.mouse'),
        'tabs' => array('ui.core', 'ui.widget')
    );

/**
 * Loads in stack all the specified Jquery UI JS files.
 *
 * If site's `js` folder (ROOT/webroot/js/) is writable all the files will be
 * combined and mergered using JSMin.
 * See the contents of the `System/webroot/js/ui` sub-directory for a list of available
 * files that may be included.
 *
 * The required ui.core file is automatically included,
 * as is effects.core if you include any effects files.
 *
 * You can load a preset, which include all the required js files for that preset.
 *
 * ###Example
 * {{{
 *  $this->JqueryUI->add('sortable');
 * }}}
 *
 * The above will load all the JS libraries required for a `sortable` interaction.
 *
 * {{{
 *  $this->JqueryUI->add('effects.blind', 'effects.fade');
 * }}}
 *
 * The above will load both `blind` & `fade` effects.
 *
 * @param array $files List of UI files to include
 * @param array $stack Reference to AppController::$Layout['javascripts']['file']
 * @return mixed
 *  TRUE if `all` was included.
 *  FALSE no files where included because they are already included or where not found.
 *  String HTML <script> tags on success.
 * @see JqueryUI::$presets
 */
    public function add($files = array(), &$stack) {
        if (in_array('all', self::$loadedUI)) {
            return true;
        }

        // no arguments -> load all
        if (empty($files)) {
            $files = array('all');
        }

        // load preset
        if (count($files) === 1 &&
            strpos($files[0], '.') === false &&
            isset(self::$presets[strtolower($files[0])])
        ) {
            $l = strtolower($files[0]);
            self::$loadedUI[] = $l;
            $files = self::$presets[$l];
            $files[] = 'ui.' . $l;
        }

        $m = implode('|', $files);

        // autoload missing effects.core
        if (strpos($m, 'effects.') !== false &&
            strpos($m, 'effects.core') === false &&
            !in_array('effects.core', self::$loadedUI) &&
            !in_array('all', $files)
        ) {
            array_unshift($files, 'effects.core');
        }

        // autoload missing ui.core
        if (strpos($m, 'ui.') !== false &&
            strpos($m, 'ui.core') === false &&
            !in_array('ui.core', self::$loadedUI) &&
            !in_array('all', $files)
        ) {
            array_unshift($files, 'ui.core');
        }

        $files = array_diff($files, self::$loadedUI);
        self::$loadedUI = Set::merge(self::$loadedUI, $files);
        $rootJS = ROOT . DS . 'webroot' . DS . 'js' . DS;

        if (!empty($files)) {
            if (is_writable($rootJS)) {
                $cache = '';
                $source = CakePlugin::path('System') . 'webroot' . DS . 'js' . DS . 'ui' . DS;

                foreach ($files as $file) {
                    if (file_exists("{$source}jquery.{$file}.min.js")) {
                        $cache .= file_get_contents("{$source}jquery.{$file}.min.js") . ";\n\n";
                    }
                }

                if (empty($cache)) {
                    false;
                }

                $cacheFile = 'jquery-ui-' . md5($cache) . '.js';

                if (!cache(str_replace(WWW_ROOT, '', JS) . $cacheFile, null, '+99 days', 'public')) {
                    cache(str_replace(WWW_ROOT, '', JS) . $cacheFile, JSMin::minify($cache), '+99 days', 'public');
                }

                $stack[] = "/js/{$cacheFile}";

                return '<script type="text/javascript" src="' . Router::url("/js/{$cacheFile}") . '"></script>';
            } else {
                $out = '';

                foreach ($files as $file) {
                    $stack[] = "/system/js/ui/jquery.{$file}.min.js";
                    $out .= '<script type="text/javascript" src="' . Router::url("/system/js/ui/jquery.{$file}.min.js") . '"></script>';
                }

                return $out;
            }
        }

        return false;
    }

/**
 * Loads in stack the CSS styles for the specified Jquery UI theme.
 *
 * Themes must be located under `/webroot/css/ui/` folder of you Module or Site.
 * Example, some valid routes are:
 *  - Core Module: ROOT/app/Plugin/System/webroot/css/ui/theme-name
 *  - Site Css: ROOT/webroot/css/ui/theme-name
 *  - Site Module: ROOT/Modules/MyModule/webroot/css/ui/theme-name
 *
 * Plugin-dot-syntax is allowed, for themes located in Module's css folder.
 * `Site Css` folder will be used otherwise.
 *
 * ###Theme auto-detect:
 * If no theme is given (FALSE):
 *  - Try to use global parametter `JqueryUI.default_theme`.
 *  - Use `System.ui-lightness` otherwise.
 *
 * ###Example:
 * {{{
 *  $this->JqueryUI->theme('MyModule.flick');
 * }}}
 * 
 * The above will load `flick` theme.
 * Theme should be located in `ROOT/Modules/MyModule/webroot/css/ui/flick/`
 *
 * {{{
 *  $this->JqueryUI->theme('flick');
 * }}} 
 *
 * The above will load `flick` theme. But, now it should be located in
 * `ROOT/webroot/css/ui/flick/`
 *
 * @param mixed $theme String name of the theme to load (Plugin-dot-syntax allowed)
 * or leave empty for auto-detect.
 * @param array $stack Reference to AppController::$Layout['stylesheets']['file']
 * @return mixed
 *  TRUE if theme has been already included.
 *  FALSE theme was not found.
 *  String HTML <style> tags on success.
 */
    public function theme($theme = false, &$stack) {
        if (!$theme) {
            if ($d = Configure::read('JqueryUI.default_theme')) {
                $theme = $d;
            } else {
                $theme = 'System.ui-lightness';
            }
        }

        list($plugin, $theme) = pluginSplit($theme);

        if (in_array($theme, self::$loadedThemes)) {
            return true;
        }

        if ($plugin) {
            $plugin = Inflector::camelize($plugin);
            $base = CakePlugin::path($plugin) . 'webroot' . DS . 'css' . DS . 'ui' . DS;
            $cssName = self::__themeCssFile($base . $theme);
            $path = $base . $theme . DS . $cssName;
            $plugin = "/{$plugin}";
        } else {
            $cssName = self::__themeCssFile(ROOT . DS . 'webroot' . DS . 'css' . DS . 'ui' . DS . $theme);
            $path = ROOT . DS . 'webroot' . DS . 'css' . DS . 'ui' . DS . $theme . DS . $cssName;
            $plugin = '';
        }

        if (file_exists($path)) {
            $stack[] = "{$plugin}/css/ui/{$theme}/{$cssName}";
            self::$loadedThemes = Set::merge(self::$loadedThemes, array($theme));

            return '<link rel="stylesheet" type="text/css" href="' . Router::url("{$plugin}/css/ui/{$theme}/{$cssName}") . '" media="all" />';
        }

        return false;
    }

    public function __themeCssFile($folder) {
        $f = new Folder($folder);
        $files = $f->read();

        if (isset($files[1][0])) {
            return $files[1][0];
        }
    }
}