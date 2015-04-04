<?php
class EntitiesTermsFixture
{

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
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'entity_id' => 1,
    'term_id' => 1,
    'field_instance_id' => 4,
    'table_alias' => 'nodes',
    ],
    1 =>
    [
    'id' => 2,
    'entity_id' => 1,
    'term_id' => 5,
    'field_instance_id' => 4,
    'table_alias' => 'nodes',
    ],
    ];
}
