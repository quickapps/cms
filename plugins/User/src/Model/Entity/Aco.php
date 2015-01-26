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
namespace User\Model\Entity;

use Cake\ORM\Entity;
use QuickApps\Core\Plugin;

/**
 * Represents single "aco" in "acos" database table.
 *
 */
class Aco extends Entity
{

    /**
     * For usage as part of MenuHelper.
     *
     * @return string
     */
    protected function _getTitle()
    {
        if ($this->_getIsPlugin()) {
            try {
                $info = Plugin::info($this->alias);
                return $info['human_name'];
            } catch (\Exception $e) {
                return $this->alias;
            }
        }
        return $this->alias;
    }

    /**
     * For usage as part of MenuHelper.
     *
     * @return bool
     */
    protected function _getExpanded()
    {
        return true;
    }

    /**
     * For usage as part of MenuHelper.
     *
     * @return string
     */
    protected function _getPluginName()
    {
        $info = Plugin::info($this->alias);
        return $info['human_name'];
    }

    /**
     * For usage as part of MenuHelper.
     *
     * @return bool
     */
    protected function _getIsPlugin()
    {
        return empty($this->parent_id);
    }
}
