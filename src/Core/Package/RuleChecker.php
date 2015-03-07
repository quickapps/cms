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
namespace QuickApps\Core\Package;

use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\Package\Version\VersionParser;
use QuickApps\Core\Package\BasePackage;
use QuickApps\Core\Package\PackageFactory;
use QuickApps\Core\StaticCacheTrait;

/**
 * Checks that the given set of rules are meet.
 *
 */
class RuleChecker
{

    use StaticCacheTrait;

    /**
     * List of rules to check.
     *
     * @var array
     */
    protected $_rules = [];

    /**
     * List of rules marked as FAIL.
     *
     * @var array
     */
    protected $_fail = [];

    /**
     * List of rules marked as PASS.
     *
     * @var array
     */
    protected $_pass = [];

    /**
     * Constructor.
     *
     * ### Basic usage:
     *
     * ```php
     * $rules = [
     *     'php' => '5.3.*',
     *     'quickapps/cms' => '2.*',
     * ];
     *
     * $solver = new RuleChecker($rules);
     *
     * if ($solver->check()) {
     *     // all OK
     * } else {
     *     // ERROR, get failing rules:
     *     $solver->fail();
     * }
     * ```
     *
     * ### Providing packages as objects:
     *
     * ```php
     * $rules = [
     *     new MyPackage('vendor/package', '/path/to/package') => '1.3.*',
     *     'quickapps/cms' => '2.*',
     * ];
     * }
     * ```
     *
     * @param array $rules A list of rules given as `package` => `constraints`,
     *  where the left hand side (package) can be either a string representing
     *  a package (as "vendor/package") or an object extending
     *  \QuickApps\Core\Package\BasePackage
     */
    public function __construct(array $rules)
    {
        foreach ($rules as $lhs => $rhs) {
            $this->_rules[] = new RuleConstraint($lhs, $rhs);
        }
    }

    /**
     * Check if all rules of this class.
     *
     * @return bool
     */
    public function check()
    {
        $pass = true;
        foreach ($this->_rules as $rule) {
            if ($rule->lhs() instanceof BasePackage) {
                $package = $rule->lhs();
            } else {
                $package = PackageFactory::create($rule->lhs());
            }

            if (!$package->versionMatch($rule->rhs())) {
                $this->_fail($rule);
                $pass = false;
            } else {
                $this->_pass($rule);
            }
        }

        return $pass;
    }

    /**
     * Gets all rules marked as PASS.
     *
     * @param bool $asString True will returns a comma separated string of rules
     * @return array|string
     */
    public function pass($asString = false)
    {
        if (!$asString) {
            return $this->_pass;
        }

        $items = [];
        foreach ($this->_pass as $rule) {
            $items[] = "{$rule}";
        }

        return implode(', ', $items);
    }

    /**
     * Gets all rules marked as FAIL.
     *
     * @param bool $asString True will returns a comma separated string of rules
     * @return array|string
     */
    public function fail($asString = false)
    {
        if (!$asString) {
            return $this->_fail;
        }

        $items = [];
        foreach ($this->_fail as $rule) {
            $items[] = "{$rule}";
        }

        return implode(', ', $items);
    }

    /**
     * Marks a rule as PASS.
     *
     * @param \QuickApps\Core\Package\RuleConstraint $rule The rule to mark
     * @return void
     */
    protected function _pass(RuleConstraint $rule)
    {
        $this->_pass[] = $rule;
    }

    /**
     * Marks a rule as FAIL.
     *
     * @param \QuickApps\Core\Package\RuleConstraint $rule The rule to mark
     * @return void
     */
    protected function _fail(RuleConstraint $rule)
    {
        $this->_fail[] = $rule;
    }
}
