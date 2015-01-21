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
use Cake\Utility\String;

/**
 * Represents a single "vocabulary" within "vocabularies" table.
 *
 */
class Vocabulary extends Entity
{

    /**
     * Gets a brief description of 80 characters long.
     *
     * @return string
     */
    protected function _getBriefDescription()
    {
        $description = $this->get('description');
        if (empty($description)) {
            return '---';
        }
        return String::truncate($description, 80);
    }

    /**
     * Sanitizes vocabulary's description. No HTML allowed.
     *
     * @param string $description Vocabulary's description
     * @return string
     */
    protected function _setDescription($description)
    {
        return strip_tags($description);
    }
}
