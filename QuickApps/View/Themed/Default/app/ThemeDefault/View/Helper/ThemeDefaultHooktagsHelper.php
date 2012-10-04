<?php
/**
 * Theme Hooktags Helper
 * Theme: Default
 *
 * PHP version 5
 *
 * @package  QuickApps.Themes.Default.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeDefaultHooktagsHelper extends AppHelper {

	public function content_box($atts, $content = null, $code = "") {
		$type = isset($atts['type']) ? $atts['type'] : 'success';
		$return = "<div class=\"td-box dialog-{$type}\">";
		$return .= $content;
		$return .= '</div>';

		return $return;
	}

	public function button($atts, $content = null, $code = "") {
		$atts = Hash::merge(
			array(
			'link' => '#',
			'target' => '',
			'color' => '',
			'size' => '', // big|small
			), $atts
		);

		extract($atts);

		$size = strtolower($size) != 'big' ? 'small' : 'big';
		$target = !empty($target) ? "target=\"{$target}\" " : "";
		$out = "<a href=\"{$link}\" class=\"btn btn-{$size} btn-{$color}\" {$target}><span>{$content}</span></a>";

		return $out;
	}
}