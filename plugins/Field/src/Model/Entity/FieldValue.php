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

/**
 * Represents a "value" from the "field_values" database table.
 *
 * @property int $id
 * @property int $field_instance_id
 * @property int $entity_id
 * @property string $field_instance_slug
 * @property string $table_alias
 * @property string $type
 * @property mixed $value
 * @property object $value_datetime
 * @property float $value_decimal
 * @property int $value_int
 * @property string $value_text
 * @property string $value_varchar
 * @property object|array $value_serialized
 */
class FieldValue extends Entity
{
}
