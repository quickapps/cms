<?php
class QaNodeType {
	public $table = 'node_types';
	public $records = array(
		array(
			'id' => 'article',
			'name' => 'Article',
			'base' => 'node',
			'module' => 'Node',
			'description' => 'Use articles for time-sensitive content like news, press releases or blog posts.',
			'title_label' => 'Title',
			'comments_approve' => '',
			'comments_per_page' => '10',
			'comments_anonymous' => '2',
			'comments_subject_field' => '',
			'node_show_author' => '1',
			'node_show_date' => '1',
			'default_comment' => '2',
			'default_language' => '',
			'default_status' => '1',
			'default_promote' => '0',
			'default_sticky' => '0',
			'status' => '1'
		),
		array(
			'id' => 'page',
			'name' => 'Basic page',
			'base' => 'node',
			'module' => 'Node',
			'description' => 'Use <em>basic pages</em> for your static content, such as an \'About us\' page.',
			'title_label' => 'Title',
			'comments_approve' => '1',
			'comments_per_page' => '10',
			'comments_anonymous' => '2',
			'comments_subject_field' => '1',
			'node_show_author' => '',
			'node_show_date' => '',
			'default_comment' => '0',
			'default_language' => 'es',
			'default_status' => '1',
			'default_promote' => '0',
			'default_sticky' => '0',
			'status' => '1'
		),
	);

}
