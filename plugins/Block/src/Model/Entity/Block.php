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

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Represents a single "block" within "blocks" table.
 *
 */
class Block extends Entity
{

    /**
     * Automatically calculates "delta" for entity's handler.
     *
     * Delta values must be unique within a handler name. That is, there may exists
     * two rows with delta = 1, but with different handler values.
     *
     * @return void
     */
    public function calculateDelta()
    {
        if ($this->isNew() && $this->has('handler') && !$this->get('delta')) {
            $latest = TableRegistry::get('Block.Blocks')->find()
                ->select('id')
                ->where(['handler' => $this->handler])
                ->order(['id' => 'DESC'])
                ->first();
            $lastId = $latest ? $latest->id : 0;
            $this->set('delta', $lastId + 1);
        }
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
     * @see \Block\View\Helper\BlockHelper
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
