<?php
/**
 * Hook Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class QuickAppsComponent extends Component {
    public $Controller;

    public function startup() { }
    public function beforeRender() { }
    public function shutdown() { }
    public function beforeRedirect() { }

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
    public function initialize(&$Controller) {
        $this->Controller =& $Controller;

        $this->loadVariables();
        $this->loadModules();
        $this->setLanguage();
        $this->accessCheck();
        $this->setTheme();
        $this->setTimeZone();
        $this->prepareContent();
        $this->siteStatus();
        $this->setCrumb();
    }

/**
 * Check for maintenance status,
 * 'Site Offline' screen is rendered if site is offline.
 *
 * @return void
 */ 
    public function siteStatus() {
        if (Configure::read('Variable.site_online') != 1 && !$this->isAdmin()) {
            if ($this->Controller->plugin != 'User' &&
                $this->Controller->request->params['controller'] != 'log' &&
                !in_array($this->Controller->request->params['controller'], array('login', 'logout'))
            ) {
                $this->Controller->layout = 'error'; 
                $this->Controller->viewPath = 'Errors'; 

                @$this->Controller->response->header(
                    array(
                        'HTTP/1.1 503 Service Temporarily Unavailable',
                        'Status: 503 Service Temporarily Unavailable',
                        'Retry-After: 60'
                    )
                );

                $this->Controller->set('name', __t('Site offline'));
                $this->Controller->set('url', $this->Controller->request->here);
                $this->Controller->render('offline');                
            }
        }
    }

/**
 * Set theme to use and load its information.
 *
 * @return void
 */
    public function setTheme() {
        if (isset($this->Controller->request->params['admin']) && $this->Controller->request->params['admin'] == 1) {
            $this->Controller->theme =  Configure::read('Variable.admin_theme') ? Configure::read('Variable.admin_theme') : 'admin_default';
        } else {
            $this->Controller->theme =  Configure::read('Variable.site_theme') ? Configure::read('Variable.site_theme') : 'default';
        }

        $this->Controller->layout ='default';
        $this->Controller->viewClass = 'Theme';
        $theme_path = App::themePath($this->Controller->theme);

        if (file_exists($theme_path . "{$this->Controller->theme}.yaml")) {
            $yaml = Cache::read("theme_{$this->Controller->theme}_yaml");

            if (!$yaml) {
                $yaml = Spyc::YAMLLoad($theme_path . "{$this->Controller->theme}.yaml");

                Cache::write("theme_{$this->Controller->theme}_yaml", $yaml);
            }

            $yaml['info']['folder'] = $this->Controller->theme;
            $yaml['settings'] = Configure::read("Modules.Theme{$this->Controller->theme}.settings");

            # set custom or default logo
            $yaml['settings']['site_logo_url'] = isset($yaml['settings']['site_logo_url']) && !empty($yaml['settings']['site_logo_url']) ? $yaml['settings']['site_logo_url'] : '/img/logo.png';

            # set custom or default favicon
            $yaml['settings']['site_favicon_url'] = isset($yaml['settings']['site_favicon_url']) && !empty($yaml['settings']['site_favicon_url']) ? $yaml['settings']['site_logo_url'] : '/favicon.ico';

            Configure::write('Theme', $yaml);

            if (isset($yaml['stylesheets']) && !empty($yaml['stylesheets'])) {
                foreach ($yaml['stylesheets'] as $media => $files) {
                    if (!isset($this->Controller->Layout['stylesheets'][$media])){
                        $this->Controller->Layout['stylesheets'][$media] = array();
                    }

                    foreach ($files as $file) {
                        $this->Controller->Layout['stylesheets'][$media][] = $file;
                    }
                }
            }

            if (isset($yaml['javascripts']) && !empty($yaml['javascripts'])) {
                foreach ($yaml['javascripts'] as $type => $files) {
                    if (!in_array($type, array('file', 'embed'))) {
                        continue;
                    }

                    foreach ($files as $file) {
                        $this->Controller->Layout['javascripts'][$type][] = $file;
                    }
                }
            }
        }

        if (Configure::read('Theme.layout')) {
            $this->Controller->layout = Configure::read('Theme.layout');
        }

        $this->Controller->hook('stylesheets_alter', $this->Controller->Layout['stylesheets']);    # pass css list to modules if they need to alter them (add/remove)
    }

/**
 * Prepare blocks, js, css, metas & basic data for rendering
 *
 * @return void
 */ 
    public function prepareContent() {
        $theme = Router::getParam('admin') ? Configure::read('Variable.admin_theme') : Configure::read('Variable.site_theme');
        $options = array(
            'conditions' => array(
                'Block.themes_cache LIKE' => "%:{$theme}:%", # only blocks assigned to current theme
                'Block.status' => 1,
                'OR' => array( # only blocks assigned to any/current language
                    'Block.locale = ' => null,
                    'Block.locale =' => '',
                    'Block.locale LIKE ' => '%s:3:"' . Configure::read('Variable.language.code') . '"%',
                    'Block.locale' => 'a:0:{}'
                )
            )
        );

        $this->Controller->Layout['blocks'] = $this->Controller->hook('blocks_list', $options, array('collectReturn' => false)); # request blocks to block module
        $this->Controller->hook('blocks_alter', $this->Controller->Layout['blocks']); # pass blocks to modules

        /* Basic js files/embed */
        $this->Controller->Layout['javascripts']['embed'][] = '
            jQuery.extend(QuickApps.settings, {
                "url": "' . str_replace("//", "/", $this->Controller->here . '/') . '",
                "base_url": "' . Router::url('/') . '",
                "domain": "' . str_replace(Router::url('/'), '', Router::url('/', true)) . '/",
                "locale": {"code": "' . Configure::read('Variable.language.code') . '"}
            });';

        $this->Controller->hook('javascripts_alter', $this->Controller->Layout['javascripts']); # pass js to modules
        $this->Controller->paginate = array('limit' => Configure::read('Variable.rows_per_page'));

        Configure::write('Variable.qa_version', Configure::read('Modules.System.yaml.version'));

        $defaultMetaDescription = Configure::read('Variable.site_description');

        if (!empty($defaultMetaDescription)){
            $this->Controller->Layout['meta']['description'] = $defaultMetaDescription;
        }

        // auto favicon meta
        if (Configure::read('Theme.settings.site_favicon')) {
            $faviconURL = Configure::read('Theme.settings.site_favicon_url');
            $this->Controller->Layout['meta']['icon'] = $faviconURL && !empty($faviconURL) ? Router::url($faviconURL) : Router::url('/favicon.ico');
        }
    }

/**
 * Set system language for the current user.
 * 
 * @return void
 */
    public function setLanguage() {
        $urlBefore = $this->__urlChunk();
        $urlBefore = isset($urlBefore[0]) ? $urlBefore[0] : '';
        $urlBeforeT = __t($urlBefore);

        $langs = $this->Controller->Language->find('all', array('conditions' => array('status' => 1), 'order' => array('ordering' => 'ASC')));
        $installed_codes = Set::extract('/Language/code', $langs);

        if (isset($this->Controller->request->params['language'])) {
            if (in_array($this->Controller->request->params['language'], $installed_codes)) {
                $lang = $this->Controller->request->params['language'];
            } else {
                header('Location: '. $this->Controller->request->base);
                exit;
            }
        } else {
            $lang = $this->Controller->Session->read('language');
        }

        Configure::write('Config.language', $lang);

        $last_i18n_urlT = __t($this->Controller->Session->read('last_i18n_url'));

        $lang = isset($this->Controller->request->params['named']['lang']) ? $this->Controller->request->params['named']['lang'] : $lang;
        $lang = isset($this->Controller->request->query['lang']) && !empty($this->Controller->request->query['lang']) ? $this->Controller->request->query['lang'] : $lang;
        $lang = empty($lang) ? Configure::read('Variable.default_language') : $lang;
        $lang = empty($lang) || !in_array($lang, $installed_codes) || strlen($lang) != 3 ? 'eng' : $lang;

        $this->Controller->Session->write('language', $lang);
        $_lang = Set::extract("/Language[code={$lang}]/..", $langs);

        if (!isset($_lang[0]['Language'])) { # not defined -> default = english
            $_lang[0]['Language'] = array(
                'code' => 'eng',
                'name' => 'English',
                'native' => 'English',
                'direction' => 'ltr'
            );
        }

        Configure::write('Variable.language', $_lang[0]['Language']);
        Configure::write('Variable.languages', $langs);
        Configure::write('Config.language', Configure::read('Variable.language.code'));

        $urlAfter = $this->__urlChunk();
        $urlAfter = isset($urlAfter[0]) ? $urlAfter[0] : '';
        $urlAfterT = __t($urlAfter);

        if ($urlBeforeT != $urlAfterT) {
            $this->Controller->Session->write('last_i18n_url', $urlBefore);
            $this->Controller->redirect($urlAfterT);
        }

        if (isset($this->Controller->request->params['named']['lang']) || (isset($this->Controller->request->query['lang']) && !empty($this->Controller->request->query['lang']))) {
            $last_i18n_url = $this->Controller->Session->read('last_i18n_url');

            if ($last_i18n_url && $last_i18n_urlT == $urlAfterT) {
                $this->Controller->redirect($last_i18n_url);
            }
        }
    }

/**
 * Check if current user is allowed to access the requested location.
 * 'Access Deny' screen is rendered if user can not access.
 *
 * @return void
 */ 
    public function accessCheck() {
        if ($this->Controller->request->params['plugin']) {
            $plugin = Inflector::camelize($this->Controller->request->params['plugin']);
            $ppath = CakePlugin::path($plugin);

            if (strpos($ppath, DS . 'Fields' . DS) === false && !Configure::read('Modules.' . $plugin . '.status')) {
                if ($this->Controller->request->params['admin']) {
                    $this->Controller->redirect('/admin');
                }

                $this->Controller->redirect('/');
            }
        }

        $this->Controller->Auth->authenticate = array(
            'Form' => array(
                'fields' => array(
                    'username' => 'username',
                    'password' => 'password'
                ),
                'userModel' => 'User.User',
                'scope' => array('User.status' => 1)
            )
        );

        $this->Controller->Auth->loginAction = array(
            'controller' => 'user',
            'action' => 'login',
            'plugin' => 'user'
        );

        $this->Controller->Auth->authError = __d('user', 'You are not authorized to access that location.');
        $this->Controller->Auth->authorize = array('Controller');
        $this->Controller->Auth->loginRedirect = Router::getParam('admin') ? '/admin' : '/';
        $this->Controller->Auth->logoutRedirect = $this->Controller->Auth->loginRedirect;
        $this->Controller->Auth->allowedActions = array('login', 'logout');
        $cookie = $this->Controller->Cookie->read('UserLogin');

        if (!$this->Controller->Auth->user() &&
            isset($cookie['id']) &&
            !empty($cookie['id']) &&
            isset($cookie['password']) &&
            !empty($cookie['password'])
        ) {
            $User = ClassRegistry::init('User.User');
            $User->unbindFields();
            $user = $User->find('first',
                array(
                    'conditions' => array(
                        'User.id' => @$cookie['id'],
                        'User.password' => @$cookie['password']
                    )
                )
            );

            $User->bindFields();

            if ($user) {
                $this->Controller->loadModel('UsersRole');
                $session = $user['User'];

                $session['role_id'] = $this->Controller->UsersRole->find('all',
                    array(
                        'conditions' => array('UsersRole.user_id' => $user['User']['id']),
                        'fields' => array('role_id', 'user_id')
                    )
                );

                $session['role_id'] = Set::extract('/UsersRole/role_id', $session['role_id']);
                $session['role_id'][] = 2; #role: authenticated user
                $this->Controller->Auth->login($session);

                return true;
            }
        }

        if ($this->isAdmin()) {
            $this->Controller->Auth->allowedActions = array('*');
        } else {
            $roleId = $this->Controller->Auth->user() ? $this->Controller->Auth->user('role_id') : 3; # 3: anonymous user (public)
            $aro = $this->Controller->Acl->Aro->find('first',
                array(
                    'conditions' => array(
                        'Aro.model' => 'User.Role',
                        'Aro.foreign_key' => $roleId, # roles! array of ids
                    ),
                    'recursive' => -1,
                )
            );
            $aroId = $aro['Aro']['id'];

            # get current plugin ACO
            $pluginNode = $this->Controller->Acl->Aco->find('first',
                array(
                    'conditions' => array(
                        'Aco.alias' => $this->Controller->params['plugin'],
                        'parent_id = ' => null
                    ),
                    'fields' => array('alias', 'id')
                )
            );

            # get plugin controllers ACOs
            $thisControllerNode = $this->Controller->Acl->Aco->find('first',
                array(
                    'conditions' => array(
                        'alias' => $this->Controller->name,
                        'parent_id' => $pluginNode['Aco']['id']
                    )
                )
            );

            if ($thisControllerNode) {
                $thisControllerActions = $this->Controller->Acl->Aco->find('list',
                    array(
                        'conditions' => array(
                            'Aco.parent_id' => $thisControllerNode['Aco']['id'],
                        ),
                        'fields' => array(
                            'Aco.id',
                            'Aco.alias',
                        ),
                        'recursive' => -1,
                    )
                );
                $thisControllerActionsIds = array_keys($thisControllerActions);
                $allowedActions = $this->Controller->Acl->Aco->Permission->find('list',
                    array(
                        'conditions' => array(
                            'Permission.aro_id' => $aroId,
                            'Permission.aco_id' => $thisControllerActionsIds,
                            'Permission._create' => 1,
                            'Permission._read' => 1,
                            'Permission._update' => 1,
                            'Permission._delete' => 1,
                        ),
                        'fields' => array('id', 'aco_id'),
                        'recursive' => -1,
                    )
                );
                $allowedActionsIds = array_values($allowedActions);
            }

            $allow = array();

            if (isset($allowedActionsIds) && is_array($allowedActionsIds) && count($allowedActionsIds) > 0) {
                foreach ($allowedActionsIds as $i => $aId){
                    $allow[] = $thisControllerActions[$aId];
                }
            }

            $this->Controller->Auth->allowedActions = array_merge($this->Controller->Auth->allowedActions, $allow);
        }

        Configure::write('allowedActions', $this->Controller->Auth->allowedActions);
    }

/**
 * Set site default time zone, and `timezone` environment variable based on User session.
 *
 * @return void
 */
    public function setTimeZone() {
        date_default_timezone_set(Configure::read('Variable.date_default_timezone'));

        $offset = 0;
        $tz = $this->loggedIn() ? $this->Controller->Session->read('Auth.User.timezone') : Configure::read('Variable.date_default_timezone');
        $tz = empty($tz) ? 'GMT' : $tz;

        try {
            $timezone = new DateTimeZone($tz);
            $offset = $timezone->getOffset(new DateTime);
        } catch(Exception $error) {
            LogError($error->getMessage());
        }

        Configure::write('Variable.timezone', $offset / 60 / 60);
    }

/**
 * Performs a cache of all environment variables stored in `variables` table.
 * ###Usage
 *  {{{
 *   Configure::read('Variable.varible_key');
 *  }}}
 *
 * @return void
 */ 
 
    public function loadVariables() {
        $variables = Cache::read('Variable');
        $lp = Configure::read('Variable.url_language_prefix');

        if ($variables === false) {
            $this->Controller->Variable->writeCache();
        } else {
            Configure::write('Variable', $variables);
        }

        Configure::write('Variable.url_language_prefix', $lp);
    }

/**
 * Shortcut for $this->set(`title_for_layout`...).
 *
 * @param string $str Title for layout
 * @return void
 */
    public function title($str) {
        $this->Controller->set('title_for_layout', $str);
    }

/**
 * Shortcut for Session setFlash.
 *
 * @param string $msg Mesagge to display
 * @param string $class Type of message: error, success, alert, bubble
 * @return void
 */
    public function flashMsg($msg, $class = 'success') {
        return $this->Controller->Session->setFlash($msg, 'theme_flash_message', array('class' => $class));
    }

/**
 * Set crumb from url parse or add url(s) to the links list.
 *
 * @param mixed $url Array of links to push to the crumbs list. Or String url.
 * @return void
 */
    public function setCrumb($url = false) {
        if (func_num_args() > 1) {
            $args = func_get_args();

            foreach ($args as $arg) {
                $this->setCrumb($arg);
            }
        }

        if (is_array($url) && !empty($url)) {
            if (is_array($url[0])) {
                foreach ($url as $link) {
                    if (empty($link)) {
                        continue;
                    }

                    $push = array(
                        'MenuLink' => array(
                            'link_title' => $link[0],
                            'router_path' => (empty($link[1]) ? 'javascript:return false;': $link[1]),
                            'description' => (isset($link[2]) ? $link[2] : ''),
                        )
                    );

                    $this->Controller->viewVars['breadCrumb'][] = $push;
                }
            } else {
                $push = array(
                    'MenuLink' => array(
                        'link_title' => $url[0],
                        'router_path' => (empty($url[1]) ? 'javascript:return false;': $url[1]),
                        'description' => (isset($url[2]) ? $url[2] : ''),
                    )
                );
                $this->Controller->viewVars['breadCrumb'][] = $push;
            }

            return;
        } else {
            $url = !is_string($url) ? $this->__urlChunk() : $url;
        }

        if (is_array($url)) {
            foreach ($url as $k => $u) {
                $url[$k] = preg_replace('/\/{2,}/', '',  "{$u}//");

                if (Configure::read('Variable.url_language_prefix')) {
                    $url[] = str_replace_once('/' . Configure::read('Config.language'), '', $u);
                }
            }
        } else {
            $url = preg_replace('/\/{2,}/', '',  "{$url}//");
        }

        $this->Controller->set('breadCrumb', array());

        $current = $this->Controller->MenuLink->find('first',
            array(
                'conditions' => array(
                    'MenuLink.router_path' => (empty($url) ? '': $url)
                )
            )
        );

        if (!empty($current)) {
            $this->Controller->MenuLink->Behaviors->detach('Tree');
            $this->Controller->MenuLink->Behaviors->attach('Tree',
                array(
                    'parent' => 'parent_id',
                    'left' => 'lft',
                    'right' => 'rght',
                    'scope' => "MenuLink.menu_id = '{$current['MenuLink']['menu_id']}'"
                )
            );

            $path = $this->Controller->MenuLink->getPath($current['MenuLink']['id']);

            if (isset($path[0]['MenuLink']['link_title'])) {
                $path[0]['MenuLink']['link_title'] = __t($path[0]['MenuLink']['link_title']);
            }

            $this->Controller->set('breadCrumb', $path);
        }

        return;
    }

/**
 * Insert custom block in stack.
 *
 * @param array $data Formatted block array
 * @param string $region Theme region where to push
 * @return boolean TRUE on sucess, FALSE otherwise
 */
    public function blockPush($block = array(), $region = null) {
        $_block = array(
            'title' => '',
            'pages' => '',
            'visibility' => 0,
            'body' => '', #
            'region' => null,
            'format' => null #
        );

        $block = array_merge($_block, $block);
        $block['module'] = null;
        $block['id'] = null;
        $block['delta'] = null;

        if (!is_null($region)) {
            $block['region'] = $region;
        }

        if (empty($block['region']) || empty($block['body'])) {
            return false;
        }

        $__block  = $block;

        unset($__block['format'], $__block['body'], $__block['region'], $__block['theme']);

        $Block = array(
            'Block' => $__block,
            'BlockCustom' => array(
                'body' => $block['body'],
                'format' => $block['format']
            ),
            'BlockRegion' => array(
                0 => array(
                    'theme' => $this->Controller->theme,
                    'region' => $block['region']
                )
            )
        );

        $this->Controller->Layout['blocks'][] = $Block;
    }

/**
 * Collect and cache information about all active modules.
 * ###Usage
 *  {{{
 *   Configure::read('Modules.YourModuleName');
 *  }}}
 *
 * @return void
 */
    public function loadModules() {
        $modules = Cache::read('Modules');

        if ($modules === false) {
            $Modules = array();
            $modules = $this->Controller->Module->find('all', array('recursive' => -1));

            foreach ($modules as $m) {
                $v = $m['Module'];

                CakePlugin::load($m['Module']['name']);

                $v['path'] = App::pluginPath($m['Module']['name']);
                $yamlFile = (strpos($m['Module']['name'], 'Theme') === 0) ? dirname(dirname($v['path'])) . DS . basename(dirname(dirname($v['path']))) . '.yaml' : $v['path'] . "{$m['Module']['name']}.yaml";
                $v['yaml'] = file_exists($yamlFile) ? Spyc::YAMLLoad($yamlFile) : array();

                $Modules[$m['Module']['name']] = $v;
            }

            Configure::write('Modules', $Modules);
            Cache::write('Modules', $Modules);
        } else {
            Configure::write('Modules', $modules);
        }
    }

/**
 * Checks User is logged in.
 *
 * @return boolean
 */
    public function loggedIn() {
        return $this->Controller->Session->check('Auth.User.id');
    }

/**
 * Retuns current user roles.
 *
 * @return array Associative array with id and names of the roles: array(id:integer => name:string, ...)
 */    
    public function userRoles() {
        $roles = array();

        if (!$this->loggedIn()) {
            $roles[] = 3;
        } else {
            $roles = $this->Controller->Session->read('Auth.User.role_id');
        }

        return $roles;        
    }

/**
 * Check if the logged user has admin privileges.
 *
 * @return boolean
 */ 
    public function isAdmin() {
        return ($this->Controller->Auth->user() && in_array(1, (array)$this->Controller->Auth->user('role_id')));
    }

    private function __urlChunk() {
        $url = '/' . $this->Controller->request->url;
        $out = array($url);

        foreach ($this->Controller->request->params['named'] as $key => $val) {
            $url = str_replace_once("/{$key}:{$val}", '', $url);
            $out[] = $url;
        }

        $out[] = $url;

        if ($this->Controller->request->params['controller'] == Inflector::underscore($this->plugin)) {
            $url =  str_replace_once("/{$this->Controller->request->params['controller']}", '', $url);
            $out[] = $url;
        } else if ($this->Controller->request->params['action'] == 'index' || $this->Controller->request->params['action'] == 'admin_index') {
            $url =  str_replace_once("/index", '', $url);
            $out[] = $url;
        }

        foreach ($this->Controller->request->params['pass'] as $p) {
            $url = str_replace_once("/{$p}", '', $url);
            $out[] = $url;
        }

        return array_unique($out);
    }
}