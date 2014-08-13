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
namespace QuickApps\View;

use Cake\Error\InternalErrorException;

/**
 * ViewModeRegistry is used as a registry for handling view modes, also provides a few
 * utility methods such as inUseViewMode().
 *
 * View modes tells nodes how they should be rendered.
 *
 * Plugins are allowed to register their own view modes at any time.
 * But it is recommended to do this at bootstrap so all plugins will be aware
 * of this new view mode soon as possible.
 */
class ViewModeRegistry {

/**
 * Holds the current view mode being used.
 *
 * @var string
 */
	protected static $_inUse;

/**
 * Holds an array list of all registered view modes.
 *
 * @var array
 */
	protected static $_viewModes = [];

/**
 * Marks as "in use" the given view mode.
 *
 * The given view mode must be registered first using `registerViewMode`. If you
 * try to switch to an unexisting (unregistered) view mode this method will throw.
 *
 * @param string $slug
 * @return void
 * @throws \Cake\Error\InternalErrorException When switching to an unregistered view mode
 */
	public static function switchViewMode($slug) {
		if (empty(static::$_viewModes[$slug])) {
			throw new InternalErrorException(__('Illegal usage of ViewModeRegistry::switchViewMode(), view mode "{0}" was not found.', $slug));
		}

		static::$_inUse = $slug;
	}

/**
 * Registers a new view mode. Or overwrite if already exists.
 *
 * You can register more than one view mode at once by passing an array as first
 * argument and ignore the others two:
 *
 *     ViewModeRegistry::registerViewMode([
 *         'slug_1' => [
 *         	    'name' => 'View Mode 1',
 *         	    'description' => 'Lorem ipsum',
 *         	],
 *         'slug_2' => [
 *         	    'name' => 'View Mode 2',
 *         	    'description' => 'Dolor sit amet',
 *         	],
 *     ]);
 *
 * Or you can register a single view mode by passing its "slug", "name"
 * and "description" as three separated arguments:
 *
 *     ViewModeRegistry::registerViewMode('slug-1', 'View Mode 1', 'Lorem ipsum');
 *     ViewModeRegistry::registerViewMode('slug-2', 'View Mode 2', 'Dolor sit amet');
 *
 * @param string|array $slug Slug name of your view mode. e.g.: `my-view mode`.
 * Or an array of view modes to register indexed by slug name
 * @param string|null $name Human readable name. e.g.: `My View Mode`
 * @param string|null $description A brief description about for what is this view mode
 * @return void
 */
	public static function registerViewMode($slug, $name = null, $description = null) {
		if (is_array($slug) && $name === null && $description === null) {
			foreach ($slug as $slug => $more) {
				if (!empty($more['name']) && !empty($more['description'])) {
					static::$_viewModes[$slug] = [
						'name' => $more['name'],
						'description' => $more['description'],
					];
				}
			}
		} else {
			static::$_viewModes[$slug] = [
				'name' => $name,
				'description' => $description,
			];
		}
	}

/**
 * Gets the in use view-mode information.
 *
 * You can get either, slug only or full information as an array.
 *
 * @param boolean $full Set to true to get full information as an array.
 * Or set to false (by default) to get slug name only
 * @return array|string
 */
	public static function inUseViewMode($full = false) {
		if (empty(static::$_inUse)) {
			return '';
		} elseif ($full === false) {
			return static::$_inUse;
		}

		return static::$_viewModes[static::$_inUse];
	}

/**
 * Gets the full list of all registered view modes.
 *
 * You can get either a full list of every registered view mode, or a plain list
 * of slugs of every registered view mode. Or you can get all information for a
 * particular view mode by passing its slug as first argument.
 *
 * ## Usage:
 *
 * ### Get a list of View Modes slugs:
 * 
 *     ViewModeRegistry::viewModes();
 *     // output:
 *     ['teaser', 'full', ...]
 *
 * ### Get a full list of every View Mode:
 * 
 *     ViewModeRegistry::viewModes(true);
 *     // output:
 *     [
 *         'teaser' => [
 *             'name' => 'Human readable for teaser mode',
 *             'description' => 'Brief description for teaser view-mode'
 *         ],
 *         'full' => [
 *             'name' => 'Human readable for full mode',
 *             'description' => 'Brief description for full view-mode'
 *         ],
 *         ...
 *     ]
 *
 * ### Get full information for a particular View Mode:
 *
 *     ViewModeRegistry::viewModes('teaser');
 *     // output:
 *     [
 *         'name' => 'Human readable for teaser mode',
 *          'description' => 'Brief description for teaser view-mode'
 *     ]
 *
 * @param boolean $full Set to true to get full list. Or false (by default) to get
 * only the slug of all registered view modes.
 * @return array
 * @throws \Cake\Error\InternalErrorException When you try to get information
 * for a particular View Mode that does not exists
 */
	public static function viewModes($full = false) {
		if (is_string($full)) {
			if (!isset(static::$_viewModes[$full])) {
				throw new InternalErrorException(__('Illegal usage of ViewModeRegistry::switchViewMode(), view mode "{0}" was not found.', $slug));
			}

			return static::$_viewModes[$full];
		} elseif (!$full) {
			return array_keys(static::$_viewModes);
		}

		return static::$_viewModes;
	}

}
