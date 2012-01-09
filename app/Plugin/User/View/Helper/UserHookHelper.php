<?php
/**
 * User View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.User.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class UserHookHelper extends AppHelper {
    // Toolbar Block
    public function beforeLayout($layoutFile) {
        if (Router::getParam('admin') &&
            $this->request->params['plugin'] == 'user' &&
            $this->request->params['action'] == 'admin_index'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        return true;
    }

    // Block, last registered users
    public function user_new($block) {
        return array(
            'title' => __t("Who's New"),
            'body' => $this->_View->element('User.user_new_block', array('block' => $block))
        );
    }

    // Block, whos new
    public function user_new_settings() {
        return $this->_View->element('User.user_new_block_settings');
    }

    // Block, user login form
    public function user_login() {
        return array(
            'title' => __t('Login'),
            'body' => $this->_View->element('User.user_login_block')
        );
    }
}