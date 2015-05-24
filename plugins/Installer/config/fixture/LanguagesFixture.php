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

class LanguagesFixture
{

    public $table = 'languages';

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
    'languages_code' =>
    [
      'type' => 'unique',
      'columns' =>
      [
        0 => 'code',
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
    'code' =>
    [
    'type' => 'string',
    'length' => 12,
    'null' => false,
    'default' => null,
    'comment' => 'Language code, e.g. ’eng’',
    'precision' => null,
    'fixed' => null,
    ],
    'name' =>
    [
    'type' => 'string',
    'length' => 64,
    'null' => false,
    'default' => null,
    'comment' => 'Language name in English.',
    'precision' => null,
    'fixed' => null,
    ],
    'direction' =>
    [
    'type' => 'string',
    'length' => 3,
    'null' => false,
    'default' => 'ltr',
    'comment' => 'Direction of language (Left-to-Right , Right-to-Left ).',
    'precision' => null,
    'fixed' => null,
    ],
    'icon' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'status' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'Enabled flag (1 = Enabled, 0 = Disabled).',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'ordering' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'Weight, used in lists of languages.',
    'precision' => null,
    'autoIncrement' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'code' => 'en_US',
    'name' => 'English (US)',
    'direction' => 'ltr',
    'icon' => 'us.gif',
    'status' => 1,
    'ordering' => 0,
    ],
    1 =>
    [
    'id' => 2,
    'code' => 'es_ES',
    'name' => 'Spanish (ES)',
    'direction' => 'ltr',
    'icon' => 'es.gif',
    'status' => 1,
    'ordering' => 0,
    ],
    ];
}
