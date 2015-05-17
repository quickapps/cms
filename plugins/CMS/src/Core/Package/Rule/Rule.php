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

/**
 * Represents a rule.
 *
 */
class Rule
{

    /**
     * Left hand side of the rule.
     *
     * @var \CMS\Core\Package\BasePackage|string
     */
    protected $_lhs = '';

    /**
     * Right hand side of the rule.
     *
     * @var string
     */
    protected $_rhs = '';

    /**
     * Constructor.
     *
     * @param \CMS\Core\Package\BasePackage|string $lhs Left part
     * @param string $rhs Right part
     */
    public function __construct($lhs, $rhs)
    {
        $this->_lhs = $lhs;
        $this->_rhs = $rhs;
    }

    /**
     * Gets left hand side of the rule.
     *
     * @return string
     */
    public function lhs()
    {
        return $this->_lhs;
    }

    /**
     * Gets right hand side of the rule.
     *
     * @return string
     */
    public function rhs()
    {
        return $this->_rhs;
    }

    /**
     * Alias for lhs().
     *
     * @return string
     */
    public function package()
    {
        return $this->lhs();
    }

    /**
     * Alias for rhs().
     *
     * @return string
     */
    public function constraint()
    {
        return $this->rhs();
    }

    /**
     * String representation of this rule.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->lhs() . ' (' . $this->rhs() . ')';
    }
}
