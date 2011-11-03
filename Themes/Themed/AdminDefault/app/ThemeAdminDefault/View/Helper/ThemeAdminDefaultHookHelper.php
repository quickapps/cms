<?php
/**
 * Theme Helper
 * Theme: AdminDefault
 *
 * PHP version 5
 *
 * @package  Quickapps.Theme.AdminDefault.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class ThemeAdminDefaultHookHelper extends AppHelper {
/**
* Returns formated menu
*
* @return string HTML
*/
    function theme_menu($menu) {
        $output = '';

        switch ($menu['region']) {
            case 'management-menu':
                $settings = array('id' => 'top-menu');
            break;

            case 'content':
                return $this->_View->element('content-menu', array('menu' => $menu), array('plugin' => 'ThemeAdminDefault'));
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
            $out[] = $this->_View->Html->link($node['MenuLink']['link_title'], $node['MenuLink']['router_path'], array('title' => $node['MenuLink']['description'], 'style' => $selected));
        }

        if (empty($out)) {
            return '';
        }

        return implode(' » ', $out) . ' » ';
    }

    function stylesheets_alter(&$css) {
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            foreach ($css['all'] as $k => $file) {
                if ($file == 'default.frontend.css') {
                    unset($css['all'][$k]);
                }
            }
        } else {
            foreach ($css['all'] as $k => $file) {
                if ($file == 'default.backend.css') {
                    unset($css['all'][$k]);
                }
            }
        }
    }

    function theme_block($block) {
        $output = '';

        switch ( $block['region']) {
            case 'management-menu':
                    $output .= "<div id=\"{$block['region']}\" class=\"item-list\">{$block['body']}</div>";
            break;

            case 'toolbar':
                $output =  $block['body'];
            break;

            case 'footer':
                $output =  $block['body'];
            break;

            default:
                $output = $this->_View->element('default_theme_block', array('block' => $block));
            break;
        }

        return $output;
    }
}