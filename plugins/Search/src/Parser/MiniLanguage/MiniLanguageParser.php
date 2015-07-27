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

use Search\Parser\ParserInterface;

/**
 * Mini-language query expressions parser.
 */
class MiniLanguageParser implements ParserInterface
{

    /**
     * Language sentence to be parsed
     *
     * @var string
     */
    protected $_criteria = '';

    /**
     * Constructor.
     *
     * @param string $criteria Language sentence to be parsed
     */
    public function __construct($criteria = '')
    {
        $this->_criteria = $criteria;
    }

    /**
     * {@inheritDoc}
     */
    public function parse($criteria = '')
    {
        if (empty($criteria)) {
            $criteria = $this->_criteria;
        }

        $criteria = trim(urldecode($criteria));
        $criteria = preg_replace('/(-?[\w]+)\:"([\]\[\w\s]+)/', '"${1}:${2}', $criteria);
        $criteria = str_replace(['-"', '+"'], ['"-', '"+'], $criteria);
        $parts = str_getcsv($criteria, ' ');
        $tokens = [];

        foreach ($parts as $i => $t) {
            if (in_array(strtolower($t), ['or', 'and'])) {
                continue;
            }

            $where = null;
            if (isset($parts[$i - 1]) &&
                in_array(strtolower($parts[$i - 1]), ['or', 'and'])
            ) {
                $where = $parts[$i - 1];
            }

            $tokens[] = new Token($t, $where);
        }

        return $tokens;
    }
}
