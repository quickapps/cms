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
namespace Search\Parser\MiniLanguage;

use Cake\Utility\Inflector;
use Search\Parser\TokenInterface;

/**
 * Represents a token within a search criteria.
 */
class Token implements TokenInterface
{

    /**
     * Token information.
     *
     * @var array
     */
    protected $_data = [
        'string' => null,
        'negated' => false,
        'where' => null,
        'isOperator' => false,
        'operatorName' => '',
        'operatorArguments' => '',
    ];

    /**
     * Constructor.
     *
     * @param string $token The string representing this token
     */
    public function __construct($token, $where = null)
    {
        $minusPosition = strpos($token, '-');
        $this->_data['string'] = $minusPosition === 0 ? substr($token, $minusPosition + 1) : $token;
        $this->_data['negated'] = $minusPosition === 0;
        $this->_data['where'] = $where !== null ? strtolower($where) : null;
        $this->_data['isOperator'] = mb_strpos($token, ':') !== false;

        if ($this->_data['isOperator']) {
            $parts = explode(':', $token);
            $this->_data['operatorName'] = (string)Inflector::underscore(preg_replace('/\PL/u', '', $parts[0]));
            $this->_data['operatorArguments'] = !empty($parts[1]) ? $parts[1] : '';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function where()
    {
        return $this->_data['where'];
    }

    /**
     * {@inheritDoc}
     */
    public function isOperator()
    {
        return $this->_data['isOperator'];
    }

    /**
     * {@inheritDoc}
     */
    public function operatorName()
    {
        return $this->_data['operatorName'];
    }

    /**
     * {@inheritDoc}
     */
    public function operatorArguments()
    {
        return $this->_data['operatorArguments'];
    }

    /**
     * {@inheritDoc}
     */
    public function name()
    {
        if ($this->isOperator()) {
            return $this->_data['operatorName'];
        }
        return $this->_data['string'];
    }

    /**
     * {@inheritDoc}
     */
    public function value()
    {
        if ($this->isOperator()) {
            return $this->_data['operatorArguments'];
        }
        return $this->_data['string'];
    }

    /**
     * {@inheritDoc}
     */
    public function negated()
    {
        return $this->_data['negated'];
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->_data['string'];
    }
}
