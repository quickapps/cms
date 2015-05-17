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
namespace CMS\Core\Package\Composer;

/**
 * Represents Composer's "composer.json" file schema.
 *
 */
class JsonSchema
{

    /**
     * Default options for composer's json file.
     *
     * @var array
     * @see https://getcomposer.org/doc/04-schema.md
     */
    public static $schema = [
        'name' => null,
        'description' => '---',
        'version' => 'dev-master',
        'type' => null,
        'keywords' => [],
        'homepage' => null,
        'time' => null,
        'license' => null,
        'authors' => [],
        'support' => [
            'email' => null,
            'issues' => null,
            'forum' => null,
            'wiki' => null,
            'irc' => null,
            'source' => null,
        ],
        'require' => [],
        'require-dev' => [],
        'conflict' => [],
        'replace' => [],
        'provide' => [],
        'suggest' => [],
        'autoload' => [
            'psr-4' => [],
            'psr-0' => [],
            'classmap' => [],
            'files' => [],
        ],
        'autoload-dev' => [
            'psr-4' => [],
            'psr-0' => [],
            'classmap' => [],
            'files' => [],
        ],
        'target-dir' => null,
        'minimum-stability' => null,
        'repositories' => [],
        'config' => [],
        'archive' => [],
        'prefer-stable' => true,
        'scripts' => [],
        'extra' => [],
        'bin' => [],
    ];
}
