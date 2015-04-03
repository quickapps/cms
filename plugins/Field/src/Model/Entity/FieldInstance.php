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
namespace Field\Model\Entity;

use Cake\ORM\Entity;
use QuickApps\Event\HookAwareTrait;

/**
 * Represents an "instance" from the "field_instances" database table.
 *
 * @property int $id
 * @property string $slug
 * @property string $handler
 * @property string $table_alias
 * @property string $label
 * @property string $description
 * @property string $type
 * @property bool $required
 * @property array $view_modes
 * @property array $settings
 */
class FieldInstance extends Entity
{

    use HookAwareTrait;

    /**
     * Gets a human-readable name of the field handler class.
     *
     * @return string
     */
    protected function _getHandlerName()
    {
        $info = $this->trigger("Field.{$this->handler}.Instance.info");

        if (!empty($info->result['name'])) {
            return $info->result['name'];
        }

        return $this->handler;
    }
}
