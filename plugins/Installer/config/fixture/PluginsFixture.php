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
class PluginsFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'plugins';
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
        0 => 'name',
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
    'name' =>
    [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'package' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'composer package. e.g. user_name/plugin_name',
    'precision' => null,
    'fixed' => null,
    ],
    'settings' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => 'serialized array of options',
    'precision' => null,
    ],
    'status' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => '',
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
