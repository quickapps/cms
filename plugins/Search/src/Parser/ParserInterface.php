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
 * Parser interface, all parsers must satisfy this interface.
 */
interface ParserInterface
{

    /**
     * Extract tokens from search-criteria.
     *
     * @param string $criteria A search-criteria
     * @return array List of extracted tokens (\Search\Parser\TokenInterface)
     */
    public function parse($criteria);
}
