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
namespace Installer\Utility;

/**
 * Used to test if server meets all requirements to install QuickAppsCMS.
 *
 */
class ServerTest
{

    /**
     * Rules to be checked.
     *
     * @var array
     */
    protected $_rules = [];

    /**
     * Whether or not to use I18n functions for translating default error messages.
     *
     * @var bool
     */
    protected $_useI18n = false;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->_useI18n = function_exists('__d');
    }

    /**
     * Returns an array of fields that have failed validation. On the current model.
     * This method will actually run validation rules over data, not just return
     * the messages.
     *
     * @return array
     */
    public function errors()
    {
        $errors = [];
        foreach ($this->_rules as $name => $rule) {
            if ($rule['rule']() !== true) {
                $errors[$name] = $rule['message'];
            }
        }

        return $errors;
    }

    /**
     * Adds a new test to run later using "errors()".
     *
     * ### Usage:
     *
     * ```php
     * $tests = new ServerTest();
     * $test->add('php_version',
     *     version_compare(PHP_VERSION, '5.4.19', '>='),
     *     'PHP version must be >= 5.4.19'
     * );
     * ```
     *
     * ```php
     * $tests = new ServerTest();
     * $test->add('php_version', [
     *     'rule' => function () {
     *         return version_compare(PHP_VERSION, '5.4.19', '>=');
     *     },
     *     'message' => 'PHP version must be >= 5.4.19',
     * ]);
     * ```
     *
     * ```php
     * $tests = new ServerTest();
     * $test->add('php_version',
     *     function () {
     *         return version_compare(PHP_VERSION, '5.4.19', '>=');
     *     },
     *     'PHP version must be >= 5.4.19'
     * );
     * ```
     *
     * @param string $name Name of the test
     * @param array|callable|bool $rule If an array, keys `rule` and `message` are
     *  required. If a callable is given, it should return boolean.
     * @param string|null $message Message to show on test failure
     * @return $this
     */
    public function add($name, $rule, $message = null)
    {
        $callback = function () {
            return false;
        };

        if (empty($message)) {
            $message = $this->_useI18n ? __d('installer', 'Test failed') : 'Test failed';
        }

        if (is_bool($rule)) {
            $callback = function () use ($rule) {
                return $rule;
            };
        } elseif (is_callable($rule)) {
            $callback = $rule;
        } elseif (is_array($rule)) {
            $rule += [
                'rule' => false,
                'message' => $this->_useI18n ? __d('installer', 'Test failed') : 'Test failed',
            ];

            return $this->add($name, $rule['rule'], $rule['message']);
        }

        $this->_rules[$name] = [
            'rule' => $callback,
            'message' => $message,
        ];

        return $this;
    }
}
