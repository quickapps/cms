<ul class="breadcrumb">
	<?php
		$out = array();

		foreach ($breadcrumb as $item) {
			$item['options']['style'] = $item['active'] ? 'text-decoration:underline;' : '';
			echo '<li>' . $this->Html->link($item['title'], $item['url'], $item['options']) . ' <span class="divider">/</span></li>';
		}
	?>
</ul>