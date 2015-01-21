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
namespace Locale\Model\Entity;

use Cake\ORM\Entity;

/**
 * Represents a single "language" within "languages" table.
 */
class Language extends Entity
{

    /**
     * Returns language country.
     *
     * @return string language country. e.g. "US", "ES"
     */
    protected function _getCountry()
    {
        $parts = explode('-', $this->get('code'));
        if (isset($parts[1])) {
            return strtoupper($parts[1]);
        }
        return strtoupper($parts[0]);
    }

    /**
     * Gets language's ISO-639-1 code.
     *
     * @return string language country. e.g. "en", "es"
     */
    protected function _getIso()
    {
        $parts = explode('-', $this->get('code'));
        return strtolower($parts[0]);
    }
}
