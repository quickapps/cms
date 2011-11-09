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

/* Header gradients */
    public function stylesheets_alter(&$css) {
        $s = Configure::read('Modules.ThemeDefault');
        $ht = @$s['settings']['color_header_top'];
        $hb = @$s['settings']['color_header_bottom'];
        $links = @$s['settings']['color_links'];
        $text = @$s['settings']['color_text'];
        $main_bg = @$s['settings']['color_main_bg'];        
        $footer = @$s['settings']['color_footer'];        

        $ht = !$ht ? '#282727': $ht;
        $links = !$links ? '#00b7f3': $links;
        $text = !$text ? '#555555': $text;
        $main_bg = !$main_bg ? '#ededec': $main_bg;
        $footer = !$footer ? '#282727': $footer;

        $css['embed'][] = "
        div#header-top {
            background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from({$ht}), to({$hb})) !important;
            background-image: -moz-linear-gradient(-90deg, {$ht}, {$hb}) !important;
        }\n";

        $css['embed'][] = "a { color:{$links} !important; }\n";
        $css['embed'][] = "body { color:{$text} !important; background:{$footer}; }\n";
        $css['embed'][] = "#page { background:{$main_bg} !important; }\n";

        if (count($this->_View->viewVars['Layout']['node'])) {
            $css['embed'][] = "#search-advanced { display:none; }";
        }
    }

/* Adding toggle effect to advanced search form */
    public function javascripts_alter(&$js) {
        if (
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'node' &&
            $this->request->params['action'] == 'search' &&
            !count($this->_View->viewVars['Layout']['node'])
        ) {
            $js['embed'][] = '
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
            'body' => $this->_View->element('theme_default_slider', array('block' => $block), array('plugin' => 'ThemeDefault'))
        );
    }

/**
 * Block Settings
 *
 */
    public function theme_default_slider_settings($data) {
        return $this->_View->element('theme_default_slider_settings', array('block' => $data), array('plugin' => 'ThemeDefault'));
    }

    # hookTag
    public function content_box($atts, $content=null, $code="") {
        $type = isset($atts['type']) ? $atts['type'] : 'success';
        $return = "<div class=\"td-box dialog-{$type}\">";
        $return .= $content;
        $return .= '</div>';

        return $return;
    }

    # hookTag
    public function button($atts, $content = null, $code="") {
        $atts = Set::merge(
            array(
            'link'    => '#',
            'target'    => '',
            'color'    => '',
            'size'    => '', #big/small
            ), $atts
        );

        extract($atts);

        $size = strtolower($size) != 'big' ? ' small' : 'big';
        $target = !empty($target) ? "target=\"{$target}\" " : "";
        $out = "<a href=\"{$link}\" class=\"{$size}-button {$size}{$color}\" {$target}><span>{$content}</span></a>";

        return $out;
    }
}