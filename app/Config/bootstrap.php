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
    App::uses('QuickApps', 'System.Lib');
    App::uses('HookCollection', 'System.Lib');

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

    App::build(
        array(
            'Controller' => ROOT . DS . 'SiteApp' . DS . 'Controller' . DS,
            'Controller/Component' => ROOT . DS . 'SiteApp' . DS . 'Controller' . DS . 'Component' . DS,
            'Model' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS,
            'Model/Behavior' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS . 'Behavior' . DS,
            'View' => ROOT . DS . 'SiteApp' . DS . 'View' . DS,
            'View/Helper' => ROOT . DS . 'SiteApp' . DS . 'View' . DS . 'Helper' . DS
        ),
    App::APPEND);

    $plugins = App::objects('plugins', null, false);

    if ($load_order = Cache::read('modules_load_order')) {
        $load_order = array_intersect($load_order, $plugins);
        $tail = array_diff($plugins, $load_order);
        $plugins = array_merge($load_order, $tail);
    }

    foreach($plugins as $plugin) {
        if (!CakePlugin::loaded($plugin)) {
            CakePlugin::load($plugin, array('bootstrap' => true, 'routes' => true));
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

            if (!$__coreModulesCache && QuickApps::is('module.core', $plugin)) {
                $__coreModules[] = $plugin;
            }

            if (!$__coreThemesCache && QuickApps::is('theme.core', $plugin)) {
                $__coreThemes[] = preg_replace('/^Theme/', '', $plugin);
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
 * Translation function.
 *
 * @param string $singular String to translate
 * @return string The translated string
 * @see QuickApps::__t()
 */
    function __t($singular, $args = null) {
        if (!is_array($args) && !is_null($args)) {
            $args = array_slice(func_get_args(), 1);
        }

        return QuickApps::__t($singular, $args);
    }

    include_once ROOT . DS . 'Config' . DS . 'bootstrap.php';