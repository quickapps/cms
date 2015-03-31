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
use User\Model\Entity\AccessibleEntityTrait;

/**
 * Represents a single "block" within "blocks" table.
 *
 * @property int $id
 * @property array $locale
 * @property array $roles
 * @property array $region
 * @property array $_matchingData
 * @property string $delta
 * @property string $pages
 * @property string $visibility
 * @property string $handler
 * @property string $description
 * @method bool isAccessible(array|null $roles = null)
 */
class Block extends Entity
{
    use AccessibleEntityTrait;

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
            $latest = TableRegistry::get('Block.Blocks')
                ->find()
                ->select('id')
                ->where(['handler' => $this->handler])
                ->order(['id' => 'DESC'])
                ->first();
            $delta = $latest ? $latest->id : 0;
            $count = 1;
            while ($count > 0) {
                $delta++;
                $count = TableRegistry::get('Block.Blocks')
                    ->find()
                    ->select('id')
                    ->where(['handler' => $this->handler, 'delta' => $delta])
                    ->limit(1)
                    ->count();
            }
            $this->set('delta', $delta);
        }
    }

    /**
     * Checks if whether this block can be rendered or not.
     *
     * @return bool
     */
    public function renderable()
    {
        if ($this->get('handler') === 'Block') {
            return true;
        }
        foreach (listeners() as $listener) {
            if (str_starts_with($listener, 'Block.' . $this->get('handler'))) {
                return true;
            }
        }
        return false;
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
