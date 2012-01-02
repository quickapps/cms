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
        if (isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'comment' &&
            $this->request->params['controller'] = 'list' &&
            $this->request->params['action'] == 'admin_show'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- CommentHookHelper -->'), 'toolbar');
        }

        if (!isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            in_array($this->request->params['controller'], array('node')) &&
            $this->request->params['action'] == 'details'
        ) {
            if ($this->_View->Layout->nodeField('comment') == 2) {
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/locale/' . Configure::read('Variable.language.code') . '.js';
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/jquery.markitup.js';
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/markItUp/sets/bbcode/set.js';
                $this->_View->viewVars['Layout']['javascripts']['file'][] = '/comment/js/jquery.scrollTo-min.js';
                $this->_View->viewVars['Layout']['javascripts']['inline'][] = "
                    $(document).ready(function()    {
                        $('#CommentBody').markItUp(MerkeItUpBbcodeSettings);
                    });";

                $this->_View->viewVars['Layout']['stylesheets']['all'][] = '/comment/js/markItUp/sets/bbcode/style.css';
                $this->_View->viewVars['Layout']['stylesheets']['all'][] = '/comment/js/markItUp/skins/simple/style.css';
            }
        }

        return true;
    }

    public function comment_captcha() {
        $out = '';

        if (Configure::read('Modules.Comment.settings.use_recaptcha') &&
            Configure::read('Modules.Comment.settings.recaptcha.private_key') &&
            Configure::read('Modules.Comment.settings.recaptcha.public_key')
        ) {
            if (!defined('RECAPTCHA_API_SERVER')) {
                App::import('Lib', 'Comment.Recaptcha');
            }

            $settings = Set::merge(
                array(
                    'custom_translations' => array(
                        'instructions_visual' => '',
                        'instructions_audio' => '',
                        'play_again' => '',
                        'cant_hear_this' => '',
                        'visual_challenge' => '',
                        'audio_challenge' => '',
                        'refresh_btn' => '',
                        'help_btn' => '',
                        'incorrect_try_again' => ''
                    ),
                    'lang' => 'en',
                    'theme' => 'red'
                ), Configure::read('Modules.Comment.settings.recaptcha')
            );
            $out .= '<div class="input text required ' . (CakeSession::read('invalid_recaptcha') ? 'error' : '') . '">';
            $out .= '<script type="text/javascript">';
            $out .= 'var RecaptchaOptions = {';

            if ($settings['lang'] != 'custom') {
                $out .= "lang: '{$settings['lang']}',";
            } else {
                $out .= 'custom_translations: {';
                $strings = array();

                foreach ($settings['custom_translations'] as $key => $str) {
                    if (empty($str)) {
                        continue;
                    }

                    $strings[] = "{$key}: '" . str_replace("'", "\'", $str) . "'";
                }

                $out .= implode(",\n", $strings);
                $out .= '},';
            }

            $out .= "theme: '{$settings['theme']}'";
            $out .= '}';
            $out .= '</script>';
            $out .= recaptcha_get_html(Configure::read('Modules.Comment.settings.recaptcha.public_key'));

            if (CakeSession::read('invalid_recaptcha')) {
                $out .= '<div class="error-message">' . __d('comment', 'Invalid security code.') . '</div>';

                CakeSession::write('invalid_recaptcha' ,false);
            }

            $out .= '</div>';
        }

        return $out;
    }
}