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
    function stylesheets_alter(&$css) {
        $s = Configure::read('Modules.ThemeDefault');
        $ht = @$s['settings']['color_header_top'];
        $hb = @$s['settings']['color_header_bottom'];
        $ht = !$ht ? '#282727': $ht;
        $hb = !$hb ? '#282727': $hb;
        $css['embed'][] = "
        div#header-top {
            background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from({$ht}), to({$hb}));
            background-image: -moz-linear-gradient(-90deg, {$ht}, {$hb});
        }";
    }

/* Adding toggle effect to advanced search form */
    function javascripts_alter(&$js) {
        if (
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'node' &&
            $this->request->params['action'] == 'search'
        )
            $js['embed'][] = '
$(document).ready(function() {
    $("#toggle-search_advanced").click(function () {
        $("#search_advanced").toggle("fast");
    });
});
';
    }

/**
 * Block
 *
 */
    function theme_default_slider($block) {
        return array(
            'body' => $this->_View->element('theme_default_slider', array('block' => $block), array('plugin' => 'ThemeDefault'))
        );
    }

/**
 * Block Settings
 *
 */
    function theme_default_slider_settings($data) {
        return $this->_View->element('theme_default_slider_settings', array('block' => $data), array('plugin' => 'ThemeDefault'));
    }

/**
 * Returns formated menu
 *
 * @return string HTML
 */
    function theme_menu($menu) {
        $output = '';

        switch ($menu['region']) {
            case 'main-menu':
                $settings = array('id' => 'top-menu');
            break;

            default:
                $settings = array();
            break;
        }

        return $this->Menu->generate($menu, $settings);
    }

    function theme_breadcrumb($b) {
        $out = array();

        foreach ($b as $node) {
            $selected = $node['MenuLink']['router_path'] == str_replace($this->_View->base, '', $this->_View->here) ? 'text-decoration:underline;' : '';
            $out[] = $this->_View->Html->link($node['MenuLink']['link_title'], $node['MenuLink']['router_path'], array('style' => $selected));
        }

        if (empty($out)) {
            return '';
        }

        return implode(' » ', $out) . ' » ';
    }

    # hookTag
    function content_box($atts, $content=null, $code="") {
        $type = isset($atts['type']) ? $atts['type'] : 'success';
        $return = "<div class=\"td-box dialog-{$type}\">";
        $return .= $content;
        $return .= '</div>';

        return $return;
    }

    # hookTag
    function button($atts, $content = null, $code="") {
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

    function theme_block($block) {
        $output = '';

        switch ( $block['region']) {
            case 'main-menu':
                case 'footer':
                    case 'slider':
                        case 'language-switcher':
                            case 'search':
                                case 'user-menu':
                $output .= "{$block['body']}";
            break;

            default:
                $output = $this->_View->element('default_theme_block', array('block' => $block));
            break;
        }

        return $output;
    }
}