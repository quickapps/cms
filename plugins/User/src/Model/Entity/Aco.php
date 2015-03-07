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
 * @property int $id
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property string $plugin
 * @property string $alias
 * @property string $alias_hash
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
                return Plugin::get($this->alias)->human_name;
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
        return Plugin::get($this->alias)->human_name;
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
