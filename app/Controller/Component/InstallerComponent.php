<?php
/**
 * Installer Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class InstallerComponent extends Component {
    public $errors = array();
    public $Controller;
    public $options = array(
        'name' => null,     # used while deleting
        'type' => 'module', # type of package to install
        'status' => 1       # install and activate(status=1), install and do not activate (status=0)
    );

    public function startup() {}
    public function beforeRender() {}
    public function shutdown() {}
    public function beforeRedirect() {}

    public function initialize(&$Controller) {
        $this->Controller =& $Controller;

        return true;
    }

/**
 * Begin instalation proccess for the indicated package.
 * Expected module package estructure:
 *      - ModuleFolderName/   # $package_path
 *          - Config/
 *          - Controller/
 *              - Component/
 *                  - InstallComponent.php
 *          - Lib/
 *          - Locale/
 *          - Model/
 *          - View/
 *          - webroot/
 *          - ModuleFolderName.yaml
 *
 * Expected theme package estructure:
 *      - CamelCaseThemeName/   # $package_path
 *          - Layouts/
 *          - app/
 *              - ThemeCamelCaseThemeName/  # prefix 'Theme' + {camelized theme name}
 *          - webroot/
 *          - CamelCaseThemeName.yaml
 *          - thumbnail.png # 206x150px
 *
 *
 * @param array $data form POST submit of the .app package ($this->data)
 * @param array $options optional settings, see InstallerComponent::$options
 * @return bool true on success or false otherwise
 */
    public function install($data = false, $options = array()) {
        if (!$data) {
            return false;
        }

        $oldMask = umask(0);
        $this->options = array_merge($this->options, $options);
        $ext = strtolower(strrchr($data['Package']['data']['name'], '.'));

        if ($ext !== '.app') {
            $this->errors[] = __d('system', 'Invalid package extension. Got `%s`, `.app` expected', $ext);
            return false;
        }

        /**********/
        /* upload */
        /**********/
        App::import('Vendor', 'Upload');

        $uploadPath = CACHE. 'installer';
        $workingDir = CACHE . 'installer' . DS . $data['Package']['data']['name'] . DS;
        $Folder = new Folder;
        $Upload = new Upload($data['Package']['data']);
        $Upload->allowed = array('application/*');
        $Upload->file_overwrite = true;
        $Upload->file_src_name_ext = 'zip';

        $Folder->delete($workingDir);
        $Upload->Process($workingDir . 'package' . DS);

        if (!$Upload->processed) {
            $this->errors[] = __d('system', 'Package upload error') . "<br/><p>{$Upload->error}</p>";
            return false;
        }

        /*******************/
        /* unzip & install */
        /*******************/
        App::import('Vendor', 'PclZip');

        $PclZip = new PclZip($Upload->file_dst_pathname);

        if (($v_result_list = $PclZip->extract(PCLZIP_OPT_PATH, $workingDir . 'unzip')) == 0 ) {
            $this->errors[] = __d('system', 'Unzip error.') . "<br/><p>" . $PclZip->errorInfo(true) . "</p>";

            return false;
        } else {
            /* Package Validation */
            $Folder->path = $workingDir . 'unzip' . DS;
            $folders = $Folder->read();$folders = $folders[0];
            $packagePath = isset($folders[0]) && count($folders) === 1 ? CACHE . 'installer' . DS . $data['Package']['data']['name'] . DS . 'unzip' . DS . str_replace(DS, '', $folders[0]) . DS : false;
            $appName = (string)basename($packagePath);

            if (!$packagePath) {
                $this->errors[] = __d('system', 'Invalid package structure after unzip');

                return false;
            }

            switch ($this->options['type']) {
                case 'module':
                    default:
                        $tests = array(
                            'notAlreadyInstalled' => array(
                                'test' => (
                                    $this->Controller->Module->find('count', array('conditions' => array('Module.name' => $appName, 'Module.type' => 'module'))) === 0 &&
                                    !file_exists(ROOT . DS . 'Modules' . DS . $appName)
                                ),
                                'header' => __d('system', 'Already Installed'),
                                'msg' => __d('system', 'This module is already installed')
                            ),
                            'protectedPrefix' => array(
                                'test' => (strpos(Inflector::camelize($appName), 'Theme') !== 0),
                                'header' => __d('system', 'Invalid prefix'),
                                'msg' => __d('system', "The prefix 'Theme' is not allowed for modules.")
                            ),
                            'Config' => array(
                                'test' => file_exists($packagePath . 'Config'),
                                'header' => __d('system', 'Config Folder'),
                                'msg' => __d('system', 'Config folder not found')
                            ),
                            'Controller' => array(
                                'test' => file_exists($packagePath . 'Controller'),
                                'header' => __d('system', 'Controller Folder'),
                                'msg' => __d('system', 'Controller folder not found')
                            ),
                            'Component' => array(
                                'test' => file_exists($packagePath . 'Controller' . DS . 'Component'),
                                'header' => __d('system', 'Component Folder'),
                                'msg' => __d('system', 'Component folder not found')
                            ),
                            'InstallComponent.php' => array(
                                'test' => file_exists($packagePath . 'Controller' . DS . 'Component' . DS . 'InstallComponent.php'),
                                'header' => __d('system', 'Installer File'),
                                'msg' => __d('system', 'Installer file (InstallComponent.php) not found')
                            ),
                            'Lib' => array(
                                'test' => file_exists($packagePath . 'Lib'),
                                'header' => __d('system', 'Lib Folder'),
                                'msg' => __d('system', 'Lib folder not found')
                            ),
                            'Locale' => array(
                                'test' => file_exists($packagePath . 'Locale'),
                                'header' => __d('system', 'Locale Folder'),
                                'msg' => __d('system', 'Locale folder not found')
                            ),
                            'Model' => array(
                                'test' => file_exists($packagePath . 'Model'),
                                'header' => __d('system', 'Model Folder'),
                                'msg' => __d('system', 'Model folder not found')
                            ),
                            'yaml' => array(
                                'test' => file_exists($packagePath . "{$appName}.yaml"),
                                'header' => __d('system', 'YAML File'),
                                'msg' => __d('system', 'YAML File (%s) not found', "{$appName}.yaml")
                            )
                        );
                break;

                case 'theme':
                    $tests = array(
                        'notAlreadyInstalled' => array(
                            'test' => (
                                $this->Controller->Module->find('count', array('conditions' => array('Module.name' => 'Theme' . $appName, 'Module.type' => 'theme'))) === 0 &&
                                !file_exists(APP . 'View' . DS . 'Themed' . DS . $appName)
                            ),
                            'header' => __d('system', 'Already Installed'),
                            'msg' => __d('system', 'This theme is already installed')
                        ),
                        'Layouts' => array(
                            'test' => file_exists($packagePath . 'Layouts'),
                            'header' => __d('system', 'Layouts Folder'),
                            'msg' => __d('system', '"Layouts" folder not found')
                        ),
                        'app' => array(
                            'test' => file_exists($packagePath . 'app'),
                            'header' => __d('system', 'app Folder'),
                            'msg' => __d('system', '"app" folder not found')
                        ),
                        'plugin_app' => array(
                            'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName),
                            'header' => __d('system', 'Plugin app'),
                            'msg' => __d('system', 'Plugin app ("%s") folder not found', 'Theme' . Inflector::camelize($appName))
                        ),
                        'InstallComponent.php' => array(
                            'test' => file_exists($packagePath . 'app' . DS . 'Theme' . $appName .  DS . 'Controller' . DS . 'Component' . DS . 'InstallComponent.php'),
                            'header' => __d('system', 'Installer File'),
                            'msg' => __d('system', 'Installer file (InstallComponent.php) not found')
                        ),
                        'webroot' => array(
                            'test' => file_exists($packagePath . 'webroot'),
                            'header' => __d('system', 'webroot Folder'),
                            'msg' => __d('system', 'webroot folder not found')
                        ),
                        'yaml' => array(
                            'test' => file_exists($packagePath . "{$appName}.yaml"),
                            'header' => __d('system', 'YAML File'),
                            'msg' => __d('system', 'YAML File (%s) not found', "{$appName}.yaml")
                        ),
                        'thumbnail' => array(
                            'test' => file_exists($packagePath . 'thumbnail.png'),
                            'header' => __d('system', 'Theme thumbnail'),
                            'msg' => __d('system', 'Thumbnail image ("%s") not found', 'thumbnail.png')
                        )
                    );
                break;
            }

            $tests['CamelCaseName'] =array(
                'test' => (Inflector::camelize($appName) == $appName),
                'header' => __d('system', 'Theme name'),
                'msg' => __d('system', 'Invalid theme name (got "%s", expected: "%s")', $appName, Inflector::camelize($appName))
            );

            if (!$this->__process_tests($tests)) {
                return false;
            }

            /** YAML validations **/
            $yaml = Spyc::YAMLLoad($packagePath . "{$appName}.yaml");

            switch ($this->options['type']) {
                case 'module':
                    default:
                        $tests = array(
                            'yaml' => array(
                                'test' => (
                                    (isset($yaml['name']) && !empty($yaml['name'])) &&
                                    (isset($yaml['description']) && !empty($yaml['description'])) &&
                                    (isset($yaml['category']) && !empty($yaml['category'])) &&
                                    (isset($yaml['version']) &&  !empty($yaml['version'])) &&
                                    (isset($yaml['core']) && !empty($yaml['core']))
                                ),
                                'header' => __d('system', 'YAML Validation'),
                                'msg' =>  __d('system', 'Module configuration file (%s) appears to be invalid.', "{$appName}.yaml")
                            )
                        );
                break;

                case 'theme':
                    $tests = array(
                        'yaml' => array(
                            'test' => (
                                    (isset($yaml['info']) && !empty($yaml['info'])) &&
                                    (isset($yaml['info']['name']) && !empty($yaml['info']['name'])) &&
                                    (isset($yaml['info']['description']) && !empty($yaml['info']['description'])) &&
                                    (isset($yaml['info']['version']) && !empty($yaml['info']['version'])) &&
                                    (isset($yaml['info']['author']) && !empty($yaml['info']['author'])) &&
                                    (isset($yaml['info']['core']) && !empty($yaml['info']['core'])) &&
                                    isset($yaml['stylesheets']) &&
                                    (isset($yaml['regions']) && !empty($yaml['regions'])) &&
                                    (isset($yaml['layout']) && !empty($yaml['layout']))
                            ),
                            'header' => __d('system', 'YAML Validation'),
                            'msg' => __d('system', 'Theme configuration file (%s) appears to be invalid.', "{$appName}.yaml")
                        )
                    );
                break;
            }

            if (!$this->__process_tests($tests)) {
                $this->errors[] = __d('system', 'Invalid information file (.yaml)');

                return false;
            }

            /**
             * validate dependencies and required core version
             */
            switch ($this->options['type']) {
                case 'module':
                    $core = "core ({$yaml['core']})";
                    $r = $this->checkIncompatibility($this->parseDependency($core), Configure::read('Variable.qa_version'));

                    if ($r !== null) {
                        $this->errors[] = __d('system', 'This module is incompatible with your QuickApps version.');

                        return false;
                    }

                    if (isset($yaml['dependencies']) && $this->checkDependency($yaml)) {
                        $this->errors[] = __d('system', "This module depends on other modules that you do not have or doesn't meet the version required: %s", implode('<br/>', $yaml['dependencies']));

                        return false;
                    }
                break;

                case 'theme':
                    $core = "core ({$yaml['info']['core']})";
                    $r = $this->checkIncompatibility($this->parseDependency($core), Configure::read('Variable.qa_version'));

                    if ($r !== null) {
                        $this->errors[] = __d('system', 'This theme is incompatible with your QuickApps version.');

                        return false;
                    }

                    if (isset($yaml['info']['dependencies']) && $this->checkDependency($yaml['info'])) {
                        $this->errors[] = __d('system', "This theme depends on other modules that you do not have or doesn't meet the version required: %s", implode('<br/>', $yaml['info']['dependencies']));

                        return false;
                    }
                break;
            }

            /**
             * validate custom fields
             * Only modules are allowed to define fields.
             */
            if ($this->options['type'] == 'module' && file_exists($packagePath . 'Fields')) {
                $Folder = new Folder($packagePath . 'Fields');
                $fields = $Folder->read();
                $fieldErrors = false;

                if (isset($fields[0])) {
                    $fields = $fields[0];

                    foreach ($fields as $field) {
                        if (file_exists($packagePath . 'Fields' . DS . $field . DS . "{$field}.yaml")) {
                            $yaml = Spyc::YAMLLoad($packagePath . 'Fields' . DS . $field . DS . "{$field}.yaml");

                            if (!isset($yaml['name']) || !isset($yaml['description'])) {
                                $fieldErrors = true;
                                $this->errors[] = __d('system', 'invalid information file (.yaml). Field "%s"', $field);
                            }
                        } else {
                            $fieldErrors = true;
                            $this->errors[] = __d('system', 'Invalid field "%s". Information file (.yaml) not found.', $field);
                        }
                    }
                }

                if ($fieldErrors) {
                    return false;
                }
            }
            ### End of validations ###


            /*****************/
            /**** INSTALL ****/
            /*****************/
            $installComponentPath = $this->options['type'] == 'theme' ? $packagePath . 'app' . DS . 'Theme' . $appName . DS . 'Controller' . DS . 'Component' . DS : $packagePath . 'Controller' . DS . 'Component' . DS;
            $Install = $this->loadInstallComponent($installComponentPath);
            $r = true;

            if (method_exists($Install, 'beforeInstall')) {
                $r = $Install->beforeInstall($this);
            }

            if ($r === false) {
                return false;
            }

            /** Copy files **/
            $copyTo = ($this->options['type'] == 'module') ? ROOT . DS . 'Modules' . DS . $appName : APP . 'View' . DS . 'Themed' . DS . $appName;
            $this->rcopy($packagePath, $copyTo);

            /** DB Logics **/
            $moduleData = array(
                'name' => ($this->options['type'] == 'module' ? $appName : 'Theme' . $appName),
                'type' => ($this->options['type'] == 'module' ? 'module' : 'theme' ),
                'status' => intval($this->options['status'])
            );

            $this->Controller->Module->save($moduleData); # register module

            /** Build ACOS && Register module in core **/
            switch ($this->options['type']) {
                case 'module':
                    $this->buildAcos($appName);
                break;

                case 'theme':
                    $this->buildAcos(
                        'Theme' . $appName,
                        APP . 'View'. DS . 'Themed' . DS . $appName . DS . 'app' . DS
                    );

                    App::build(array('plugins' => array(APP . 'View'. DS . 'Themed' . DS . $appName . DS . 'app' . DS)));
                break;
            }

            /** Delete unziped package **/
            $Folder->delete($workingDir);

            /** Finish **/
            if (method_exists($Install, 'afterInstall')) {
                $Install->afterInstall($this);
            }

            $this->afterInstall();
        }

        umask($oldMask);

        return true;
    }

/**
 * Uninstall plugin by name
 *
 * @param string $pluginName name of the plugin to uninstall, it could be a theme plugin
 *                           (ThemeMyThemeName or theme_my_theme_name) or module plugin
 *                           (MyModuleName or my_module_name)
 * @return boolean true on success or false otherwise
 */
    public function uninstall($pluginName = false) {
        if (!$pluginName || !is_string($pluginName)) {
            return false;
        }

        $this->options['name'] = $pluginName;
        $Name = Inflector::camelize($this->options['name']);
        $pData = $this->Controller->Module->findByName($Name);

        if (!$pData) {
            return false;
        }

        /* useful for before/afterUninstall */
        $this->options['type'] = $pData['Module']['type'];
        $this->options['__data'] = $pData;
        $this->options['__path'] = $pData['Module']['type'] == 'theme' ? APP . 'View' . DS . 'Themed' . DS . str_replace('Theme', '', $Name) . DS . 'app' . DS . $Name . DS : CakePlugin::path($Name);
        $this->options['__Name'] = $Name;

        # core plugins can not be deleted
        if (in_array($this->options['__Name'], array_merge(array('ThemeDefault', 'ThemeAdminDefault'), Configure::read('coreModules')))) {
            return false;
        }

        $pluginPath = $this->options['__path'];

        if (!file_exists($pluginPath)) {
            return false;
        }

        $Install =& $this->loadInstallComponent($pluginPath . 'Controller' . DS . 'Component' . DS);

        if (!is_object($Install)) {
            return false;
        }

        $r = true;

        if (method_exists($Install, 'beforeUninstall')) {
            $r = $Install->beforeUninstall($this);
        }

        if ($r === false) {
            return false;
        }

        if (!$this->Controller->Module->deleteAll(array('Module.name' => $Name))) {
            return false;
        }

        /**
         * Theme Controller does not allow to delete in-use-theme,
         * but for precaution we assign to Core Default ones if for some reason
         * the in-use-theme is being deleted.
         */
        if ($this->options['type'] == 'theme') {
            if (Configure::read('Variable.site_theme') == str_replace('Theme', '', $Name)) {
                ClassRegistry::init('System.Variable')->save(
                    array(
                        'name' => 'site_theme',
                        'value' => 'Default'
                    )
                );
            } elseif (Configure::read('Variable.admin_theme') == str_replace('Theme', '', $Name)) {
                ClassRegistry::init('System.Variable')->save(
                    array(
                        'name' => 'admin_theme',
                        'value' => 'AdminDefault'
                    )
                );            
            }
        }

        if (method_exists($Install, 'afterUninstall')) {
            $Install->afterUninstall($this);
        }

        $this->afterUninstall();

        return true;
    }

    public function enable() {

    }

    public function disable() {

    }

    public function beforeInstall() {
        return true;
    }

    public function beforeUninstall() {
        return true;
    }

    public function afterInstall() {
        Cache::delete('Modules');
        Cache::delete('Variable');

        $this->Controller->Quickapps->loadVariables();
        $this->Controller->Quickapps->loadModules();

        return true;
    }

    public function afterUninstall() {
        # delete & regenerate caches
        Cache::delete('Modules');
        Cache::delete('Variable');

        $this->Controller->Quickapps->loadModules();
        $this->Controller->Quickapps->loadVariables();

        # delete all menus created by module/theme
        ClassRegistry::init('Menu.Menu')->deleteAll(
            array(
                'Menu.module' => $this->options['__name']
            )
        );

        # delete blocks
        ClassRegistry::init('Block.Block')->deleteAll(
            array(
                'Block.module' => $this->options['__name']
            )
        );

        # delete acos branch
        $rootAco = $this->Controller->Acl->Aco->find('first',
            array(
                'conditions' => array(
                    'Aco.alias' => $this->options['__Name'],
                    'Aco.parent_id' => null
                )
            )
        );

        $this->Controller->Acl->Aco->delete($rootAco['Aco']['id']);

        # delete node types
        ClassRegistry::init('Node.NodeType')->deleteAll(
            array(
                'NodeType.module' => $this->options['__name']
            )
        );

        # delete app folder
        $folderpath = ($this->options['type'] == 'module') ? $this->options['__path'] : dirname(dirname($this->options['__path']));
        $Folder = new Folder($folderpath);
        $Folder->delete();
    }

/**
 * Creates acos for especified plugin by parsing its Controller folder.
 * Plugin's fields are also analyzed.
 * Usage example:
 * {{{
 *      buildAcos('user', APP . 'Plugin' . DS); // Core plugin
 * }}}
 *
 * @param string $plugin CamelCase plugin name to analyze
 * @param mixed $pluginPath Optional (string) plugin full base path. If it is set to false
 *          then ROOT/Modules is used as default base path.
 * @return void
 */
    public function buildAcos($plugin, $pluginPath = false) {
        $plugin = Inflector::camelize($plugin);
        $pluginPath = !$pluginPath ? ROOT . DS . 'Modules' . DS : str_replace(DS . DS, DS, $pluginPath . DS);

        if (!file_exists($pluginPath . $plugin)) {
            return false;
        }

        $__folder = new Folder;
        $cPath = $pluginPath . $plugin . DS . 'Controller' . DS;
        $__folder->path = $cPath;
        $controllers = $__folder->read();
        $controllers = $controllers[1];

        if (count($controllers) === 0) {
            return false;
        }

        $appControllerPath = $cPath . $plugin . 'AppController.php';

        if (file_exists($appControllerPath)) {
            include_once($appControllerPath);
        }

        $this->Controller->Acl->Aco->create();
        $this->Controller->Acl->Aco->save(array('alias' => Inflector::camelize($plugin)));

        $_parent_id =  $this->Controller->Acl->Aco->getInsertID();

        foreach ($controllers as $c) {
            if (strpos($c, 'AppController.php') !== false) {
                continue;
            }

            include_once($cPath .  $c);

            $className = str_replace('.php', '', $c);
            $methods = get_this_class_methods($className);

            foreach ($methods as $i => $m) {
                if (strpos($m, '__') === 0 ||
                    strpos($m, '_') === 0 ||
                    in_array($m, array('beforeFilter', 'beforeRender', 'beforeRedirect', 'afterFilter'))
                ) { # ignore private and callback methods
                    unset($methods[$i]);
                }
            }

            $this->Controller->Acl->Aco->create();
            $this->Controller->Acl->Aco->save(
                array(
                    'parent_id' => $_parent_id,
                    'alias' => str_replace('Controller', '', $className)
                )
            );

            $parent_id =  $this->Controller->Acl->Aco->getInsertID();

            foreach ($methods as $m) {
                $this->Controller->Acl->Aco->create();
                $this->Controller->Acl->Aco->save(
                    array(
                        'parent_id' => $parent_id,
                        'alias' => $m
                    )
                );
            }
        }

        # Fields
        if (file_exists($pluginPath . $plugin . DS . 'Fields')) {
            $__folder->path = $pluginPath . $plugin . DS . 'Fields' . DS;
            $fieldsFolders = $__folder->read(); $fieldsFolders = $fieldsFolders[0];

            foreach ($fieldsFolders as $field) {
                $this->buildAcos(basename($field), $pluginPath . $plugin . DS . 'Fields' . DS);
            }
        }
    }

/**
 * By: Drupal
 * Parse a dependency for comparison by checkIncompatibility().
 *
 * @param $dependency
 *   A dependency string, for example 'foo (>=7.x-4.5-beta5, 3.x)'.
 * @return
 *   An associative array with three keys:
 *   - 'name' includes the name of the thing to depend on (e.g. 'foo').
 *   - 'original_version' contains the original version string (which can be
 *     used in the UI for reporting incompatibilities).
 *   - 'versions' is a list of associative arrays, each containing the keys
 *     'op' and 'version'. 'op' can be one of: '=', '==', '!=', '<>', '<',
 *     '<=', '>', or '>='. 'version' is one piece like '4.5-beta3'.
 *   Callers should pass this structure to checkIncompatibility().
 *
 * @see checkIncompatibility()
 */
    public function parseDependency($dependency) {
        // We use named subpatterns and support every op that version_compare
        // supports. Also, op is optional and defaults to equals.
        $p_op = '(?P<operation>!=|==|=|<|<=|>|>=|<>)?';
        // Core version is always optional: 7.x-2.x and 2.x is treated the same.
        $p_core = '(?:' . preg_quote(Configure::read('Variable.qa_version')) . '-)?';
        $p_major = '(?P<major>\d+)';
        // By setting the minor version to x, branches can be matched.
        $p_minor = '(?P<minor>(?:\d+|x)(?:-[A-Za-z]+\d+)?)';
        $value = array();
        $parts = explode('(', $dependency, 2);
        $value['name'] = trim($parts[0]);

        if (isset($parts[1])) {
            $value['original_version'] = ' (' . $parts[1];

            foreach (explode(',', $parts[1]) as $version) {
                if (preg_match("/^\s*{$p_op}\s*{$p_core}{$p_major}\.{$p_minor}/", $version, $matches)) {
                    $op = !empty($matches['operation']) ? $matches['operation'] : '=';

                    if ($matches['minor'] == 'x') {
                        if ($op == '>' || $op == '<=') {
                            $matches['major']++;
                        }

                        if ($op == '=' || $op == '==') {
                            $value['versions'][] = array('op' => '<', 'version' => ($matches['major'] + 1) . '.x');
                            $op = '>=';
                        }
                    }

                    $value['versions'][] = array('op' => $op, 'version' => $matches['major'] . '.' . $matches['minor']);
                }
            }
        }

        return $value;
    }

/**
 * By: Drupal
 * Check whether a version is compatible with a given dependency.
 *
 * @param $v
 *   The parsed dependency structure from parseDependency().
 * @param $current_version
 *   The version to check against (like 4.2).
 * @return
 *   NULL if compatible, otherwise the original dependency version string that
 *   caused the incompatibility.
 *
 * @see parseDependency()
 */
    public function checkIncompatibility($v, $current_version) {
        if (!empty($v['versions'])) {
            foreach ($v['versions'] as $required_version) {
                if ((isset($required_version['op']) && !version_compare($current_version, $required_version['version'], $required_version['op']))) {
                    return $v['original_version'];
                }
            }
        }

        return null;
    }

/**
 * Verify if all the plugins that $plugin depends on are available and match the required version.
 *
 * @param  string $plugin plugin alias
 * @return boolean
 */
    public function checkDependency($plugin = null) {
        $Plugin = !is_null($plugin) && isset($plugin['yaml']) ? $plugin : Configure::read('Modules.' . Inflector::underscore($plugin));

        if (isset($Plugin['yaml']['dependencies']) && is_array($Plugin['yaml']['dependencies'])) {
            foreach ($Plugin['yaml']['dependencies'] as $p) {
                $check = false;
                $check = Configure::read('Modules.' . Inflector::underscore($p));

                if (!$check) {
                    return false;
                }

                $check = $this->checkIncompatibility($this->parseDependency($p), $check['yaml']['version']);

                if (!$check) {
                    return false;
                }
            }
        }

        return true;
    }

/**
 * Verify if there is any plugin that depends of $plugin
 *
 * @param  string $plugin plugin alias
 * @param  boolean $returnList set to true to return an array list of all plugins that uses $plugin.
 *                             The array list contains all the plugin information Configure::read('Modules.{plugin}')
 * @return mixed boolean (if $returnList = false), false return means that there are no plugins that uses $plugin.
 *                       Or an array list of all plugins that uses $plugin ($returnList = true), empty arrray is returned
 *                       if there are no plugins that uses $plugin.
 */
    function checkReverseDependency($plugin, $returnList = true) {
        $list = array();
        $plugin = Inflector::camelcase($plugin);

        foreach (Configure::read('Modules') as $p) {
            if (isset($p['yaml']['dependencies']) &&
                is_array($p['yaml']['dependencies'])
            ) {
                $dependencies = array();

                foreach ($p['yaml']['dependencies'] as $d) {
                    $dependencies[] = $this->parseDependency($d);
                }

                $dependencies = Set::extract('{n}.name', $dependencies);

                if (in_array($plugin, $dependencies, true) && $returnList) {
                    $list[] = $p;
                } elseif (in_array($plugin, $dependencies, true)) {
                    return true;
                }
            }
        }

        if ($returnList) {
            return $list;
        }

        return false;
    }

    private function __process_tests($tests, $header = false) {
        $e = 0;

        foreach ($tests as $key => $test) {
            if (!$test['test']) {
                $e++;
                $this->errors[] = $header ? "<b>{$test['header']}</b><br/><p>{$test['msg']}</p>" : "<p>{$test['msg']}</p>";
            }
        }

        return ($e == 0);
    }

    public function loadInstallComponent($search = false) {
        if (!file_exists($search . 'InstallComponent.php')) {
            return false;
        }

        include_once($search . 'InstallComponent.php');

        $class = "InstallComponent";
        $component = new $class($this->Controller->Components);

        if (method_exists($component, 'initialize')) {
            $component->initialize($this);
        }

        if (method_exists($component, 'startup')) {
            $component->startup($this);
        }

        return $component;
    }

    public function rcopy($src, $dst) {
        $dir = opendir($src);

        @mkdir($dst);

        while(false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . DS . $file)) {
                    $this->rcopy($src . DS . $file,$dst . DS . $file);
                } else {
                    if (!copy($src . DS . $file, $dst . DS . $file)) {
                        return false;
                    }
                }
            }
        }

        closedir($dir);
    }
}