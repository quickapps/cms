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
namespace CMS\Core\Package\Rule;

use CMS\Core\Package\Composer\Package\LinkConstraint\VersionConstraint;
use CMS\Core\Package\Composer\Package\Version\VersionParser;

/**
 * Provides match() method.
 *
 */
class Constraint
{

    /**
     * Check whether a version matches the given constraint.
     *
     * ### Example:
     *
     * ```php
     * Constraint::match('1.2.6', '>=1.1'); // returns true
     * ```
     *
     * @param string $version The version to check against (e.g. v4.2.6)
     * @param string $constraints A string representing a dependency constraints,
     *  for instance, `>7.0 || 1.2`
     * @return bool True if compatible, false otherwise
     * @see https://getcomposer.org/doc/01-basic-usage.md#package-versions
     */
    public static function match($version, $constraints = null)
    {
        if (is_string($version) && empty($version)) {
            return false;
        }

        if (empty($constraints) || $version == $constraints) {
            return true;
        }

        $parser = new VersionParser();
        $modifierRegex = '[\-\@]dev(\#\w+)?';
        $constraints = preg_replace('{' . $modifierRegex . '$}i', '', $constraints);
        $version = $parser->normalize($version);
        $version = preg_replace('{' . $modifierRegex . '$}i', '', $version);

        if (empty($constraints) || $version == $constraints) {
            return true;
        }

        try {
            $pkgConstraint = new VersionConstraint('==', $version);
            $constraintObjects = $parser->parseConstraints($constraints);
            return $constraintObjects->matches($pkgConstraint);
        } catch (\Exception $ex) {
            return false;
        }
    }
}
