<?php
/**
 * Layout Helper
 *
 * PHP version 5
 *
 * @package  QuickApps.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class LayoutHelper extends AppHelper {

/**
 * Used by some methods to cache data in order to improve
 * comunication between them, for example see LayoutHelper::blocksInRegion()
 */
    protected $_tmp = array();

/**
 * Render css files links
 *
 * @param array $stylesheets Asociative array of extra css elements to merge
 * {{{
 * array(
       'inline' => array("css code1", "css code2", ...)
 *     'print' => array("file1", "file2", ...),
 *     'all' => array("file3", "file4", ...),
 *      ....
 * );
 * }}
 * @return string HTML css link-tags and inline-styles
 * @see AppController::$Layout
 */
    public function stylesheets($stylesheets = array()) {
        $output = $inline = '';
        $stylesheets = Set::merge($this->_View->viewVars['Layout']['stylesheets'], $stylesheets);

        $this->hook('stylesheets_alter', $stylesheets); # pass css list array to modules

        foreach ($stylesheets as $media => $files) {
            foreach ($files as $file) {
                if ($media !== 'inline') {
                    $output .= "\n". $this->_View->Html->css($file, 'stylesheet', array('media' => $media));
                } else {
                    $inline .= "{$file}\n\n";
                }
            }
        }

        $output = !empty($inline) ? $output . "\n<style type=\"text/css\"><!--\t\n {$inline} \n--></style>\n" : $output;

        return $output;
    }

/**
 * Render js files links
 *
 * @param array $javascripts Asociative array of extra js elements to merge:
 * {{{
 * array(
 *     'inline' => array("code1", "code2", ...),
 *     'file' => array("path_to_file1", "path_to_file2", ...)
 * );
 * }}}
 * @return string HTML javascript link-tags and inline-code
 * @see AppController::$Layout
 */
    public function javascripts($javascripts = array()) {
        $output = '';
        $javascripts = Set::merge($this->_View->viewVars['Layout']['javascripts'], $javascripts);

        $this->hook('javascripts_alter', $javascripts);    # pass javascripts list to modules if they need to alter them

        # js files first
        $javascripts['file'] = array_unique($javascripts['file']);

        foreach ($javascripts['file'] as $file) {
            $output .= "\n" . $this->_View->Html->script($file);
        }

        # js inline code blocks after
        $c_blocks = "\n";
        $javascripts['inline'] = array_unique($javascripts['inline']);

        foreach ($javascripts['inline'] as $block) {
            $c_blocks .=  $block . "\n\n";
        }

        $output .= "\n" . $this->_View->Html->scriptBlock($c_blocks);

        return "\n" . $output . "\n";
    }

/**
 * Render extra code for header.
 * This function should be used by themes just before </head>.
 *
 * @return string HTML code to include in header.
 */
    public function header() {
        if (is_string($this->_View->viewVars['Layout']['header'])) {
            return $this->_View->viewVars['Layout']['header'];
        }

        if (is_array($this->_View->viewVars['Layout']['header'])) {
            $out = '';

            foreach ($this->_View->viewVars['Layout']['header'] as $code) {
                $out .= "{$code}\n";
            }
        }

        return "\n" . $out;
    }

/**
 * Shortcut for `title_for_layout`.
 *
 * @return string Current page's title
 */
    public function title() {
        $title = isset($this->_View->viewVars['Layout']['node']['Node']['title']) ? __t($this->_View->viewVars['Layout']['node']['Node']['title']) : Configure::read('Variable.site_name');
        $title = $this->_View->viewVars['title_for_layout'] != Inflector::camelize($this->_View->params['controller']) || Router::getParam('admin') ? $this->_View->viewVars['title_for_layout'] : $title;
        $this->hook('title_for_layout_alter', $title);    # pass title_for_layout to modules

        return $this->hooktags(__t($title));
    }

/**
 * Shortcut for `content_for_layout`
 *
 * @return string Current page's HTML content
 */
    public function content() {
        $content = $this->_View->viewVars['content_for_layout'];
        $this->hook('content_for_layout_alter', $content);    # pass content_for_layout to modules

        return $content;
    }

/**
 * Render extra code for footer.
 * This function should be used by themes just before </body>.
 *
 * @return string HTML code
 */
    public function footer() {
        if (is_string($this->_View->viewVars['Layout']['footer'])) {
            return $this->_View->viewVars['Layout']['header'];
        }

        if (is_array($this->_View->viewVars['Layout']['footer'])) {
            $out = '';

            foreach ($this->_View->viewVars['Layout']['footer'] as $code) {
                $out .= "{$code}\n";
            }
        }

        return "\n" . $out;
    }

/**
 * Return all meta-tags for the current page.
 * This function should be used by themes between <head> and </head> tags.
 *
 * @param array $metaForLayout Optional asociative array of aditional meta-tags to
 *                             merge with Layout metas `meta_name => content`.
 * @return string HTML formatted meta tags.
 * @see AppController::$Layout
 */
    public function meta($metaForLayout = array()) {
        if (!is_array($metaForLayout) || empty($metaForLayout)) {
            $metaForLayout = Set::merge($this->_View->viewVars['Layout']['meta'], $metaForLayout);
        }

        $output = '';

        foreach ($metaForLayout as $name => $content) {
            $output .= $this->_View->Html->meta($name, $content) . "\n";
        }

        return $output;
    }

/**
 * Returns node type of the current node's being renderend.
 * (Valid only when rendering a single node [viewMode = full])
 *
 * @return mixed String ID of the NodeType or false if could not be found.
 */
    public function getNodeType() {
        if (!isset($this->_View->viewVars['Layout']['node']['NodeType']['id'])) {
            return false;
        }

        return $this->_View->viewVars['Layout']['node']['NodeType']['id'];
    }

/**
 * Returns specified node's field.
 * (Valid only when rendering a single node [viewMode = full])
 *
 * @param string $field Node field name to retrieve.
 * @return mixed Array of the field if exists. FALSE otherwise.
 */
    public function nodeField($field = false) {
        if (!is_string($field)) {
            return false;
        }

        if ($field == 'node_type_id') {
            return $this->getNodeType();
        }

        if (isset($this->_View->viewVars['Layout']['node']['Node'][$field])) {
            return $this->_View->viewVars['Layout']['node']['Node'][$field];
        }

        return false;
    }

/**
 * Render a specified Node or `current` Node, render based on NodeType.
 * Node rendering hook is called based on NodeType, but if is there is no response
 * then default rendering proccess is fired.
 *
 * @param mixed $node Optional:
 *                    - boolean FALSE: current node will be rendered. (by default)
 *                    - string SLUG: render node by node's slug.
 *                    - array : asociative Node's array to render.
 * @param array $options Node rendering options:
 *                       - mixed class: array or string, extra CSS class(es) for node DIV container
 *                       - mixed viewMode: set to string value to force rendering viewMode. set to boolean false for automatic.
 * @return string HTML formatted node. Empty string ('') will be returned if node could not be rendered.
 */
    public function renderNode($node = false, $options = array()) {
        $options = array_merge(
            array(
                'class' => array(),
                'viewMode' => false
            )
        , $options);

        extract($options);

        $nodeClasses = !is_array($class) ? array($class) : $class;

        if ($node === false) {
            $node = $this->_View->viewVars['Layout']['node'];
        } elseif (is_string($node)) {
            $node = ClassRegistry::init('Node.Node')->findBySlug($node);
        } elseif (!is_array($node)) {
            return '';
        }

        if (empty($node)) {
            return '';
        }

        $content = '';
        $view_mode = $viewMode !== false ? $viewMode : $this->_View->viewVars['Layout']['viewMode'];

        foreach ($node['Field'] as $key => &$data) {
            # undefined viewMode -> use default
            if (!isset($data['settings']['display'][$view_mode]) && isset($data['settings']['display']['default'])) {
                $data['settings']['display'][$view_mode] = $data['settings']['display']['default'];
            }
        }

        $node['Field'] = Set::sort($node['Field'], "{n}.settings.display.{$view_mode}.ordering", 'asc');
        $sufix = $node['NodeType']['module'] == 'Node' ? 'render' : $node['NodeType']['id'];
        $callback = "{$node['NodeType']['base']}_{$sufix}";
        $beforeRender = (array)$this->hook('before_render_node', $node, array('collectReturn' => true));

        if (in_array(false, $beforeRender, true)) {
            return '';
        }

        $content .= implode('', $beforeRender);
        $content_callback = $this->hook($callback, $node, array('collectReturn' => false));

        if (empty($content_callback)) {
            $content .= "<h1>" . __d('system', 'The node could not be rendered') . "</h1>";
        } else {
            $content .= $content_callback;
        }

        $content .= implode('', (array)$this->hook('after_render_node', $node, array('collectReturn' => true)));
        $content = "\n\t" . $this->hooktags($content) . "\n";

        if (isset($this->_tmp['renderedNodes'])) {
            $this->_tmp['renderedNodes']++;
        } else {
            $this->_tmp['renderedNodes'] = 1;
        }

        if (isset($node['Node']['params']['class'])) {
            $nodeClasses = array_merge($nodeClasses, explode(' ', preg_replace('/\s{2,}/', ' ', $node['Node']['params']['class'])));
        }

        $nodeClasses = array_merge(
            array(
                'node',
                "node-{$node['NodeType']['id']}",
                "node-{$this->_View->viewVars['Layout']['viewMode']}",
                "node-" . ($node['Node']['promote'] ? "promoted" : "demote"),
                "node-" . ($node['Node']['sticky'] ? "sticky" : "nosticky"),
                "node-" . ($this->_tmp['renderedNodes']%2 ? "odd" : "even")
            ),
            $nodeClasses);

        $div = "\n" . $this->_View->Html->div(implode(' ', $nodeClasses), $content, array('id' => "node-{$node['Node']['id']}")) . "\n";

        return $div;
    }

/**
 * Wrapper for field rendering hook.
 *
 * @param array $field Field information array.
 * @param boolean $edit Set to TRUE for edit form. FALSE for view mode.
 * @return string HTML formatted field.
 */
    public function renderField($field, $edit = false) {
        if (isset($field['settings']['display'][$this->_View->viewVars['Layout']['viewMode']]['type']) &&
            $field['settings']['display'][$this->_View->viewVars['Layout']['viewMode']]['type'] == 'hidden'
        ) {
            return '';
        }

        $field['label'] = $this->hooktags($field['label']);
        $viewVars = array('data' => $field);

        if ($edit) {
            $view = 'edit';
            $field['label'] .= $field['required'] ? ' *' : '';
            $field['description'] = !empty($field['description']) ? $this->hooktags($field['description']) : '';
        } else {
            $viewMode = isset($field['settings']['display'][$this->_View->viewVars['Layout']['viewMode']]) ? $this->_View->viewVars['Layout']['viewMode'] : 'default';

            if (isset($field['settings']['display'][$viewMode]['type']) && $field['settings']['display'][$viewMode]['type'] != 'hidden') {
                $view = 'view';
                $viewVars['viewMode'] = $viewMode;
                $viewVars['display'] = $field['settings']['display'][$viewMode];
            } else {
                return '';
            }
        }

        $data = array('field' => $field, 'edit' => $edit);
        $beforeRender = (array)$this->hook('before_render_field', $data, array('collectReturn' => true));

        if (in_array(false, $beforeRender, true)) {
            return '';
        }

        extract($data);

        $result = $this->_View->element($view, 
            $viewVars, 
            array('plugin' => Inflector::camelize($field['field_module']))
        );

        if (!empty($result)) {
            $result .= implode('', (array)$this->hook('after_render_field', $data, array('collectReturn' => true)));
            $result = "\n\t" . $this->hooktags($result) . "\n";

            return "\n<div class=\"field-container field-name-{$field['name']}\">{$result}</div>\n";
        }

        return '';
    }

/**
 * Show flash message.
 *
 * @return string.
 */
    public function sessionFlash() {
        $messages = $this->Session->read('Message');

        if (is_array($messages)) {
            $out = '';

            foreach (array_keys($messages) as $key) {
                $out .= $this->Session->flash($key);
            }

            return $out;
        } elseif (is_string($messages)) {
            return $messages;
        }

        return false;
    }

/**
 * Return rendered breadcrumb. Data is passed to themes for formatting the crumbs.
 * Default formatting is fired in case of no theme format-response.
 *
 * @return string HTML formatted breadcrumb
 */
    public function breadCrumb() {
        $b = $this->_View->viewVars['breadCrumb'];
        $beforeRender = (array)$this->hook('before_render_breadcrumb', $b, array('collectReturn' => true));

        if (in_array(false, $beforeRender, true)) {
            return '';
        }

        $crumbs = $this->_View->element('theme_breadcrumb', array('breadcrumb' => $b));
        $crumbs .= implode('', (array)$this->hook('after_render_breadcrumb', $b, array('collectReturn' => true)));
        $crumbs = $this->hooktags($crumbs);

        return $crumbs;
    }

/**
 * Render child nodes of the given menu node (father).
 *
 * @param mixed $path String path of the father node or boolen false to use current path
 * @param string $region Theme region where the child nodes will be rendered, 'content' by default
 * @return string Html rendered menu
 */
    public function menuNodeChildren($path = false, $region = 'content') {
        $output = '';

        if (!$path) {
            $base = Router::url('/');
            $path = '/';
            $path .= $base !== '/' ? str_replace($base, '', $this->_View->here) : $this->_View->here;
            $path = preg_replace("/\/{2,}/i", '/', $path);
        }

        $MenuLink = Classregistry::init('Menu.MenuLink');
        $here = $MenuLink->find('first',
            array(
                'conditions' => array(
                    'MenuLink.router_path' => $path,
                    'MenuLink.status' => 1
                )
            )
        );

        if (!empty($here)) {
            $subs = $MenuLink->children($here['MenuLink']['id']);
            $_subs['MenuLink'] = Set::extract('{n}.MenuLink', $subs);

            if (empty($_subs['MenuLink'])) {
                return '';
            }

            $_subs['region'] = $region;
            $_subs['id'] = 'no-id';

            foreach ($_subs['MenuLink'] as &$node) {
                $tt = __t($node['link_title']);
                $dt = __t($node['description']);
                $node['link_title'] = $tt != $node['link_title'] ? $tt : __d(Inflector::underscore($node['module']), $node['link_title']);
                $node['description'] = $dt != $node['description'] ? $dt : __d(Inflector::underscore($node['module']), $node['description']);
            }

            $output = $this->_View->element('theme_menu', array('menu' => $_subs));
        }

        return $output;
    }

/**
 * Wrapper method to MenuHelper::generate()
 *
 * @param array $menu Array of links to render
 * @param array $settings Optional, customization options for menu rendering process
 * @return string HTML rendered menu
 * @see MenuHelper::generate
 */
    public function menu($menu, $settings = array()) {
        $data = array(
            'menu' => $menu,
            'settings' => $settings
        );

        $this->hook('menu_alter', $data);
        extract($data);

        return $this->Menu->generate($menu, $settings);
    }

/**
 * Check is the page being viewed is the site frontpage.
 *
 * @return boolean TRUE if is frontpage. FALSE otherwise.
 */
    public function isFrontpage() {
        return ($this->_View->plugin == 'Node' &&
                $this->_View->params['action'] == 'index' &&
                !Configure::read('Variable.site_frontpage')
        );
    }

/**
 * Checks user session.
 *
 * @return boolean, TRUE if user is logged in. FALSE otherwise.
 */
    public function loggedIn() {
        return $this->Session->check('Auth.User.id');
    }

/**
 * Check if the logged user has admin privileges
 *
 * @return boolean
 */
    public function isAdmin() {
        return in_array(1, (array)$this->userRoles());
    }

/**
 * Retuns current user roles
 *
 * @return array associative array with id and names of the roles: array(id:integer => name:string, ...)
 */
    public function userRoles() {
        $roles = array();

        if (!$this->loggedIn()) {
            $roles[] = 3;
        } else {
            $roles = CakeSession::read('Auth.User.role_id');
        }

        return $roles;
    }

/**
 * Generates user's avatar image.
 *
 * @param array $user Optional user data, current logged user data will be used otherwise
 * @param array $options extra Options for Html->image()
 * @return HTML <img>
 */
    public function userAvatar($user = false, $options = array()) {
        $__options = array(
            'class' => 'user-avatar'
        );

        if (!$user) {
            $user = $this->Session->read('Auth.User');
        }

        if (!isset($user['User'])) {
            return '';
        }

        if (isset($user['User']['avatar']) && !empty($user['User']['avatar'])) {
            $avatar = $user['User']['avatar'];
        } else {
            if (!Configure::read('Variable.user_default_avatar')) {
                if (isset($user['User']['email']) && !empty($user['User']['email'])) {
                    $hash = md5(strtolower(trim("{$user['User']['email']}")));
                } else {
                    $hash = md5(strtolower(trim("")));
                }

                $avatar = "http://www.gravatar.com/avatar/{$hash}";
            } else {
                $avatar = Configure::read('Variable.user_default_avatar');
            }
        }

        $options = array_merge($__options, $options);
        $html = $this->_View->Html->image($avatar, $options);

        $this->hook('after_render_user_avatar', $html);

        return $html;
    }

/**
 * Manually insert a custom block to stack.
 *
 * @param array $block Formatted block array:
 *     - title
 *     - pages
 *     - visibility
 *     - body
 *     - region
 *     - theme
 *     - format
 * @param string $region Theme region
 * @return boolean TRUE on success. FALSE otherwise.
 */
    public function blockPush($block = array(), $region = '') {
        $_block = array(
            'title' => '',
            'pages' => '',
            'visibility' => 0,
            'body' => '',
            'region' => null,
            'theme' => null,
            'format' => null
        );

        $block = array_merge($_block, $block);
        $block['module'] = null;
        $block['id'] = null;
        $block['delta'] = null;

        if (!empty($region)) {
            $block['region'] = $region;
        }

        if (is_null($block['theme'])) {
            $block['theme'] =  $this->themeName();
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
                    'theme' => $this->themeName(),
                    'region' => $block['region']
                )
            )
        );

        $this->_View->viewVars['Layout']['blocks'][] = $Block;
        $this->_tmp['blocksInRegion'][$region][] = $Block;

        return true;
    }

/**
 * Creates a simple plain (deph 0) menu list.
 * Useful when creating backend submenu buttons.
 *
 * @param array $links Array of links: array('title', '/your/url/')
 * @param array $options Array of options:
 *      `type`: type of list, ol, ul. default: ul
 *      `id`: id attribute for the container (ul, ol)
 *      `itemType`: type of child node. default: li
 *      `selectedClass`: class attribute for selected itemType. default: `selected`
 * @return string HTML
 */
    public function toolbar($links, $options = array()) {
        $data = array('links' => $links, 'options' => $options);
        $this->hook('toolbar_alter', $data, array('collectReturn' => true));

        extract($data);

        $_options = array(
            'id' => null,
            'type' => 'ul',
            'itemType' => 'li',
            'selectedClass' => 'selected'
        );

        $options = array_merge($_options, $options);

        extract($options);

        $id = !is_null($id) ? " id=\"{$id}\" " : '';
        $o = "<{$type}{$id}>\n";
        $here = preg_replace("/\/{2,}/i", '/', str_replace($this->_View->base, '', $this->_View->here) . "/");

        foreach ($links as $link) {
            $link[1] = preg_replace("/\/{2,}/i", '/', "{$link[1]}/");
            $selected =   strpos($here, $link[1]) !== false  ? " class=\"{$selectedClass}\" " : '';
            $link = isset($link[2]) && is_array($link[2]) ? $this->_View->Html->link($link[0], $link[1], $link[2]) : $this->_View->Html->link($link[0], $link[1]);
            $o .= "\t<{$itemType}{$selected}><span>" . $link . "</span></{$itemType}>\n";
        }

        $o .= "\n</{$type}>";

        return $o;
    }

/**
 * Returns current theme's machine name (CamelCased).
 *
 * @return string Theme name in CamelCase
 */
    public function themeName() {
        return Configure::read('Theme.info.folder');
    }

/**
 * Checks if the given theme region is empty or not.
 *
 * @param string $region Region alias
 * @return boolean TRUE no blocks in region, FALSE otherwise.
 */
    public function emptyRegion($region) {
        return ($this->blocksInRegion($region) == 0);
    }

/**
 * Returns the numbers of blocks in the specified region.
 *
 * @param string $region Region alias to check
 * @return integer Number of blocks
 */
    public function blocksInRegion($region) {
        if (isset($this->_tmp['blocksInRegion'][$region])) {
            return count($this->_tmp['blocksInRegion'][$region]);
        }

        $blocks_in_theme = Set::extract("/BlockRegion[theme=" . $this->themeName() . "]/..", $this->_View->viewVars['Layout']['blocks']);
        $blocks_in_region = Set::extract("/BlockRegion[region={$region}]/..", $blocks_in_theme);
        $t = 0;

        foreach ($blocks_in_region as $key => $block) {
            $themes = Set::extract('/BlockRegion/theme', $block);

            if (!in_array($this->themeName(), $themes)) {
                continue;
            }

            $found = false;

            foreach ($block['BlockRegion'] as $br) {
                if ($br['region'] == $region && $br['theme'] == $this->themeName()) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                continue;
            }

            if (!empty($block['UserRole'])) {
                $roles_id = Set::extract('/UserRole/id', $block);
                $allowed = false;

                foreach ($this->userRoles() as $role) {
                    if (in_array($role, $roles_id)) {
                        $allowed = true;
                        break;
                    }
                }
            }

            switch ($block['Block']['visibility']) {
                case 0:
                    $allowed = $this->urlMatch($block['Block']['pages']) ? false : true;
                break;

                case 1:
                    $allowed = $this->urlMatch($block['Block']['pages']) ? true : false;
                break;

                case 2:
                    $allowed = $this->php_eval($block['Block']['pages']);
                break;
            }

            if (!$allowed) {
                continue;
            }

            if (!isset($this->_tmp['blocksInRegion'][$region]['blocks_ids']) ||
                !in_array($block['Block']['id'], $this->_tmp['blocksInRegion'][$region]['blocks_ids'])
            ) {
                $this->_tmp['blocksInRegion'][$region][] = $block;                              # Cache improve
                $this->_tmp['blocksInRegion'][$region]['blocks_ids'][] = $block['Block']['id']; # Cache improve
            }

            $t++;
        }

        return ($t > 0);
    }

/**
 * Render all blocks for a particular region
 *
 * @param string $region Region alias to render.
 * @return string Html blocks
 */
    public function blocks($region) {
        if (!$this->emptyRegion($region)) {
            $output = '';

            if (isset($this->_tmp['blocksInRegion'][$region])) {
                $blocks = $this->_tmp['blocksInRegion'][$region];
            } else {
                $blocks = Set::extract("/BlockRegion[region={$region}]/..",
                    Set::extract("/BlockRegion[theme=" . $this->themeName() . "]/..", $this->_View->viewVars['Layout']['blocks'])
                );
            }

            foreach ($blocks as &$block) {
                if (isset($block['BlockRegion'])) {
                    foreach ($block['BlockRegion'] as $key => $br) {
                        if (!($br['theme'] == $this->themeName() && $br['region'] == $region)) {
                            unset($block['BlockRegion'][$key]);
                        }
                    }
                }
            }

            $blocks = Set::sort($blocks, '{n}.BlockRegion.{n}.ordering', 'asc');
            $i = 1;
            $total = count($blocks);

            foreach ($blocks as $block) {
                $block['Block']['__region'] = $region;
                $block['Block']['__weight'] = array($i, $total);

                if ($o = $this->block($block)) {
                    $output .= $o;
                    $i++;
                }
            }

            $_data = array('html' => $output, 'region' => $region);
            $this->hook('after_render_blocks', $_data, array('collectReturn' => false)); // pass all rendered blocks (HTML) to modules

            extract($_data);

            return $html;
        }

        return '';
    }

/**
 * Render single block
 *
 * @param array $block Well formated block array.
 * @param array $options Array of options:
 *                       - boolean title: Render title. default true.
 *                       - boolean body: Render body. default true.
 *                       - string region: Region where block belongs to.
 *                       - array params: extra options used by block.
 * @return string Html
 */
    public function block($block, $options = array()) {
        $options = array_merge(
            array(
                'title' => true,
                'body' => true,
                'region' => true,
                'params' => array()
            ),
            $options
        );

        $block['Block']['__region'] = !isset($block['Block']['__region']) ? '' : $block['Block']['__region'];
        $block['Block']['__weight'] = !isset($block['Block']['__weight']) ? array(0, 0) : $block['Block']['__weight'];

        if (isset($block['Block']['locale']) &&
            !empty($block['Block']['locale']) &&
            !in_array(Configure::read('Variable.language.code'), $block['Block']['locale'])
        ) {
            return;
        }

        if (!empty($block['Role'])) {
            $roles_id = Set::extract('/Role/id', $block);
            $allowed = false;

            foreach ($this->userRoles() as $role) {
                if (in_array($role, $roles_id)) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                return;
            }
        }

        $region = $block['Block']['__region'];

        /**
         * Check visibility
         * 0 = Show on all pages except listed pages
         * 1 = Show only on listed pages
         * 2 = Use custom PHP code to determine visibility
         */
        switch ($block['Block']['visibility']) {
            case 0:
                $allowed = $this->urlMatch($block['Block']['pages']) ? false : true;
            break;

            case 1:
                $allowed = $this->urlMatch($block['Block']['pages']) ? true : false;
            break;

            case 2:
                $allowed = $this->php_eval($block['Block']['pages']);
            break;
        }

        if (!$allowed) {
            return; #skip if is not allowed
        }

        $Block = array(
            'id' => $block['Block']['id'],
            'module' => $block['Block']['module'],
            'delta' => $block['Block']['delta'],
            'title' => $block['Block']['title'],
            'body' => null,
            'region' => $region,
            'description' => null,
            'format' => null,
            'params' => (isset($block['Block']['params']) ? $block['Block']['params'] : array())
        );

        if (!empty($block['Menu']['id']) && $block['Block']['module'] == 'Menu') {
            // menu block
            $block['Menu']['region'] = $region;
            $Block['title'] = empty($Block['title']) ? $block['Menu']['title'] : $Block['title'];
            $Block['body'] = $this->_View->element('theme_menu', array('menu' => $block['Menu']));
            $Block['description'] = $block['Menu']['description'];
        } elseif (!empty($block['BlockCustom']['body'])) {
            // custom block
            $Block['body'] = @$block['BlockCustom']['body'];
            $Block['format'] = @$block['BlockCustom']['format'];
            $Block['description'] = @$block['BlockCustom']['description'];
        } else {
            // module block
            // module hook must return formated array block
            $Block = $this->hook("{$block['Block']['module']}_{$block['Block']['delta']}", $block, array('collectReturn' => false));

            if (empty($Block)) {
                return false;
            }

            if (!isset($Block['params'])) {
                $Block['params'] = (isset($block['Block']['params']) ? $block['Block']['params'] : array());
            }

            $Block['id'] = $block['Block']['id'];
            $Block['module'] = $block['Block']['module'];
            $Block['delta'] = $block['Block']['delta'];
            $Block['region'] = $region;
            $Block['title'] = !isset($Block['title']) ? $block['Block']['title'] : $Block['title'];
        }

        $Block['weight'] = $block['Block']['__weight']; // X of total

        if ($options['title']) {
            $Block['title'] = $this->hooktags($Block['title']);
        } else {
            unset($Block['title']);
        }

        if ($options['body']) {
            $Block['body'] = $this->hooktags($Block['body']);
        } else {
            unset($Block['body']);
        }

        if (!$options['region']) {
            $Block['region'] = null;
        }

        if ($options['params']) {
            $options['params'] = !is_array($options['params']) ? array($options['params']) : $options['params'];
            $Block['params'] = $options['params'];
        }

        $this->hook('block_alter', $Block, array('collectReturn' => false)); // pass block array to modules

        $out = $this->_View->element('theme_block', array('block' => $Block)); // try theme rendering
        $data = array(
            'html' => $out,
            'block' => $Block
        );

        $this->hook('after_render_block', $data, array('collectReturn' => false));
        extract($data);

        return $html;
    }

/**
 * Parse string for special hooktags placeholders and replace them with the corresponding hooktag method return.
 * Placeholder example:
 * {{{
 *     [hook_function param1=text param=2 param3=0 ... /]
 *     [other_hook_function]only content & no params[/other_hook_function]
 * }}}
 *
 * @return string HTML with all placeholders replaced.
 */
    public function hooktags($text) {
        $text = $this->specialTags($text);

        if (!empty($this->_tmp['__hooktags_reg'])) {
            $tags = $this->_tmp['__hooktags_reg'];
        } else {
            $tags = $this->_tmp['__hooktags_reg'] = implode('|', $this->_View->HookCollection->hooktagsList());
        }

        return preg_replace_callback('/(.?)\[(' . $tags . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', array($this->_View->HookCollection, 'doHooktag'), $text);
    }

/**
 * Removes all hooktags from the given content (except special tags).
 * Useful for plain text converting.
 *
 * @param string $string Text to remove hooktags.
 * @return string Content without hooktags.
 */
    public function stripHooktags($string) {
        $string = $this->specialTags($string);

        if (!empty($this->_tmp['__hooktags_reg'])) {
            $tags = $this->_tmp['__hooktags_reg'];
        } else {
            $tags = $this->_tmp['__hooktags_reg'] = implode('|', $this->_methods['Hooktags']);
        }

        return preg_replace('/(.?)\[(' . $tags . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', '$1$6', $string);
    }

/**
 * Special hooktags that are not managed by any modules:
 *  `[date=FORMAT]` Return current date(FORMAT).
 *  `[rand={values,by,comma}]` Returns a radom value from the specified group.
 *                             If only two numeric values are given as group, then rand(num1, num2) is returned.
 *  `[language.OPTION]` Current language option (code, name, native, direction).
 *  `[language]` Shortcut to [language.code] which return current language code.
 *  `[url]YourURL[/url]` or `[url=YourURL]` Formatted url.
 *  `[url=LINK]LABEL[/url]` Returns link tag <href="LINK">LABEL</a>
 *  `[t=stringToTranslate]` or `[t]stringToTranslate[/t]` text translation: __t(stringToTranslate)
 *  `[t=domain@@stringToTranslate]` Translation by domain __d(domain, stringToTranslate)
 *  `[Layout.PATH]` Get any value from `Layout` variable. i.e.: [Layout.viewMode] gets current view mode
 *                  if path does not exists then '' (empty) is rendered instead the hooktag code.
 *
 * @param string $text original text where to replace tags
 * @return string
 */
    public function specialTags($text) {
        //[locale]
        $text = str_replace('[language]', Configure::read('Variable.language.code'), $text);

        //[locale.OPTION]
        preg_match_all('/\[language.(.+)\]/iUs', $text, $localeMatches);
        foreach ($localeMatches[1] as $attr) {
            $text = str_replace("[language.{$attr}]", Configure::read('Variable.language.' .$attr), $text);
        }

        //[url]URL[/url]
        preg_match_all('/\[url\](.+)\[\/url\]/iUs', $text, $urlMatches);
        foreach ($urlMatches[1] as $url) {
            $text = str_replace("[url]{$url}[/url]", Router::url($url, true), $text);
        }

        //[url=URL]
        preg_match_all('/\[url\=(.+)\]/iUs', $text, $urlMatches);
        foreach ($urlMatches[1] as $url) {
            $text = str_replace("[url={$url}]", Router::url($url, true), $text);
        }

        //[t=text to translate]
        preg_match_all('/\[t\=(.+)\]/iUs', $text, $tMatches);
        foreach ($tMatches[1] as $string) {
            $text = str_replace("[t={$string}]", __t($string), $text);
        }

        //[t]text to translate[/t]
        preg_match_all('/\[t\](.+)\[\/t\]/iUs', $text, $tMatches);
        foreach ($tMatches[1] as $string) {
            $text = str_replace("[t]{$string}[/t]", __t($string), $text);
        }

        //[t=domain@@text to translate]
        preg_match_all('/\[t\=(.+)\@\@(.+)\]/iUs', $text, $dMatches);
        foreach ($dMatches[1] as $key => $domain) {
            $text = str_replace("[d={$domain}@@{$dMatches[2][$key]}]", __d($domain, $dMatches[2][$key]), $text);
        }

        //[date=FORMAT@@TIME_STAMP]
        preg_match_all('/\[date\=(.+)\@\@(.+)\]/iUs', $text, $dateMatches);
        foreach ($dateMatches[1] as $key => $format) {
            $stamp = $dateMatches[2][$key];
            $replace = is_numeric($stamp) ? date($format, $stamp) : date($format, strtotime($stamp));
            $text = str_replace("[date={$format}@@{$stamp}]", $replace, $text);
        }

        //[date=FORMAT]
        preg_match_all('/\[date\=(.+)\]/iUs', $text, $dateMatches);
        foreach ($dateMatches[1] as $format) {
            $text = str_replace("[date={$format}]", date($format), $text);
        }

        //[rand=a,b,c]
        preg_match_all('/\[rand\=(.+)\]/iUs', $text, $randomMatches);
        foreach ($randomMatches[1] as $_values) {
            $values = explode(',', $_values);
            $values = array_map('trim', $values);
            $c = count($values);

            if ($c == 2 && is_numeric($values[0]) && is_numeric($values[1])) {
                $replace = rand($values[0], $values[1]);
            } else {
                $replace = $values[rand(0, $c-1)];
            }

            $text = str_replace("[rand={$_values}]", $replace, $text);
        }

        //[Layout.PATH]
        preg_match_all('/\[Layout.(.+)\]/iUs', $text, $layoutPaths);
        foreach ($layoutPaths[1] as $path) {
            $extract = Set::extract("{$path}", $this->_View->viewVars['Layout']);
            $text = str_replace("[Layout.{$path}]", $extract, $text);
        }

        # pass text to modules so they can apply their own special tags
        $this->hook('special_tags_alter', $text);

        return $text;
    }
}