<?php
/**
 * Theme Helper
 * Theme: Default
 *
 * PHP version 5
 *
 * @package  Quickapps.Theme.Default.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class ThemeDefaultHookHelper extends AppHelper {
/**
 * Display search form only on zero (0) results.
 *
 * @return void
 */
    public function stylesheets_alter(&$css) {
        if (count($this->_View->viewVars['Layout']['node'])) {
            $css['inline'][] = "#search-advanced { display:none; }";
        }
    }

/**
 * Adding toggle effect to advanced search form
 *
 * @return void
 */
    public function javascripts_alter(&$js) {
        if (
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'node' &&
            $this->request->params['action'] == 'search' &&
            !count($this->_View->viewVars['Layout']['node'])
        ) {
            $js['inline'][] = '
                $(document).ready(function() {
                    $("#toggle-search_advanced").click(function () {
                        $("#search_advanced").toggle("fast");
                    });
                });
                ';
        }
    }

/**
 * Block
 *
 */
    public function theme_default_slider($block) {
        return array(
            'body' => $this->_View->element('ThemeDefault.theme_default_slider', array('block' => $block))
        );
    }

/**
 * Block Settings
 *
 */
    public function theme_default_slider_settings($data) {
        return $this->_View->element('ThemeDefault.theme_default_slider_settings', array('block' => $data));
    }
}