<?php header('Content-Type: text/xml'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss version="2.0">
	<channel>
		<title><?php echo $this->Layout->title(); ?></title>
		<link><?php echo $this->Html->url($this->here, true); ?></link>
		<language><?php echo Configure::read('Variable.language.code'); ?></language>
		<description><?php echo is_string(Configure::read('Variable.site_description')) ? Configure::read('Variable.site_description') : ''; ?></description>
		<generator>QuickApps v<?php echo Configure::read('Variable.qa_version'); ?></generator>

		<?php
			foreach ($Layout['node'] as $node):
				$nodeTime = $node['Node']['created'];
				$nodeLink = "/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html";
				$nodeBody = $this->Node->render($node);

				echo $this->Rss->item(array(),
					array(
						'title' => $this->Layout->hooktags($node['Node']['title']),
						'link' => $nodeLink,
						'guid' => array('url' => $nodeLink, 'isPermaLink' => 'true'),
						'description' => $nodeBody,
						'author' => (string)$node['CreatedBy']['username'],
						'pubDate' => $node['Node']['created']
					)
				);
				echo "\n\n";
			endforeach;
		?>
	</channel>
</rss>