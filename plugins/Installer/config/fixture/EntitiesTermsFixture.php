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
class EntitiesTermsFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'entities_terms';
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
    'entity_id' =>
    [
    'type' => 'integer',
    'length' => 20,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'term_id' =>
    [
    'type' => 'integer',
    'length' => 20,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'field_instance_id' =>
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
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 30,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    ];
/**
 * Table records.
 *
 * @var array
 */
    public $records = [
    0 =>
    [
    'id' => 3,
    'entity_id' => 1,
    'term_id' => 1,
    'field_instance_id' => 4,
    'table_alias' => 'contents',
    ],
    1 =>
    [
    'id' => 4,
    'entity_id' => 1,
    'term_id' => 5,
    'field_instance_id' => 4,
    'table_alias' => 'contents',
    ],
    ];
}
