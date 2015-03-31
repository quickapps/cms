<?php
class FieldValuesFixture
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
    'field_instance_id' =>
    [
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'field_instance_slug' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'entity_id' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => 'id of the entity in `table`',
    'precision' => null,
    'fixed' => null,
    ],
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'value' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'raw' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'Extra information required by this field hadnler',
    'precision' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'field_instance_id' => 1,
    'field_instance_slug' => 'article-introduction',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value' => 'Welcome to QuickAppsCMS. This is an example content.',
    'raw' => 'a:0:{}',
    ],
    1 =>
    [
    'id' => 9,
    'field_instance_id' => 3,
    'field_instance_slug' => 'article-body',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>

<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>

<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc</p>
',
    'raw' => 'a:0:{}',
    ],
    2 =>
    [
    'id' => 12,
    'field_instance_id' => 4,
    'field_instance_slug' => 'page-body',
    'entity_id' => '3',
    'table_alias' => 'nodes:page',
    'value' => '<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.&nbsp;<span style="line-height:1.6">Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</span></p>

<p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</p>

<p>Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>
',
    'raw' => 'a:0:{}',
    ],
    3 =>
    [
    'id' => 13,
    'field_instance_id' => 5,
    'field_instance_slug' => 'article-category',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value' => 'PHP QuickAppsCMS',
    'raw' => 'a:2:{i:0;s:1:"1";i:1;s:1:"5";}',
    ],
    ];
}
