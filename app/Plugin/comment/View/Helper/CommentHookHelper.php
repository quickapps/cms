<?php
/**
 * Comment View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Comment.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class CommentHookHelper extends AppHelper {
    public function beforeLayout($layoutFile) {
        # content list toolbar:
        $showOn = (
            isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'comment' &&
            in_array($this->request->params['controller'], array('published', 'unpublished')) &&
            $this->request->params['action'] == 'admin_index'
        );

        $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- CommentHookHelper -->' ), 'toolbar', $showOn);

        $markeItUp_showOn = (
            !isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            in_array($this->request->params['controller'], array('node')) &&
            $this->request->params['action'] == 'details'
        );

        if ($markeItUp_showOn) {
            if ($this->_View->Layout->getNodeField('comment') == 2) {
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markitup/locale/' . Configure::read('Variable.language.code') . '.js';
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/jquery.markitup.js';
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markitup/sets/bbcode/set.js';
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/jquery.scrollTo-min.js';
                $this->_View->viewVars['Layout']['javascripts']['embed'][] = "
                    $(document).ready(function()    {
                        $('#CommentBody').markItUp(MerkeItUpBbcodeSettings);
                    });
                ";

                $this->_View->viewVars['Layout']['stylesheets']['all'][] = '/comment/js/markItUp/sets/bbcode/style.css';
                $this->_View->viewVars['Layout']['stylesheets']['all'][] = '/comment/js/markItUp/skins/simple/style.css';
            }
        }

        return true;
    }
}