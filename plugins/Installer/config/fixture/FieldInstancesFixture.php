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
class FieldInstancesFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'field_instances';
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
    'field_instances_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'id',
      ],
      'length' =>
      [
      ],
    ],
    'field_instances_eav_attribute_id_index' =>
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
    'handler' =>
    [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'Name of event handler class under the `Field` namespace',
    'precision' => null,
    'fixed' => null,
    ],
    'label' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'Human readble name, used in views. eg: `First Name` (for a textbox)',
    'precision' => null,
    'fixed' => null,
    ],
    'description' =>
    [
    'type' => 'string',
    'length' => 250,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'instructions to present to the user below this field on the editing form.',
    'precision' => null,
    'fixed' => null,
    ],
    'required' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => null,
    ],
    'settings' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'Serialized information',
    'precision' => null,
    ],
    'view_modes' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'type' =>
    [
    'type' => 'string',
    'length' => 10,
    'null' => false,
    'default' => 'varchar',
    'collate' => 'utf8_unicode_ci',
    'comment' => 'Data type for this field (datetime, decimal, int, text, varchar)',
    'precision' => null,
    'fixed' => null,
    ],
    'locked' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
    'precision' => null,
    ],
    'ordering' =>
    [
    'type' => 'integer',
    'length' => 3,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    ];
/**
 * Table records.
 *
 * @var array
 */
    public $records = null;
}
