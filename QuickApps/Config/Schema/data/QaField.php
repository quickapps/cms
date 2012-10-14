<?php
class QaField {
	public $table = 'fields';
	public $records = array(
		array(
			'id' => '1',
			'name' => 'field_page_body',
			'label' => 'Body',
			'belongsTo' => 'NodeType-page',
			'field_module' => 'FieldText',
			'description' => '',
			'required' => '1',
			'settings' => 'a:7:{s:7:"display";a:4:{s:7:"default";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:4:"full";s:8:"settings";a:0:{}s:8:"ordering";i:1;s:11:"trim_length";s:3:"180";}s:4:"full";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:4:"full";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:3:"600";}s:4:"list";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:7:"trimmed";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:3:"400";}s:3:"rss";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:7:"trimmed";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:3:"400";}}s:4:"type";s:8:"textarea";s:11:"text_format";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";s:15:"text_processing";s:4:"full";}',
			'locked' => '0',
			'ordering' => '1'
		),
		array(
			'id' => '2',
			'name' => 'field_article_introduction',
			'label' => 'Introduction',
			'belongsTo' => 'NodeType-article',
			'field_module' => 'FieldText',
			'description' => '',
			'required' => '1',
			'settings' => 'a:6:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"display";a:4:{s:7:"default";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}s:4:"full";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}s:4:"list";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:5:"plain";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}s:3:"rss";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:5:"plain";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}}s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
			'locked' => '0',
			'ordering' => '1'
		),
		array(
			'id' => '3',
			'name' => 'field_article_content',
			'label' => 'Article content',
			'belongsTo' => 'NodeType-article',
			'field_module' => 'FieldText',
			'description' => '',
			'required' => '1',
			'settings' => 'a:6:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"display";a:4:{s:7:"default";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:4:"full";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}s:4:"full";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:4:"full";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}s:4:"list";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}s:3:"rss";a:5:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";s:8:"settings";a:0:{}s:8:"ordering";i:0;s:11:"trim_length";s:0:"";}}s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
			'locked' => '0',
			'ordering' => '1'
		),
		array(
			'id' => '4',
			'name' => 'field_article_category',
			'label' => 'Category',
			'belongsTo' => 'NodeType-article',
			'field_module' => 'TaxonomyTerms',
			'description' => '',
			'required' => '1',
			'settings' => 'a:4:{s:7:"display";a:4:{s:7:"default";a:4:{s:5:"label";s:6:"inline";s:4:"type";s:14:"link-localized";s:8:"settings";a:0:{}s:8:"ordering";i:0;}s:4:"list";a:3:{s:5:"label";s:6:"inline";s:4:"type";s:14:"link-localized";s:10:"url_prefix";s:12:"type:article";}s:3:"rss";a:2:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";}s:4:"full";a:3:{s:5:"label";s:6:"inline";s:4:"type";s:15:"plain-localized";s:10:"url_prefix";s:0:"";}}s:10:"vocabulary";s:1:"1";s:4:"type";s:6:"select";s:10:"max_values";s:1:"1";}',
			'locked' => '0',
			'ordering' => '1'
		),
		array(
			'id' => '5',
			'name' => 'field_article_tags',
			'label' => 'Tags',
			'belongsTo' => 'NodeType-article',
			'field_module' => 'TaxonomyTerms',
			'description' => '',
			'required' => '',
			'settings' => 'a:4:{s:7:"display";a:4:{s:7:"default";a:4:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";s:8:"settings";a:0:{}s:8:"ordering";i:0;}s:4:"full";a:3:{s:5:"label";s:6:"inline";s:4:"type";s:14:"link-localized";s:10:"url_prefix";s:12:"type:article";}s:4:"list";a:3:{s:5:"label";s:6:"inline";s:4:"type";s:14:"link-localized";s:10:"url_prefix";s:12:"type:article";}s:3:"rss";a:2:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"hidden";}}s:10:"vocabulary";s:1:"2";s:4:"type";s:12:"autocomplete";s:10:"max_values";s:1:"0";}',
			'locked' => '0',
			'ordering' => '1'
		),
	);

}
