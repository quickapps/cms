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
class EavValuesFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'eav_values';
/**
 * Table columns.
 *
 * @var array
 */
    public $fields = [
    '_constraints' =>
    [
    'primary' =>
    [
      'type' => 'primary',
      'columns' =>
      [
        0 => 'id',
      ],
      'length' =>
      [
      ],
    ],
    ],
    '_indexes' =>
    [
    'eav_values_eav_attribute_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'eav_attribute_id',
      ],
      'length' =>
      [
      ],
    ],
    'eav_values_entity_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'entity_id',
      ],
      'length' =>
      [
      ],
    ],
    ],
    '_options' =>
    [
    'engine' => 'InnoDB',
    'collation' => 'utf8_unicode_ci',
    ],
    'id' =>
    [
    'type' => 'integer',
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => null,
    ],
    'eav_attribute_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'entity_id' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'id of the entity in `table`',
    'precision' => null,
    'fixed' => null,
    ],
    'value_datetime' =>
    [
    'type' => 'datetime',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'value_binary' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'value_time' =>
    [
    'type' => 'time',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'value_date' =>
    [
    'type' => 'date',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'value_float' =>
    [
    'type' => 'decimal',
    'length' => 10,
    'precision' => 0,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    ],
    'value_integer' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'value_biginteger' =>
    [
    'type' => 'biginteger',
    'length' => 20,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'value_text' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    ],
    'value_string' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'value_boolean' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'value_uuid' =>
    [
    'type' => 'string',
    'length' => 36,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'extra' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'serialized additional information',
    'precision' => null,
    ],
    ];
/**
 * Table records.
 *
 * @var array
 */
    public $records = null;
}
