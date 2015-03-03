<?php
trait LanguagesSchemaTrait
{

    protected $_fields = [
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
    '705b6ee8efbfe82e22c77a2a6357a902' => 
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
  'id' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'code' => 
  [
    'type' => 'string',
    'length' => 12,
    'null' => false,
    'default' => NULL,
    'comment' => 'Language code, e.g. ’eng’',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'name' => 
  [
    'type' => 'string',
    'length' => 64,
    'null' => false,
    'default' => NULL,
    'comment' => 'Language name in English.',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'direction' => 
  [
    'type' => 'string',
    'length' => 3,
    'null' => false,
    'default' => 'ltr',
    'comment' => 'Direction of language (Left-to-Right , Right-to-Left ).',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'icon' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'status' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'Enabled flag (1 = Enabled, 0 = Disabled).',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'ordering' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'Weight, used in lists of languages.',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
];

    protected $_records = [
  0 => 
  [
    'code' => 'en-us',
    'name' => 'English',
    'direction' => 'ltr',
    'icon' => 'us.gif',
    'status' => 1,
    'ordering' => 0,
  ],
  1 => 
  [
    'code' => 'es',
    'name' => 'Spanish',
    'direction' => 'ltr',
    'icon' => 'es.gif',
    'status' => 1,
    'ordering' => 0,
  ],
];

    public function fields()
    {
        foreach ($this->_fields as $name => $info) {
            if (!empty($info['autoIncrement'])) {
                $this->_fields[$name]['length'] = null;
            }
        }
        return $this->_fields;
    }

    public function records()
    {
        return $this->_records;
    }
}

class LanguagesSchema
{

    use LanguagesSchemaTrait;

}
