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
namespace Block\Model\Entity;

use Block\Model\Entity\WidgetTrait;
use Cake\ORM\Entity;
use User\Model\Entity\AccessibleEntityTrait;

/**
 * Represents a single "block" within "blocks" table.
 *
 * @property int $id
 * @property array $locale
 * @property array $roles
 * @property array $region
 * @property array $_matchingData
 * @property string $pages
 * @property string $visibility
 * @property string $handler
 * @property string $description
 * @method bool isAccessible(array|null $roles = null)
 */
class Block extends Entity
{
    use AccessibleEntityTrait;
    use WidgetTrait;

    /**
     * Whether this blocks is a custom block or a widget block.
     *
     * @return bool
     */
    public function isCustom()
    {
        return $this->get('handler') === 'Block\Widget\CustomBlockWidget';
    }

    /**
     * Checks if whether this block can be rendered or not, that is, exists an event
     * listeners for handling block rendering task.
     *
     * @return bool
     */
    public function renderable()
    {
        return $this->has('handler') && class_exists($this->get('handler'));
    }

    /**
     * Sanitizes block's description. No HTML allowed.
     *
     * @param string $description Block's description
     * @return string
     */
    protected function _setDescription($description)
    {
        return strip_tags($description);
    }

    /**
     * Tries to get block's region.
     *
     * This method is used when finding blocks matching a particular region.
     *
     * @return \Block\Model\Entity\BlockRegion
     */
    protected function _getRegion()
    {
        if (isset($this->_matchingData['BlockRegions'])) {
            return $this->_matchingData['BlockRegions'];
        }

        if (isset($this->_properties['region'])) {
            return $this->_properties['region'];
        }
    }
}
