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

use CMS\Core\Package\BasePackage;
use CMS\Core\Package\Composer\Package\LinkConstraint\VersionConstraint;
use CMS\Core\Package\Composer\Package\Version\VersionParser;
use CMS\Core\Package\PackageFactory;
use CMS\Core\StaticCacheTrait;

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
     * Whether rules were checked using check() or not.
     *
     * @var bool
     */
    protected $_checked = false;

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
     * $checker = new RuleChecker($rules);
     *
     * if ($checker->check()) {
     *     // all OK
     * } else {
     *     // ERROR, get failing rules:
     *     $checker->fail();
     * }
     * ```
     *
     * ### Without invoking check():
     *
     * You can also use `pass()` or `fail()` methods before invoking `check()` as
     * in the example below.
     *
     * ```php
     * $checker = new RuleChecker($rules);
     * $pass = $checker->pass();
     * $fail = $checker->fail();
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
     *  \CMS\Core\Package\BasePackage
     */
    public function __construct(array $rules)
    {
        foreach ($rules as $lhs => $rhs) {
            $this->_rules[] = new Rule($lhs, $rhs);
        }
    }

    /**
     * Checks all the rules of this class.
     *
     * @return bool True if all rules are meet
     */
    public function check()
    {
        $pass = true;
        foreach ($this->_rules as $rule) {
            if ($rule->lhs() instanceof BasePackage) {
                $package = $rule->lhs();
            } else {
                $package = PackageFactory::create((string)$rule->lhs());
            }

            if (!$package->versionMatch($rule->rhs())) {
                $this->_fail($rule);
                $pass = false;
            } else {
                $this->_pass($rule);
            }
        }

        $this->_checked = true;
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
        if (!$this->_checked) {
            $this->check();
        }

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
        if (!$this->_checked) {
            $this->check();
        }

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
     * @param \CMS\Core\Package\Rule\Rule $rule The rule to mark
     * @return void
     */
    protected function _pass(Rule $rule)
    {
        $this->_pass[] = $rule;
    }

    /**
     * Marks a rule as FAIL.
     *
     * @param \CMS\Core\Package\Rule\Rule $rule The rule to mark
     * @return void
     */
    protected function _fail(Rule $rule)
    {
        $this->_fail[] = $rule;
    }
}
