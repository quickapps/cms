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
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'attribute' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'entity_id' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => 'id of the entity in `table`',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'table_alias' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'value_datetime' => 
  [
    'type' => 'datetime',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'value_decimal' => 
  [
    'type' => 'decimal',
    'length' => 10,
    'precision' => 0,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
  ],
  'value_int' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'value_text' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'value_varchar' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'extra' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'serialized additional information',
    'precision' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'attribute' => 'article-introduction',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value_datetime' => NULL,
    'value_decimal' => NULL,
    'value_int' => NULL,
    'value_text' => 'Welcome to QuickAppsCMS. This is an example content.',
    'value_varchar' => NULL,
    'extra' => '',
  ],
  1 => 
  [
    'id' => 2,
    'attribute' => 'article-body',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value_datetime' => NULL,
    'value_decimal' => NULL,
    'value_int' => NULL,
    'value_text' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>

<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>

<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc</p>
',
    'value_varchar' => NULL,
    'extra' => '',
  ],
  2 => 
  [
    'id' => 3,
    'attribute' => 'page-body',
    'entity_id' => '2',
    'table_alias' => 'nodes:page',
    'value_datetime' => NULL,
    'value_decimal' => NULL,
    'value_int' => NULL,
    'value_text' => '<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.&nbsp;<span style="line-height:1.6">Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</span></p>

<p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</p>

<p>Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>
',
    'value_varchar' => NULL,
    'extra' => 'a:0:{}',
  ],
  3 => 
  [
    'id' => 4,
    'attribute' => 'article-category',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value_datetime' => NULL,
    'value_decimal' => NULL,
    'value_int' => NULL,
    'value_text' => 'PHP QuickAppsCMS',
    'value_varchar' => NULL,
    'extra' => 'a:2:{i:0;s:1:"1";i:1;s:1:"5";}',
  ],
];
}
