<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' => array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
    define('THEMES', ROOT  . DS . 'Themes' . DS . 'Themed' . DS);
    App::uses('Spyc', 'vendors');
    App::uses('Folder', 'Utility');

/**
 * Load themes as plugin
 *
 */
    $__plugin_paths = Cache::read('plugin_paths');

    if (!$__plugin_paths) {
        $folder = new Folder;
        $folder->path = THEMES;

        // site themes
        $__themes = $folder->read();
        $__themes = $__themes[0];

        foreach ($__themes as $__tname) {
            $__plugin_paths[] = THEMES . $__tname . DS . 'app' . DS;
        }

        // core themes
        $folder->path = APP . 'View' . DS . 'Themed' . DS;
        $__themes = $folder->read();
        $__themes = $__themes[0];

        foreach ($__themes as $__tname) {
            $__plugin_paths[] =  APP . 'View' . DS . 'Themed' . DS . $__tname . DS . 'app' . DS;
        }

        $__plugin_paths[] = ROOT . DS . 'Modules' . DS;

        App::build(array('plugins' => $__plugin_paths));

        $plugins = App::objects('plugins', null, false);

        foreach ($plugins as $plugin) {
            CakePlugin::load($plugin, array('bootstrap' => true, 'routes' => true));

            $__ppath = CakePlugin::path($plugin);
            $__ppath = str_replace(DS . $plugin . DS, DS . $plugin . DS, $__ppath);

            if (file_exists($__ppath . 'Fields' . DS)) {
                $__plugin_paths[] = $__ppath . 'Fields' . DS;
            }
        }

        Cache::write('plugin_paths', $__plugin_paths);
        unset($__themes, $__tname, $folder);
    }

    App::build(
        array(
            'locales' => ROOT . DS . 'Locale' . DS,
            'views' => ROOT  . DS . 'Themes' . DS,
            'plugins' => $__plugin_paths,
            'Model/Behavior' => ROOT . DS . 'Hooks' . DS . 'Behavior' . DS,
            'View/Helper' => ROOT . DS . 'Hooks' . DS . 'Helper' . DS,
            'Controller/Component' => ROOT . DS . 'Hooks' . DS . 'Component' . DS
        )
    );

    $plugins = App::objects('plugins', null, false);

    foreach($plugins as $plugin) {
        if (!CakePlugin::loaded($plugin)) {
            CakePlugin::load($plugin, array('bootstrap' => true, 'routes' => true) );
        }
    }

    $__coreModulesCache = Cache::read('core_modules');
    $__coreThemesCache = Cache::read('core_themes');

    if (!$__coreModulesCache || !$__coreThemesCache) {
        $plugins = App::objects('plugins', null, false);
        $__coreThemes = $__coreModules = array();

        foreach($plugins as $plugin) {
            $__ppath = CakePlugin::path($plugin);

            if (strpos($__ppath, DS . 'Fields' . DS) !== false) {
                continue;
            }

            if (!$__coreModulesCache && isCoreModule($plugin)) {
                $__coreModules[] = $plugin;
            }

            if (!$__coreThemesCache && isCoreTheme($plugin)) {
                $__coreThemes[] = str_replace_once('Theme', '', $plugin);
            }
        }

        if (!$__coreModulesCache) {
            $__coreModulesCache = $__coreModules;
            Cache::write('core_modules', $__coreModules);
        }

        if (!$__coreThemesCache) {
            $__coreThemesCache = $__coreThemes;
            Cache::write('core_themes', $__coreThemes);
        }

        unset($__coreModules, $__coreThemes);
    }

    Configure::write('coreModules', $__coreModulesCache);
    Configure::write('coreThemes', $__coreThemesCache);

    unset($__plugin_paths, $plugins, $plugin, $__ppath, $__coreModulesCache, $__coreThemesCache);

/**
 * Check if the given theme name belongs to QA Core installation.
 *
 * @param string $theme Theme name to check.
 * @return bool TRUE if theme is a core theme, FALSE otherwise.
 */
    function isCoreTheme($theme) {
        $theme = Inflector::camelize($theme);
        $theme = strpos($theme, 'Theme') !== 0 ? "Theme{$theme}" : $theme;

        if (CakePlugin::loaded($theme)) {
            $app_path = CakePlugin::path($theme);

            if (strpos($app_path, APP . 'View' . DS . 'Themed' . DS) !== false) {
                return true;
            }
        }

        return false;
    }

/**
 * Check if the given module name belongs to QA Core installation.
 *
 * @param string $module Module name to check.
 * @return bool TRUE if module is a core module, FALSE otherwise.
 */
    function isCoreModule($module) {
        $module = Inflector::camelize($module);

        if (CakePlugin::loaded($module)) {
            $path = CakePlugin::path($module);

            if (strpos($path, APP . 'Plugin' . DS) !== false) {
                return true;
            }
        }

        return false;
    }

/**
 * Return only the methods for the indicated object.
 * It will strip out the inherited methods.
 *
 * @return array List of methods.
 */
    function get_this_class_methods($class) {
        $methods = array();
        $primary = get_class_methods($class);

        if ($parent = get_parent_class($class)) {
            $secondary = get_class_methods($parent);
            $methods = array_diff($primary, $secondary);
        } else {
            $methods = $primary;
        }

        return $methods;
    }

/**
 * Strip language prefix from the given URL.
 * e.g.: `http://site.com/eng/some-url` becomes http://site.com/some-url`
 *
 * @param string $url URL to replace.
 * @return string URL with no language prefix.
 */
    function strip_language_prefix($url) {
        $url = preg_replace('/\/[a-z]{3}\//', '/', $url);

        return $url;
    }

/**
 * Translation function, domain search order:
 * 1- Current plugin
 * 2- Default
 * 3- Translatable entries cache
 *
 * @param string $singular String to translate.
 * @return string The translated string.
 */
    function __t($singular, $args = null) {
        if (!$singular) {
            return;
        }

        App::uses('I18n', 'I18n');

        $route = Router::getParams();

        if (isset($route['plugin']) && !empty($route['plugin'])) {
            $translated = I18n::translate($singular, null, Inflector::underscore($route['plugin'])); # 1º look in plugin
        } else {
            $translated = $singular;
        }

        if ($translated === $singular) { # 2º look in default
            $translated = I18n::translate($singular, null, 'default');
        }

        if ($translated === $singular) { # 3º look in transtalion db-cache
            $cache = Cache::read(md5($singular) . '_' . Configure::read('Config.language'), 'i18n');
            $translated = $cache ? $cache: $singular;
        }

        if ($args === null) {
            return $translated;
        } elseif (!is_array($args)) {
            $args = array_slice(func_get_args(), 1);
        }

        return vsprintf($translated, $args);
    }

/**
 * Create Unique Arrays using an md5 hash
 *
 * @param array $array
 * @return array
 */
    function arrayUnique($array, $preserveKeys = false) {
        $arrayRewrite = array();
        $arrayHashes = array();

        foreach ($array as $key => $item) {
            $hash = md5(serialize($item));

            if (!isset($arrayHashes[$hash])) {
                $arrayHashes[$hash] = $hash;

                if ($preserveKeys) {
                    $arrayRewrite[$key] = $item;
                } else {
                    $arrayRewrite[] = $item;
                }
            }
        }

        return $arrayRewrite;
    }

/**
 * Replace the first ocurrence only.
 *
 * @param string $str_pattern What to find for.
 * @param string $str_replacement The replacement for $str_pattern.
 * @param string $string The original to find and replace.
 * @return string
 */
    function str_replace_once($str_pattern, $str_replacement, $string) {
        if (strpos($string, $str_pattern) !== false) {
            $occurrence = strpos($string, $str_pattern);
            return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
        }

        return $string;
    }

    include_once ROOT . DS . 'Config' . DS . 'bootstrap.php';