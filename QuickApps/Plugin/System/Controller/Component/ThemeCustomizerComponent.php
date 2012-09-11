<?php
/**
 * Theme Customizer class.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeCustomizerComponent extends Component {
	public $Controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;
	}

/**
 * Generates a customized version of CSS file based on user choices submitted via POST.
 *
 * @return boolean TRUE on success, FALSE otherwise
 */
	public function savePost() {
		if (!isset($this->Controller->data['ThemeCustomizer'])) {
			return false;
		}

		foreach ($this->Controller->data['ThemeCustomizer'] as $theme_name => $files) {
			$theme_name = Inflector::camelize($theme_name);
			$themePath = App::themePath($theme_name);
			$cssPath = $themePath . 'webroot' . DS . 'css' . DS;
			$tags = 'color|size|font|miscellaneous';
			$map = array();

			if ($css = $this->Controller->data['ThemeCustomizer'][$theme_name]['__reset']) {
				Cache::delete("theme_{$theme_name}_{$css}", '__theme_css__');

				return true;
			}

			if (file_exists($cssPath)) {
				foreach ($files as $css => $values) {
					$css = base64_decode($css);

					if ($css != $this->Controller->data['ThemeCustomizer'][$theme_name]['__save_css']) {
						continue;
					}

					if (file_exists($cssPath . $css)) {
						$cssContent = file_get_contents($cssPath . $css);

						if (preg_match_all('/\/\*([\s\t\n\r]*?)\[(' . $tags . ')\b(.*?)(?:(\/))?\]([\s\t\n\r]*?)\*\/(?:(.+?)\/\*([\s\t\n\r]*?)\[\/\2\]([\s\t\n\r]*?)\*\/)?/s', $cssContent, $matches)) {
							foreach ($matches[0] as $i => $match) {
								if (isset($values[$i]) && isset($matches[6][$i])) {
									$map[$match] = $values[$i];
									$attrs = QuickApps::parseHooktagAttributes($matches[3][$i]);
									$new = str_replace($matches[6][$i], $values[$i], $match);
									$cssContent = str_replace($match, $new, $cssContent);

									if (isset($attrs['id'])) {
										$cssContent = preg_replace('/\/\*([\s\t\n\r]*?)\[(' . $attrs['id'] . ')\]([\s\t\n\r]*?)\*\/(?:(.+?)\/\*([\s\t\n\r]*?)\[\/\2\]([\s\t\n\r]*?)\*\/)?/s', $values[$i], $cssContent);
									}
								}
							}
						}

						$cssCache = array(
							'content' => $cssContent,
							'map' => $map
						);

						Cache::write("theme_{$theme_name}_{$css}", $cssCache, '__theme_css__');
					}
				}
			}
		}
	}
}