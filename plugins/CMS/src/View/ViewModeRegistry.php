<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace CMS\View;

use Cake\Network\Exception\InternalErrorException;

/**
 * ViewModeRegistry is used as a registry for handling view modes, also provides
 * a few utility methods such as add() & remove().
 *
 * View modes tells contents how they should be rendered.
 *
 * Plugins are allowed to register their own view modes at any time.
 * But it is recommended to do this at bootstrap so all plugins will be aware of
 * this new view mode soon as possible.
 */
class ViewModeRegistry
{

    /**
     * Holds the current view mode being used.
     *
     * @var string
     */
    protected static $_current;

    /**
     * Holds an array list of all registered view modes.
     *
     * @var array
     */
    protected static $_modes = [];

    /**
     * Marks as "in use" the given view mode.
     *
     * The given view mode must be registered first using `add()`. If you
     * try to switch to an unexisting (unregistered) view mode this method will
     * throw and exception.
     *
     * @param string $slug View mode machine name to switch to
     * @return void
     * @throws \Cake\Network\Exception\InternalErrorException When switching to an
     *  unregistered view mode
     */
    public static function uses($slug)
    {
        if (empty(static::$_modes[$slug])) {
            throw new InternalErrorException(__('Illegal usage of ViewModeRegistry::uses(), view mode "{0}" was not found.', $slug));
        }

        static::$_current = $slug;
    }

    /**
     * Registers a new view mode. Or overwrite if already exists.
     *
     * You can register more than one view mode at once by passing an array as first
     * argument and ignore the others two:
     *
     * ```php
     * ViewModeRegistry::add([
     *     'slug_1' => [
     *         'name' => 'View Mode 1',
     *         'description' => 'Lorem ipsum',
     *     ],
     *     'slug_2' => [
     *         'name' => 'View Mode 2',
     *         'description' => 'Dolor sit amet',
     *      ],
     * ]);
     * ```
     *
     * Or you can register a single view mode by passing its "slug", "name"
     * and "description" as three separated arguments:
     *
     * ```php
     * ViewModeRegistry::add('slug-1', 'View Mode 1', 'Lorem ipsum');
     * ViewModeRegistry::add('slug-2', 'View Mode 2', 'Dolor sit amet');
     * ```
     * @param string|array $slug Slug name of your view mode. e.g.: `my-view mode`,
     *  or an array of view modes to register indexed by slug name
     * @param string|null $name Human readable name. e.g.: `My View Mode`
     * @param string|null $description A brief description about for what is this view mode
     * @return void
     */
    public static function add($slug, $name = null, $description = null)
    {
        if (is_array($slug) && $name === null && $description === null) {
            foreach ($slug as $s => $more) {
                if (!empty($more['name']) && !empty($more['description'])) {
                    static::$_modes[$s] = [
                        'name' => $more['name'],
                        'description' => $more['description'],
                    ];
                }
            }
        } elseif (is_string($slug)) {
            static::$_modes[$slug] = [
                'name' => $name,
                'description' => $description,
            ];
        }
    }

    /**
     * Unregisters the given view-mode, or all of them if first parameter is null.
     *
     * @param string|null $slug View mode's slug
     * @return void
     */
    public static function remove($slug = null)
    {
        if ($slug === null) {
            static::$_current = null;
            static::$_modes = [];
        } else {
            if (isset(static::$_modes[$slug])) {
                unset(static::$_modes[$slug]);
            }
        }
    }

    /**
     * Gets the in use view-mode information.
     *
     * You can get either, slug only or full information as an array.
     *
     * @param bool $full Set to true to get full information as an array,
     *  or set to false (by default) to get slug name only
     * @return array|string
     */
    public static function current($full = false)
    {
        if (empty(static::$_current)) {
            return '';
        } elseif ($full === false) {
            return static::$_current;
        }

        return static::$_modes[static::$_current];
    }

    /**
     * Gets the full list of all registered view modes, or for a single view mode
     * if $viewMode is set to a string value.
     *
     * You can get either a full list of every registered view mode, or a plain list
     * of slugs of every registered view mode. Or you can get all information for a
     * particular view mode by passing its slug as first argument.
     *
     * ## Usage:
     *
     * ### Get a list of View Modes slugs:
     *
     * ```php
     * ViewModeRegistry::modes();
     * // output: ['teaser', 'full', ...]
     * ```
     *
     * ### Get a full list of every View Mode:
     *
     * ```php
     * ViewModeRegistry::modes(true);
     * // output: [
     * //     'teaser' => [
     * //         'name' => 'Human readable for teaser mode',
     * //         'description' => 'Brief description for teaser view-mode'
     * //     ],
     * //     'full' => [
     * //         'name' => 'Human readable for full mode',
     * //         'description' => 'Brief description for full view-mode'
     * //     ],
     * //     ...
     * // ]
     * ```
     *
     * ### Get full information for a particular View Mode:
     *
     * ```php
     * ViewModeRegistry::modes('teaser');
     * // output: [
     * //     'name' => 'Human readable for teaser mode',
     * //     'description' => 'Brief description for teaser view-mode'
     * // ]
     * ```
     *
     * @param bool|string $mode Set to true to get full list. Or false (by default) to
     *  get only the slug of all registered view modes. Or set to a string value to
     *  get information for that view mode only.
     * @return array
     * @throws \Cake\Network\Exception\InternalErrorException When you try to get
     *  information for a particular View Mode that does not exists
     */
    public static function modes($mode = false)
    {
        if (is_string($mode)) {
            if (!isset(static::$_modes[$mode])) {
                throw new InternalErrorException(__('Illegal usage of ViewModeRegistry::modes(), view mode "{0}" was not found.', $mode));
            }

            return static::$_modes[$mode];
        } elseif (!$mode) {
            return array_keys(static::$_modes);
        }

        return static::$_modes;
    }
}
