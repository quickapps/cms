<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
use Cake\Cache\Cache;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\Utility\Folder;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

/**
 * Stores some bootstrap-handy information
 * into a persistent file `SITE/tmp/snapshot.php`.
 *
 * @return void
 */
function snapshot() {
	$snapshot = [
		'active_languages' => ['en'],
		'node_types' => [],
		'plugins' => [
			'enabled' => [],
			'disabled' => [],
			'core' => []
		],
		'themes' => [],
		'variables' => [
			'url_locale_prefix' => 0,
			'site_theme' => null,
			'admin_theme' => null,
			'default_language' => 'eng'
		],
		'fields' => [],
		'hooks' => [],
		'languages' => [
			'eng' => [
				'name' => 'English',
				'native' => 'English',
				'direction' => 'ltr',
			]
		]
	];

	$Plugins = TableRegistry::get('Plugins')->find()
		->select(['name', 'status'])
		->all();
	$NodeTypes = TableRegistry::get('NodeTypes')->find()
		->select(['slug'])
		->all();
	$Variables = TableRegistry::get('Variables')->find()
		->where([
			'name IN' => [
				'site_theme',
				'admin_theme',
				'url_locale_prefix',
				'default_language',
			]
		])->all();

	foreach ($Plugins as $plugin) {
		if ($plugin->status) {
			$snapshot['plugins']['enabled'][] = $plugin->name;
		} else {
			$snapshot['plugins']['disabled'][] = $plugin->name;
		}
	}

	foreach ($NodeTypes as $nodeType) {
		$snapshot['node_types'][] = $nodeType->slug;
	}

	foreach ($Variables as $variable) {
		$snapshot['variables'][$variable->name] = $variable->value;
	}

	$Folder = new Folder(APP . 'Plugin');

	foreach ($Folder->read(false, false, false)[0] as $plugin) {
		$snapshot['plugins']['core'][] = $plugin;
	}

	$Folder = new Folder(APP . 'Template' . DS . 'Themed');

	foreach ($Folder->read(false, false, false)[0] as $theme) {
		$snapshot['themes']['core'][] = $theme;
	}

	foreach (App::path('Plugin') as $path) {
		$Folder = new Folder($path);

		foreach($Folder->read(false, false, true)[0] as $pluginPath) {
			$pluginName = basename($pluginPath);
			$fieldPath = $pluginPath . DS . 'Hook' . DS . 'Field' . DS;

			if (is_dir($fieldPath)) {
				$Folder = new Folder($fieldPath);

				foreach ($Folder->read(false, false, true)[1] as $fieldHandler) {
					$snapshot['fields'][$pluginName][] = [
						'namespace' => 'Field\\',
						'path' => dirname($fieldHandler),
						'className' => 'Field\\' . basename(preg_replace('/\.php$/', '', $fieldHandler))
					];
				}
			}

			$hookPath = $pluginPath . DS . 'Hook' . DS;
			if (is_dir($hookPath)) {
				$Folder = new Folder($hookPath);

				foreach ($Folder->read(false, false, true)[1] as $hookListener) {
					$snapshot['hooks'][$pluginName][] = [
						'namespace' => 'Hook\\',
						'path' => dirname($hookListener),
						'className' => 'Hook\\' . basename(preg_replace('/\.php$/', '', $hookListener))
					];
				}
			}
		}
	}

	Configure::write('QuickApps', $snapshot);
	Configure::dump('snapshot.php', 'QuickApps', ['QuickApps']);
}

/**
 * Translation function, domain search order:
 *
 * 1.  In use plugin.
 * 2.  Default.
 *
 * @param string $singular String to translate
 * @param mixed $args Array with arguments or multiple arguments in function
 * @return string The translated string
 */
function __($singular, $args = null) {
	$plugin = false;
	$translated = $singular;

	if (!empty(Router::getRequest()->params['plugin'])) {
		$plugin = Inflector::underscore(Router::getRequest()->params['plugin']);
	}

	if ($plugin) {
		$translated = __d($plugin, $singular);
	}

	if ($translated === $singular) {
		$translated = I18n::translate($singular);
	}

	if ($args === null) {
		return $translated;
	} elseif (!is_array($args)) {
		$args = array_slice(func_get_args(), 1);
	}

	return vsprintf($translated, $args);
}

/**
 * Check if $haystack string starts with $needle string.
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function str_starts_with($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

/**
 * Check if $haystack string ends with $needle string.
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function str_ends_with($haystack, $needle) {
	return
		strpos($haystack, $needle) +
		strlen($needle) === strlen($haystack);
}
