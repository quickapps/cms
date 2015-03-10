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
 *
 * @property int $id
 * @property string $regionCode
 * @property string $languageCode
 */
class Language extends Entity
{

    /**
     * Returns language's region code.
     *
     * @return string language's region code. e.g. "NZ" (for en_NZ), "EE" (for et_EE)
     */
    protected function _getRegionCode()
    {
        list(, $country) = localeSplit($this->get('code'));
        return $country;
    }

    /**
     * Gets language's ISO-639-1 code.
     *
     * @return string language's code. e.g. "en" (for en_NZ), "et" (for et_EE)
     */
    protected function _getLanguageCode()
    {
        list($language, ) = localeSplit($this->get('code'));
        return $language;
    }
}
