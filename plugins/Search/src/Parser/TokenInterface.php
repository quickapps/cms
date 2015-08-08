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
namespace Search\Parser;

/**
 * Represents a token within a search criteria.
 *
 */
interface TokenInterface
{

    /**
     * Indicates the type of "where()" ORM method that should be used to scope when
     * using this token: "andWhere()", "orWhere" or just "where()".
     *
     * - `or`: Indicates that `Query::orWhere()` should be used
     * - `and`: Indicates that `Query::andWhere()` should be used
     * - `NULL`: Indicates that `Query::where()` should be used
     *
     * @return string Possible values are: `or`, `and` & null
     */
    public function where();

    /**
     * Whether this token represents an operator or not.
     *
     * @return bool True if it's an operator
     */
    public function isOperator();

    /**
     * Gets operator's name.
     *
     * Should be used only when this token is an operator.
     *
     * @return string
     */
    public function operatorName();

    /**
     * Gets operator's argument.
     *
     * Should be used only when this token is an operator.
     *
     * @return string
     */
    public function operatorArguments();

    /**
     * Gets operator's name (if this token is an operator), or string
     * representation of this token (if ain't operator).
     *
     * @return string
     */
    public function name();

    /**
     * Gets operator's value (if this token is an operator), or string
     * representation of this token (if ain't operator).
     *
     * @return string
     */
    public function value();

    /**
     * Indicates this token was negated using "-" symbol. e.g. `-"no this phrase"`
     *
     * @return bool True if it's negated
     */
    public function negated();

    /**
     * Magic method.
     *
     * @return string
     */
    public function __toString();
}
