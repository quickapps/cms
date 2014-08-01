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
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

/**
 * Stores some bootstrap-handy information
 * into a persistent file `SITE/tmp/snapshot.php`.
 *
 * @param array $mergeWith Array to merge with snapshot array
 * @return void
 */
function snapshot($mergeWith = []) {
	$snapshot = [
		'node_types' => [],
		'plugins' => [],
		'variables' => [
			'url_locale_prefix' => 0,
			'site_theme' => null,
			'admin_theme' => null,
			'default_language' => 'en-us'
		],
		'languages' => [
			'en-us' => [
				'status' => 1,
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

	$enabledPlugins = [];
	$disabledPlugins = [];
	foreach ($Plugins as $plugin) {
		if ($plugin->status) {
			$enabledPlugins[] = $plugin->name;
		} else {
			$disabledPlugins[] = $plugin->name;
		}
	}

	foreach ($NodeTypes as $nodeType) {
		$snapshot['node_types'][] = $nodeType->slug;
	}

	foreach ($Variables as $variable) {
		$snapshot['variables'][$variable->name] = $variable->value;
	}

	foreach (App::path('Plugin') as $path) {
		$Folder = new Folder($path);

		foreach($Folder->read(false, true, true)[0] as $pluginPath) {
			$pluginName = basename($pluginPath);
			$basePath = $pluginPath . DS . 'src' . DS;
			$eventsPath =  $basePath . 'Event' . DS;
			$isCore = (strpos(str_replace(['/', DS], '/', $pluginPath), str_replace(['/', DS], '/', APP)) !== false);

			// core plugins are always enabled
			if ($isCore) {
				$status = 1;
			} else {
				$status = -1;
				$status = in_array($pluginName, $enabledPlugins) ? 1 : $status;
				$status = in_array($pluginName, $disabledPlugins) ? 0 : $status;
			}

			$events = [
				'hooks' => [],
				'hooktags' => [],
				'fields' => [],
			];

			if (is_dir($eventsPath)) {
				$Folder = new Folder($eventsPath);

				foreach ($Folder->read(false, false, true)[1] as $classFile) {
					$className = basename(preg_replace('/\.php$/', '', $classFile));

					if (str_ends_with($className, 'Field')) {
						$events['fields']['Field\\' . $className] = [
							'namespace' => 'Field\\',
							'path' => dirname($classFile),
						];
					} elseif (str_ends_with($className, 'Hook')) {
						$events['hooks']['Hook\\' . $className] = [
							'namespace' => 'Hook\\',
							'path' => dirname($classFile),
						];
					} elseif (str_ends_with($className, 'Hooktag')) {
						$events['hooktags']['Hooktag\\' . $className] = [
							'namespace' => 'Hooktag\\',
							'path' => dirname($classFile),
						];
					}
				}
			}

			$snapshot['plugins'][$pluginName] = [
				'name' => $pluginName,
				'isTheme' => str_ends_with($pluginName, 'Theme'),
				'isCore' => $isCore,
				'hasHelp' => file_exists($pluginPath . '/src/Template/Element/help.ctp'),
				'hasSettings' => file_exists($pluginPath . '/src/Template/Element/settings.ctp'),
				'events' => $events,
				'status' => $status,
				'path' => $pluginPath,
			];
		}
	}

	if (!empty($mergeWith)) {
		$snapshot = Hash::merge($snapshot, $mergeWith);
	}

	Configure::write('QuickApps', $snapshot);
	Configure::dump('snapshot.php', 'QuickApps', ['QuickApps']);
}

/**
 * Replace the first occurrence only.
 *
 * @param string $search The value being searched for
 * @param string $replace The replacement value that replaces found search value
 * @param string $subject The string being searched and replaced on
 * @return string A string with the replaced value
 */
function str_replace_once($search, $replace, $subject) {
	if (strpos($subject, $search) !== false) {
		$occurrence = strpos($subject, $search);
		return substr_replace($subject, $replace, strpos($subject, $search), strlen($search));
	}

	return $subject;
}

/**
 * Check if $haystack string starts with $needle string.
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function str_starts_with($haystack, $needle) {
    return
    	$needle === '' ||
    	strpos($haystack, $needle) === 0;
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
		$needle === '' ||
		substr($haystack, - strlen($needle)) === $needle;
}
