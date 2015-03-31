<?php
class SearchDatasetsFixture
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
    'search_datasets_entity_id' =>
    [
      'type' => 'unique',
      'columns' =>
      [
        0 => 'entity_id',
        1 => 'table_alias',
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
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'words' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'entity_id' => '1',
    'table_alias' => 'nodes',
    'words' => ' ',
    ],
    1 =>
    [
    'id' => 3,
    'entity_id' => '3',
    'table_alias' => 'nodes',
    'words' => ' about about quickappscms demo page p duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi nbsp span style line height nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum span p p typi non habent claritatem insitam est usus legentis in iis qui facit eorum claritatem investigationes demonstraverunt lectores legere me lius quod ii legunt saepius claritas est etiam processus dynamicus qui sequitur mutationem consuetudium lectorum p p mirum est notare quam littera gothica quam nunc putamus parum claram anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima eodem modo typi qui nunc nobis videntur parum clari fiant sollemnes in futurum p ',
    ],
    ];
}
