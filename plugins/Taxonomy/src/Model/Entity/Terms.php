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
namespace Taxonomy\Model\Entity;

use Cake\ORM\Entity;

/**
 * Represents a single "term" within "terms" table.
 *
 */
class Terms extends Entity
{

    /**
     * Removes any invalid characters from term's name.
     *
     * @param string $value Term's name
     * @return string
     */
    protected function _setName($value)
    {
        $value = strip_tags($value);
        $value = str_replace(["\n", "\r"], '', $value);
        return trim($value);
    }
}
