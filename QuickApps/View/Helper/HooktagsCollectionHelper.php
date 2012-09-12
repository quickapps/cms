<?php
/**
 * Hooktagss collection is used as a registry for loaded hooktags helpers
 * and handles dispatching and loading hooktags methods.
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class HooktagsCollectionHelper extends AppHelper {
/**
 * Temporaly information used by some methods.
 *
 * @var array
 */
	private $__tmp;

/**
 * Instance of View class.
 *
 * @var View
 */
	private $__view;

/**
 * Associtive array of methods and Hooktag classes.
 *
 * @var array
 */
	private $__map = array();

/**
 * List of all available hooktag methods.
 *
 * @var array
 */
	protected $_methods = array();

/**
 * List of all available hooktags objects.
 *
 * @var array
 */
	protected $_hookObjects = array();

	public function beforeRender($viewFile) {
		$this->__view = $this->_View;
		$this->__loadHooktags();

		return true;
	}

/**
 * Parse string for special hooktags placeholders and replace
 * them with the corresponding hooktag method return.
 *
 * ### Hooktag example
 *
 *     [self_closing_hooktag param1=text param=2 param3=0 /]
 *     [other_hook_hooktag]only content & no params[/other_hook_hooktag]
 *
 * @param string $text Text to replace.
 * @return string HTML with all hooktags replaced.
 */
	public function hooktags($text) {
		$text = $this->specialTags($text);

		if (!empty($this->__tmp['__hooktags_reg'])) {
			$tags = $this->__tmp['__hooktags_reg'];
		} else {
			$tags = $this->__tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());
		}

		return preg_replace_callback('/(.?)\[(' . $tags . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', array($this, '__doHooktag'), $text);
	}

/**
 * Load all hooks (and optionally hooktags) of specified Module.
 *
 * @param string $module Name of the module.
 * @return boolean TRUE on success. FALSE otherwise.
 */
	public function attachModuleHooktags($module) {
		$Plugin = Inflector::camelize($module);

		if (!CakePlugin::loaded($Plugin) || isset($this->_hookObjects[$Plugin . 'Hook'])) {
			return false;
		}

		$folder = new Folder;
		$folder->path = CakePlugin::path($Plugin) . 'View' . DS . 'Helper' . DS;
		$file_pattern = '(.*)HooktagsHelper\.php';
		$files = $folder->find($file_pattern);

		foreach ($files as $object) {
			$object = str_replace('Helper.php', '', $object);
			$this->{$object} = $this->_View->loadHelper("{$Plugin}.{$object}");

			if (!is_object($this->{$object})) {
				continue;
			}

			$methods = array();
			$_methods = QuickApps::get_this_class_methods($this->{$object});

			foreach ($_methods as $method) {
				$methods[] = $method;
				$this->__map[$method][] = (string)$object;
			}

			$this->_hookObjects["{$Plugin}.{$object}"] = $methods;
		}

		$this->_methods = array_keys($this->__map);
		$this->__tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());

		return true;
	}

/**
 * Unload all hooktags of specified Module.
 *
 * @param string $module Name of the module
 * @return boolean TRUE on success. FALSE otherwise.
 */
	public function detachModuleHooktags($module) {
		$Plugin = Inflector::camelize($module);
		$found = 0;

		foreach ($this->_hookObjects as $object => $hooks) {
			if (strpos($object, "{$Plugin}.") === 0) {
				foreach ($hooks as $hook) {
					unset($this->__map[$hook]);
				}

				$className = str_replace("{$Plugin}.", '', $object);

				unset($this->_hookObjects[$object]);
				unset($this->__view->{$className});

				$found++;
			}
		}

		$this->_methods = array_keys($this->__map);
		$this->__tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());

		return $found > 0;
	}

/**
 * Chech if hooktag method exists.
 *
 * @param string $hooktag Name of the hooktag method to check
 * @return boolean
 */
	public function hooktagDefined($hooktag) {
		return isset($this->__map[$hooktag]);
	}

/**
 * Turn on hooktag method if is turned off.
 *
 * @param string $hooktag Hooktag name to turn on.
 * @return boolean TRUE on success. FALSE hooktag does not exists or is already on.
 */
	public function hooktagEnable($hooktag) {
		$hooktag = Inflector::underscore($hooktag);

		if (isset($this->__map["{$hooktag}::Disabled"])) {
			$this->__map[$hooktag] = $this->__map["{$hooktag}::Disabled"];

			unset($this->__map["{$hooktag}::Disabled"]);

			if (in_array("{$hooktag}::Disabled", $this->_methods)) {
				$this->_methods[] = $hooktag;

				unset($this->_methods[array_search("{$hooktag}::Disabled", $this->_methods)]);
			}

			return true;
		}

		return false;
	}

/**
 * Turns off hooktag method.
 *
 * @param string $hooktag Hooktag name to turn off.
 * @return boolean TRUE on success. FALSE hooktag does not exists.
 */
	public function hooktagDisable($hooktag) {
		$hooktag = Inflector::underscore($hooktag);

		if (isset($this->__map[$hooktag])) {
			$this->__map["{$hooktag}::Disabled"] = $this->__map[$hooktag];

			unset($this->__map[$hooktag]);

			if (in_array($hooktag, $this->_methods)) {
				$this->_methods[] = "{$hooktag}::Disabled";

				unset($this->_methods[array_search("{$hooktag}", $this->_methods)]);
			}

			return true;
		}

		return false;
	}

/**
 * Return an array list of all registered hooktag methods.
 *
 * @return array Array list of all available hooktag methods.
 */
	public function hooktagsList() {
		return $this->_methods;
	}

/**
 * Removes all hooktags from the given content (except special tags).
 * Useful for plain text converting.
 *
 * @param string $text Text to remove hooktags.
 * @return string Content without hooktags.
 */
	public function stripHooktags($text) {
		$text = $this->specialTags($text);

		if (!empty($this->__tmp['__hooktags_reg'])) {
			$tags = $this->__tmp['__hooktags_reg'];
		} else {
			$tags = $this->__tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());
		}

		return preg_replace('/(.?)\[(' . $tags . ')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', '$1$6', $text);
	}

/**
 * Special hooktags that are not managed by any modules.
 *
 * -	[date=FORMAT]: Return current date(FORMAT).
 * -	[rand={values,by,comma}]:
 *			Returns a radom value from the specified group.
 *			If only two numeric values are given as group, then rand(num1, num2) is returned.
 * -	[language.OPTION]: Current language option (code, name, native, direction).
 * -	[language]: Shortcut to [language.code] which return current language code.
 * -	[url]YourURL[/url] or [url=YourURL]: Formatted url.
 * -	[url=LINK]LABEL[/url]: Returns link tag <href="LINK">LABEL</a>
 * -	[t=stringToTranslate] or [t]stringToTranslate[/t]: text translation: __t(stringToTranslate)
 * -	[t=domain@@stringToTranslate]: Translation by domain __d(domain, stringToTranslate)
 * -	[Layout.PATH]:
 *			Get any value from `Layout` variable. i.e.: [Layout.display] gets current display mode
 *			if path does not exists then '' (empty) is rendered instead the hooktag code.
 *
 * @param string $text Original text where to replace tags.
 * @return string.
 */
	public function specialTags($text) {
		//[locale]
		$text = str_replace('[language]', Configure::read('Variable.language.code'), $text);

		//[locale.OPTION]
		preg_match_all('/\[language.(.+)\]/iUs', $text, $localeMatches);
		foreach ($localeMatches[1] as $attr) {
			$text = str_replace("[language.{$attr}]", Configure::read('Variable.language.' .$attr), $text);
		}

		//[url]URL[/url]
		preg_match_all('/\[url\](.+)\[\/url\]/iUs', $text, $urlMatches);
		foreach ($urlMatches[1] as $url) {
			$text = str_replace("[url]{$url}[/url]", Router::url($url, true), $text);
		}

		//[url=URL]
		preg_match_all('/\[url\=(.+)\]/iUs', $text, $urlMatches);
		foreach ($urlMatches[1] as $url) {
			$text = str_replace("[url={$url}]", Router::url($url, true), $text);
		}

		//[t=text to translate]
		preg_match_all('/\[t\=(.+)\]/iUs', $text, $tMatches);
		foreach ($tMatches[1] as $string) {
			$text = str_replace("[t={$string}]", __t($string), $text);
		}

		//[t]text to translate[/t]
		preg_match_all('/\[t\](.+)\[\/t\]/iUs', $text, $tMatches);
		foreach ($tMatches[1] as $string) {
			$text = str_replace("[t]{$string}[/t]", __t($string), $text);
		}

		//[t=domain@@text to translate]
		preg_match_all('/\[t\=(.+)\@\@(.+)\]/iUs', $text, $dMatches);
		foreach ($dMatches[1] as $key => $domain) {
			$text = str_replace("[d={$domain}@@{$dMatches[2][$key]}]", __d($domain, $dMatches[2][$key]), $text);
		}

		//[date=FORMAT@@TIME_STAMP]
		preg_match_all('/\[date\=(.+)\@\@(.+)\]/iUs', $text, $dateMatches);
		foreach ($dateMatches[1] as $key => $format) {
			$stamp = $dateMatches[2][$key];
			$replace = is_numeric($stamp) ? date($format, $stamp) : date($format, strtotime($stamp));
			$text = str_replace("[date={$format}@@{$stamp}]", $replace, $text);
		}

		//[date=FORMAT]
		preg_match_all('/\[date\=(.+)\]/iUs', $text, $dateMatches);
		foreach ($dateMatches[1] as $format) {
			$text = str_replace("[date={$format}]", date($format), $text);
		}

		//[rand=a,b,c]
		preg_match_all('/\[rand\=(.+)\]/iUs', $text, $randomMatches);
		foreach ($randomMatches[1] as $_values) {
			$values = explode(',', $_values);
			$values = array_map('trim', $values);
			$c = count($values);

			if ($c == 2 && is_numeric($values[0]) && is_numeric($values[1])) {
				$replace = rand($values[0], $values[1]);
			} else {
				$replace = $values[rand(0, $c-1)];
			}

			$text = str_replace("[rand={$_values}]", $replace, $text);
		}

		//[Layout.PATH]
		preg_match_all('/\[Layout.(.+)\]/iUs', $text, $layoutPaths);
		foreach ($layoutPaths[1] as $path) {
			$extract = Hash::extract($this->_View->viewVars['Layout'], $path);
			$text = str_replace("[Layout.{$path}]", $extract, $text);
		}

		// pass text to modules so they can apply their own special tags
		$this->hook('special_tags_alter', $text);

		return $text;
	}

/**
 * Callback function.
 *
 * @return mixed Hook response or false in case of no response.
 */
	private function __doHooktag($m) {
		// allow [[foo]] syntax for escaping a tag
		if ($m[1] == '[' && $m[6] == ']') {
			return substr($m[0], 1, -1);
		}

		$tag = $m[2];

		if (isset($this->__map["{$tag}::Disabled"])) {
			return false;
		}

		$attr = QuickApps::parseHooktagAttributes($m[3]);
		$hook = isset($this->__map[$tag]) ? $this->__map[$tag] : false;

		if ($hook) {
			foreach ($this->__map[$tag] as $object) {
				$hook =& $this->__view->{$object};

				if (isset($m[5])) {
					// enclosing tag - extra parameter
					return $m[1] . call_user_func(array($hook, $tag), $attr, $m[5], $tag) . $m[6];
				} else {
					// self-closing tag
					return $m[1] . call_user_func(array($hook, $tag), $attr, null, $tag) . $m[6];
				}
			}
		}

		return false;
	}

/**
 * Loads Hooktag classes.
 *
 * @return void
 */
	private function __loadHooktags() {
		foreach ((array)Configure::read('Hook.hooktags') as $helper) {
			list($plugin, $helper) = pluginSplit($helper);

			$this->__view->Helpers->load("{$plugin}.{$helper}");

			if ($helper == 'HooktagsCollection' || !is_object($this->__view->{$helper})) {
				continue;
			}

			if (strpos($helper, 'Hook') !== false) {
				$methods = array();
				$_methods = QuickApps::get_this_class_methods($this->__view->{$helper});

				foreach ($_methods as $method) {
					// ignore private and protected methods
					if (strpos($method, '__') === 0 || strpos($method, '_') === 0) {
						continue;
					}

					$methods[] = $method;

					if (isset($this->__map[$method])) {
						$this->__map[$method][] = (string)$helper;
					} else {
						$this->__map[$method] = array((string)$helper);
					}
				}

				if ($plugin) {
					$this->_hookObjects["{$plugin}.{$helper}"] = $methods;
				} else {
					$this->_hookObjects[$helper] = $methods;
				}
			}
		}

		$this->_methods = array_keys($this->__map);
	}
}