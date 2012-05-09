<?php
	if (!empty($types)):
		$links = array();

		foreach ($types as $type) {
			$links[] = array(
				'router_path' => '/admin/node/contents/add/' . $type['NodeType']['id'],
				'link_title' => __t($type['NodeType']['name']),
				'description' => __t($type['NodeType']['description'])
			);
		}

		$menu['MenuLink'] = $links;
		$menu['region'] = 'content';
		$menu['id'] = 'nodeTypesMenu';

		echo $this->element('theme_menu', array('menu' => $menu));
	else:
?>

<h3><?php echo __t('There are no content types availables'); ?></h3>
<em><?php echo __t('Go to <a href="%s">Content Types</a> section for create a new content type', $this->Html->url('/admin/node/types')); ?></em>

<?php endif; ?>