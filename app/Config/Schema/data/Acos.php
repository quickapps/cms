<?php
class Acos {
	public $table = 'acos';
	public $records = array(
		array(
			'id' => '1',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Block',
			'lft' => '1',
			'rght' => '20'
		),
		array(
			'id' => '2',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Block',
			'lft' => '2',
			'rght' => '5'
		),
		array(
			'id' => '3',
			'parent_id' => '2',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '3',
			'rght' => '4'
		),
		array(
			'id' => '4',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Manage',
			'lft' => '6',
			'rght' => '19'
		),
		array(
			'id' => '5',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '7',
			'rght' => '8'
		),
		array(
			'id' => '6',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_move',
			'lft' => '9',
			'rght' => '10'
		),
		array(
			'id' => '7',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_clone',
			'lft' => '11',
			'rght' => '12'
		),
		array(
			'id' => '8',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '13',
			'rght' => '14'
		),
		array(
			'id' => '9',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '15',
			'rght' => '16'
		),
		array(
			'id' => '10',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '17',
			'rght' => '18'
		),
		array(
			'id' => '11',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Comment',
			'lft' => '21',
			'rght' => '38'
		),
		array(
			'id' => '12',
			'parent_id' => '11',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Comment',
			'lft' => '22',
			'rght' => '25'
		),
		array(
			'id' => '13',
			'parent_id' => '12',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '23',
			'rght' => '24'
		),
		array(
			'id' => '14',
			'parent_id' => '11',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'List',
			'lft' => '26',
			'rght' => '37'
		),
		array(
			'id' => '15',
			'parent_id' => '14',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_show',
			'lft' => '27',
			'rght' => '28'
		),
		array(
			'id' => '16',
			'parent_id' => '14',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_view',
			'lft' => '29',
			'rght' => '30'
		),
		array(
			'id' => '17',
			'parent_id' => '14',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_approve',
			'lft' => '31',
			'rght' => '32'
		),
		array(
			'id' => '18',
			'parent_id' => '14',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_unapprove',
			'lft' => '33',
			'rght' => '34'
		),
		array(
			'id' => '19',
			'parent_id' => '14',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '35',
			'rght' => '36'
		),
		array(
			'id' => '20',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Field',
			'lft' => '39',
			'rght' => '46'
		),
		array(
			'id' => '21',
			'parent_id' => '20',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Handler',
			'lft' => '40',
			'rght' => '45'
		),
		array(
			'id' => '22',
			'parent_id' => '21',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '41',
			'rght' => '42'
		),
		array(
			'id' => '23',
			'parent_id' => '21',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_move',
			'lft' => '43',
			'rght' => '44'
		),
		array(
			'id' => '24',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'FieldFile',
			'lft' => '47',
			'rght' => '54'
		),
		array(
			'id' => '25',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Uploadify',
			'lft' => '48',
			'rght' => '53'
		),
		array(
			'id' => '26',
			'parent_id' => '25',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'delete',
			'lft' => '49',
			'rght' => '50'
		),
		array(
			'id' => '27',
			'parent_id' => '25',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'upload',
			'lft' => '51',
			'rght' => '52'
		),
		array(
			'id' => '28',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Locale',
			'lft' => '55',
			'rght' => '98'
		),
		array(
			'id' => '29',
			'parent_id' => '28',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Languages',
			'lft' => '56',
			'rght' => '69'
		),
		array(
			'id' => '30',
			'parent_id' => '29',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '57',
			'rght' => '58'
		),
		array(
			'id' => '31',
			'parent_id' => '29',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_set_default',
			'lft' => '59',
			'rght' => '60'
		),
		array(
			'id' => '32',
			'parent_id' => '29',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '61',
			'rght' => '62'
		),
		array(
			'id' => '33',
			'parent_id' => '29',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '63',
			'rght' => '64'
		),
		array(
			'id' => '34',
			'parent_id' => '29',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_move',
			'lft' => '65',
			'rght' => '66'
		),
		array(
			'id' => '35',
			'parent_id' => '29',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '67',
			'rght' => '68'
		),
		array(
			'id' => '36',
			'parent_id' => '28',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Locale',
			'lft' => '70',
			'rght' => '73'
		),
		array(
			'id' => '37',
			'parent_id' => '36',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '71',
			'rght' => '72'
		),
		array(
			'id' => '38',
			'parent_id' => '28',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Packages',
			'lft' => '74',
			'rght' => '83'
		),
		array(
			'id' => '39',
			'parent_id' => '38',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '75',
			'rght' => '76'
		),
		array(
			'id' => '40',
			'parent_id' => '38',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_download_package',
			'lft' => '77',
			'rght' => '78'
		),
		array(
			'id' => '41',
			'parent_id' => '38',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_uninstall',
			'lft' => '79',
			'rght' => '80'
		),
		array(
			'id' => '42',
			'parent_id' => '38',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_install',
			'lft' => '81',
			'rght' => '82'
		),
		array(
			'id' => '43',
			'parent_id' => '28',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Translations',
			'lft' => '84',
			'rght' => '97'
		),
		array(
			'id' => '44',
			'parent_id' => '43',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '85',
			'rght' => '86'
		),
		array(
			'id' => '45',
			'parent_id' => '43',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_list',
			'lft' => '87',
			'rght' => '88'
		),
		array(
			'id' => '46',
			'parent_id' => '43',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '89',
			'rght' => '90'
		),
		array(
			'id' => '47',
			'parent_id' => '43',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '91',
			'rght' => '92'
		),
		array(
			'id' => '48',
			'parent_id' => '43',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_regenerate',
			'lft' => '93',
			'rght' => '94'
		),
		array(
			'id' => '49',
			'parent_id' => '43',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '95',
			'rght' => '96'
		),
		array(
			'id' => '50',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Menu',
			'lft' => '99',
			'rght' => '122'
		),
		array(
			'id' => '51',
			'parent_id' => '50',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Manage',
			'lft' => '100',
			'rght' => '117'
		),
		array(
			'id' => '52',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '101',
			'rght' => '102'
		),
		array(
			'id' => '53',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '103',
			'rght' => '104'
		),
		array(
			'id' => '54',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '105',
			'rght' => '106'
		),
		array(
			'id' => '55',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '107',
			'rght' => '108'
		),
		array(
			'id' => '56',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete_link',
			'lft' => '109',
			'rght' => '110'
		),
		array(
			'id' => '57',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add_link',
			'lft' => '111',
			'rght' => '112'
		),
		array(
			'id' => '58',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit_link',
			'lft' => '113',
			'rght' => '114'
		),
		array(
			'id' => '59',
			'parent_id' => '51',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_links',
			'lft' => '115',
			'rght' => '116'
		),
		array(
			'id' => '60',
			'parent_id' => '50',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Menu',
			'lft' => '118',
			'rght' => '121'
		),
		array(
			'id' => '61',
			'parent_id' => '60',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '119',
			'rght' => '120'
		),
		array(
			'id' => '62',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Node',
			'lft' => '123',
			'rght' => '166'
		),
		array(
			'id' => '63',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Contents',
			'lft' => '124',
			'rght' => '137'
		),
		array(
			'id' => '64',
			'parent_id' => '63',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '125',
			'rght' => '126'
		),
		array(
			'id' => '65',
			'parent_id' => '63',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '127',
			'rght' => '128'
		),
		array(
			'id' => '66',
			'parent_id' => '63',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_create',
			'lft' => '129',
			'rght' => '130'
		),
		array(
			'id' => '67',
			'parent_id' => '63',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '131',
			'rght' => '132'
		),
		array(
			'id' => '68',
			'parent_id' => '63',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '133',
			'rght' => '134'
		),
		array(
			'id' => '69',
			'parent_id' => '63',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_clear_cache',
			'lft' => '135',
			'rght' => '136'
		),
		array(
			'id' => '70',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Node',
			'lft' => '138',
			'rght' => '147'
		),
		array(
			'id' => '71',
			'parent_id' => '70',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '139',
			'rght' => '140'
		),
		array(
			'id' => '72',
			'parent_id' => '70',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'index',
			'lft' => '141',
			'rght' => '142'
		),
		array(
			'id' => '73',
			'parent_id' => '70',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'details',
			'lft' => '143',
			'rght' => '144'
		),
		array(
			'id' => '74',
			'parent_id' => '70',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'search',
			'lft' => '145',
			'rght' => '146'
		),
		array(
			'id' => '75',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Types',
			'lft' => '148',
			'rght' => '165'
		),
		array(
			'id' => '76',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '149',
			'rght' => '150'
		),
		array(
			'id' => '77',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '151',
			'rght' => '152'
		),
		array(
			'id' => '78',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '153',
			'rght' => '154'
		),
		array(
			'id' => '79',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '155',
			'rght' => '156'
		),
		array(
			'id' => '80',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_display',
			'lft' => '157',
			'rght' => '158'
		),
		array(
			'id' => '81',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_field_settings',
			'lft' => '159',
			'rght' => '160'
		),
		array(
			'id' => '82',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_field_formatter',
			'lft' => '161',
			'rght' => '162'
		),
		array(
			'id' => '83',
			'parent_id' => '75',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_fields',
			'lft' => '163',
			'rght' => '164'
		),
		array(
			'id' => '84',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'System',
			'lft' => '167',
			'rght' => '216'
		),
		array(
			'id' => '85',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Configuration',
			'lft' => '168',
			'rght' => '171'
		),
		array(
			'id' => '86',
			'parent_id' => '85',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '169',
			'rght' => '170'
		),
		array(
			'id' => '87',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Dashboard',
			'lft' => '172',
			'rght' => '175'
		),
		array(
			'id' => '88',
			'parent_id' => '87',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '173',
			'rght' => '174'
		),
		array(
			'id' => '89',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Help',
			'lft' => '176',
			'rght' => '181'
		),
		array(
			'id' => '90',
			'parent_id' => '89',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '177',
			'rght' => '178'
		),
		array(
			'id' => '91',
			'parent_id' => '89',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_module',
			'lft' => '179',
			'rght' => '180'
		),
		array(
			'id' => '92',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Modules',
			'lft' => '182',
			'rght' => '193'
		),
		array(
			'id' => '93',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '183',
			'rght' => '184'
		),
		array(
			'id' => '94',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_settings',
			'lft' => '185',
			'rght' => '186'
		),
		array(
			'id' => '95',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_toggle',
			'lft' => '187',
			'rght' => '188'
		),
		array(
			'id' => '96',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_uninstall',
			'lft' => '189',
			'rght' => '190'
		),
		array(
			'id' => '97',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_install',
			'lft' => '191',
			'rght' => '192'
		),
		array(
			'id' => '98',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Structure',
			'lft' => '194',
			'rght' => '197'
		),
		array(
			'id' => '99',
			'parent_id' => '98',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '195',
			'rght' => '196'
		),
		array(
			'id' => '100',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'System',
			'lft' => '198',
			'rght' => '201'
		),
		array(
			'id' => '101',
			'parent_id' => '100',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '199',
			'rght' => '200'
		),
		array(
			'id' => '102',
			'parent_id' => '84',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Themes',
			'lft' => '202',
			'rght' => '215'
		),
		array(
			'id' => '103',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '203',
			'rght' => '204'
		),
		array(
			'id' => '104',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_set_theme',
			'lft' => '205',
			'rght' => '206'
		),
		array(
			'id' => '105',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_settings',
			'lft' => '207',
			'rght' => '208'
		),
		array(
			'id' => '106',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_uninstall',
			'lft' => '209',
			'rght' => '210'
		),
		array(
			'id' => '107',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_install',
			'lft' => '211',
			'rght' => '212'
		),
		array(
			'id' => '108',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_theme_tn',
			'lft' => '213',
			'rght' => '214'
		),
		array(
			'id' => '109',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Taxonomy',
			'lft' => '217',
			'rght' => '240'
		),
		array(
			'id' => '110',
			'parent_id' => '109',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Taxonomy',
			'lft' => '218',
			'rght' => '221'
		),
		array(
			'id' => '111',
			'parent_id' => '110',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '219',
			'rght' => '220'
		),
		array(
			'id' => '112',
			'parent_id' => '109',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Vocabularies',
			'lft' => '222',
			'rght' => '239'
		),
		array(
			'id' => '113',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '223',
			'rght' => '224'
		),
		array(
			'id' => '114',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '225',
			'rght' => '226'
		),
		array(
			'id' => '115',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_move',
			'lft' => '227',
			'rght' => '228'
		),
		array(
			'id' => '116',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '229',
			'rght' => '230'
		),
		array(
			'id' => '117',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '231',
			'rght' => '232'
		),
		array(
			'id' => '118',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_terms',
			'lft' => '233',
			'rght' => '234'
		),
		array(
			'id' => '119',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete_term',
			'lft' => '235',
			'rght' => '236'
		),
		array(
			'id' => '120',
			'parent_id' => '112',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit_term',
			'lft' => '237',
			'rght' => '238'
		),
		array(
			'id' => '121',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'User',
			'lft' => '241',
			'rght' => '306'
		),
		array(
			'id' => '122',
			'parent_id' => '121',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Display',
			'lft' => '242',
			'rght' => '247'
		),
		array(
			'id' => '123',
			'parent_id' => '122',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '243',
			'rght' => '244'
		),
		array(
			'id' => '124',
			'parent_id' => '122',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_field_formatter',
			'lft' => '245',
			'rght' => '246'
		),
		array(
			'id' => '125',
			'parent_id' => '121',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Fields',
			'lft' => '248',
			'rght' => '253'
		),
		array(
			'id' => '126',
			'parent_id' => '125',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '249',
			'rght' => '250'
		),
		array(
			'id' => '127',
			'parent_id' => '125',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_field_settings',
			'lft' => '251',
			'rght' => '252'
		),
		array(
			'id' => '128',
			'parent_id' => '121',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'List',
			'lft' => '254',
			'rght' => '267'
		),
		array(
			'id' => '129',
			'parent_id' => '128',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '255',
			'rght' => '256'
		),
		array(
			'id' => '130',
			'parent_id' => '128',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '257',
			'rght' => '258'
		),
		array(
			'id' => '131',
			'parent_id' => '128',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_block',
			'lft' => '259',
			'rght' => '260'
		),
		array(
			'id' => '132',
			'parent_id' => '128',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_activate',
			'lft' => '261',
			'rght' => '262'
		),
		array(
			'id' => '133',
			'parent_id' => '128',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_add',
			'lft' => '263',
			'rght' => '264'
		),
		array(
			'id' => '134',
			'parent_id' => '128',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '265',
			'rght' => '266'
		),
		array(
			'id' => '135',
			'parent_id' => '121',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Permissions',
			'lft' => '268',
			'rght' => '275'
		),
		array(
			'id' => '136',
			'parent_id' => '135',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '269',
			'rght' => '270'
		),
		array(
			'id' => '137',
			'parent_id' => '135',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '271',
			'rght' => '272'
		),
		array(
			'id' => '138',
			'parent_id' => '135',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_toggle',
			'lft' => '273',
			'rght' => '274'
		),
		array(
			'id' => '139',
			'parent_id' => '121',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Roles',
			'lft' => '276',
			'rght' => '283'
		),
		array(
			'id' => '140',
			'parent_id' => '139',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '277',
			'rght' => '278'
		),
		array(
			'id' => '141',
			'parent_id' => '139',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_edit',
			'lft' => '279',
			'rght' => '280'
		),
		array(
			'id' => '142',
			'parent_id' => '139',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_delete',
			'lft' => '281',
			'rght' => '282'
		),
		array(
			'id' => '143',
			'parent_id' => '121',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'User',
			'lft' => '284',
			'rght' => '305'
		),
		array(
			'id' => '144',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '285',
			'rght' => '286'
		),
		array(
			'id' => '145',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'login',
			'lft' => '287',
			'rght' => '288'
		),
		array(
			'id' => '146',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'logout',
			'lft' => '289',
			'rght' => '290'
		),
		array(
			'id' => '147',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_login',
			'lft' => '291',
			'rght' => '292'
		),
		array(
			'id' => '148',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_logout',
			'lft' => '293',
			'rght' => '294'
		),
		array(
			'id' => '149',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'register',
			'lft' => '295',
			'rght' => '296'
		),
		array(
			'id' => '150',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'activate',
			'lft' => '297',
			'rght' => '298'
		),
		array(
			'id' => '151',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'password_recovery',
			'lft' => '299',
			'rght' => '300'
		),
		array(
			'id' => '152',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'profile',
			'lft' => '301',
			'rght' => '302'
		),
		array(
			'id' => '153',
			'parent_id' => '143',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'my_account',
			'lft' => '303',
			'rght' => '304'
		),
		array(
			'id' => '154',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'TaxonomyTerms',
			'lft' => '307',
			'rght' => '312'
		),
		array(
			'id' => '155',
			'parent_id' => '154',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Tokeninput',
			'lft' => '308',
			'rght' => '311'
		),
		array(
			'id' => '156',
			'parent_id' => '155',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_suggest',
			'lft' => '309',
			'rght' => '310'
		),
	);

}
