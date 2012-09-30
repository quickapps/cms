<?php
/**
 * Theme Customizer class.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeCustomizerHelper extends AppHelper {
	public function beforeRender($viewFile) {
		$this->_View->viewVars['Layout']['stylesheets']['all'][] = '/system/js/colorpicker/colorpicker.css';
		$this->_View->viewVars['Layout']['javascripts']['file'][] = '/system/js/colorpicker/colorpicker.js';
		$this->_View->viewVars['Layout']['javascripts']['file'][] = '/system/js/fontpanel/fontpanel.js';
		$this->_View->viewVars['Layout']['stylesheets']['all'][] = '/system/js/fontpanel/fontpanel.css';
	}

/**
 * Generates the "configurable styles" interface for the given theme.
 *
 * @param string $theme_name Name of the theme to handle
 * @return string HTML
 */
	public function generate($theme_name) {
		$out = '';
		$theme_name = Inflector::camelize($theme_name);
		$themePath = App::themePath($theme_name);
		$cssPath = $themePath . 'webroot' . DS . 'css' . DS;
		$tags = 'color|size|font|miscellaneous';

		if (file_exists($cssPath)) {
			$Folder = new Folder($cssPath);
			$cssFiles = $Folder->find('(.*)\.css');

			if ($cssFiles) {
				$color = $font = 0;

				foreach ($cssFiles as $css) {
					$__css = '';
					$__groups = array();
					$__noTitleCounters = array(
						'color' => 0,
						'font' => 0,
						'size' => 0,
						'Unknow' => 0
					);

					$cssContent = file_get_contents($cssPath . $css);

					if (preg_match_all('/\/\*([\s\t\n\r]*?)\[(' . $tags . ')\b(.*?)(?:(\/))?\]([\s\t\n\r]*?)\*\/(?:(.+?)\/\*([\s\t\n\r]*?)\[\/\2\]([\s\t\n\r]*?)\*\/)?/s', $cssContent, $matches)) {
						foreach ($matches[0] as $i => $match) {
							$__style = '';
							$field = base64_encode($css) . ".{$i}";
							$attrs = QuickApps::parseHooktagAttributes($matches[3][$i]);
							$value = '';

							if (!isset($attrs['title'])) {
								if (isset($__noTitleCounters[$matches[2][$i]])){
									$__noTitleCounters[$matches[2][$i]]++;
								}

								switch ($matches[2][$i]) {
									case 'color':
										$attrs['title'] = __t('Color %d', $__noTitleCounters['color']);
									break;

									case 'font':
										$attrs['title'] = __t('Font %d', $__noTitleCounters['font']);
									break;

									case 'size':
										$attrs['title'] = __t('Size %d', $__noTitleCounters['size']);
									break;

									case 'miscellaneous':
										$attrs['title'] = __t('Add Your CSS');
									break;

									default:
										$__noTitleCounters['undefined']++;
										$attrs['title'] = __t('Unknown property %d', $__noTitleCounters['undefined']);
									break;
								}
							} else {
								$attrs['title'] = QuickApps::is('theme.core', $theme_name) ? __t($attrs['title']) : __d("Theme{$theme_name}", $attrs['title']);
							}

							if ($cache = Cache::read("theme_{$theme_name}_{$css}", '__theme_css__')) {
								if (isset($cache['map'][$match])) {
									$value = $cache['map'][$match];
								}
							} elseif (isset($matches[6][$i]) && !empty($matches[6][$i])) {
								$value = $matches[6][$i];
							}

							switch ($matches[2][$i]) {
								case 'color':
									$color++;
									$id = md5("ThemeCustomizer.{$theme_name}.{$field}");
									$__style .=
										$this->_View->Form->label("ThemeCustomizer.{$theme_name}.{$field}", $attrs['title'])
										. '<div class="colorSelector">'
										. $this->_View->Form->input(
											"ThemeCustomizer.{$theme_name}.{$field}", 
											array(
												'value' => $value,
												'class' => $id,
												'style' => 'width:50px;',
												'type' => 'text',
												'label' => $attrs['title']
											)
										)
										. '<div class="preview" id="' . $id . '"></div>'
										. '</div>';
								break;

								case 'font':
									$font++;
									$id = md5("ThemeCustomizer.{$theme_name}.{$field}");
									$__style .= $this->_View->Form->input(
										"ThemeCustomizer.{$theme_name}.{$field}", 
										array(
											'id' => $id,
											'value' => $value,
											'class' => 'fontselector',
											'style' => 'width:200px;',
											'type' => 'text',
											'label' => $attrs['title']
										)
									);
								break;

								case 'miscellaneous':
									$__style .= $this->_View->Form->input(
										"ThemeCustomizer.{$theme_name}.{$field}", 
										array(
											'value' => $value,
											'style' => 'width:100%;',
											'type' => 'textarea',
											'label' => $attrs['title']
										)
									);
								break;

								case 'size':
									case 'default':
										$h = false;

										if ($matches[2][$i] != 'size') {
											$h = $this->hook('customize_' . $matches[2][$i], 
												$__data = array(
													'theme_name' => $theme_name,
													'css' => $css,
													'tag' => $matches[2][$i],
													'value' => $value,
													'attrs' => $attrs
												), array('collectReturn' => false));
										}

										if (!$h) {
											$__style .= $this->_View->Form->input(
												"ThemeCustomizer.{$theme_name}.{$field}", 
												array(
													'value' => $value,
													'style' => 'width:50px;',
													'type' => 'text',
													'label' => $attrs['title']
												)
											);
										} else {
											$__style .= $h;
										}
								break;
							}
							
							if (!empty($__style)) {
								if (isset($attrs['group'])) {
									$__groups[$attrs['group']][] = $__style;
								} else {
									$__css .= $__style;
								}
							}
						}
					}

					$__g = '';

					foreach ($__groups as $title => $content) {
						$title = QuickApps::is('theme.core', $theme_name) ? __t($title) : __d("Theme{$theme_name}", $title);
						$__g .= $this->_View->Html->useTag('fieldsetstart', $title);
						$__g .= implode(' ', $content);
						$__g .= $this->_View->Html->useTag('fieldsetend');
					}

					$__css = $__g . $__css;

					if (!empty($__css)) {
						$__css .= $this->_View->Form->hidden("ThemeCustomizer.{$theme_name}.__reset", array('value' => 0));

						$out .=
							$this->_View->Html->useTag('fieldsetstart', '<span class="fieldset-toggle"><em>' . $css . '</em></span>')
							. "<div class=\"fieldset-toggle-container\" style=\"display:none;\" id=\"{$css}\">"
								. '<div class="form-controls">'
									. $this->_View->Form->submit(__t('Save'), array('style' => 'float:right; display:block; margin-left:10px;', 'onclick' => '$("#ThemeCustomizer' . $theme_name . 'SaveCss").val("' . $css . '");'))
									. $this->_View->Form->submit(__t('Reset'), array('style' => 'float:right; display:block; margin-left:10px;', 'onclick' => 'return reset_styles("' . $theme_name . '", "' . $css . '");'))
								. '</div>'
								. $__css
							. "</div>"
							. $this->_View->Html->useTag('fieldsetend');
					}
				}

				if (empty($out)) {
					return false;
				}
			} else {
				return false;
			}

			$scripts = '
				<script>
					function reset_styles(theme_name, css) {
						var c = confirm("' . __t('Reset selected style sheet ?') . '");

						if (c) {
							$("#ThemeCustomizer" + theme_name + "Reset").val(css);

							return true;
						}

						return false;
					}
				</script>';

			if ($color) {
				$scripts .= "
					<script>
						$(document).ready(function() {
							$('.colorSelector .preview').each(function () {
								var id = $(this).attr('id');
								var color = $('input.' + id).val();

								$(this).css('backgroundColor', color);
								$(this).ColorPicker({
									color: color,
									onChange: function (hsb, hex, rgb) {
										$('.' + id).val('#' + hex);
										$('#' + id).css('backgroundColor', '#' + hex);
									}
							   });
							});
						});
					</script>"; 
			}

			if ($font) {
				$scripts .= '
					<script>
						$(document).ready(function() {
							$(function() {
								$("input.fontselector").FontPanel();
							});
						});
					</script>'; 
			}
		}

		$out = "<div style=\"width:48%; float:left; margin-right:15px;\">{$out}</div>{$scripts}";
		$out .= $this->_View->Form->hidden("ThemeCustomizer.{$theme_name}.__save_css", array('value' => ''));

		return $out;
	}
}